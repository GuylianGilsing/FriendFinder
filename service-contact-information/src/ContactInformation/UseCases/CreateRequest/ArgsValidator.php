<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\UseCases\CreateRequest;

use FriendFinder\ContactInformation\Context\ContactInformationContext;
use PHPValidation\Factories\ValidatorFactory;
use PHPValidation\ValidatorInterface;

use function PHPValidation\Functions\isArray;
use function PHPValidation\Functions\isString;
use function PHPValidation\Functions\maxLength;
use function PHPValidation\Functions\minLength;
use function PHPValidation\Functions\notEmpty;
use function PHPValidation\Functions\required;

final class ArgsValidator implements ValidatorInterface
{
    private ValidatorInterface $validator;

    /** @var array<string, mixed> $contactInformationErrorMessages */
    private array $contactInformationErrorMessages = [];

    public function __construct(
        private readonly ValidatorFactory $validatorFactory,
        private readonly ContactInformationContext $context,
    ) {
        $validators = [
            'identity' => [required(), isString(), notEmpty()],
            'requestBody' => [
                'receiverProfileID' => [required(), notEmpty(), isString()],
                'message' => [required(), notEmpty(), isString(), minLength(0), maxLength(1000)],
                'socials' => [required(), isArray(), notEmpty()],
            ],
        ];
        $errorMessages = [];

        $this->validator = $validatorFactory->createDefaultValidator($validators, $errorMessages);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function isValid(array $data): bool
    {
        $this->contactInformationErrorMessages = [];

        if (!$this->validator->isValid($data)) {
            return false;
        }

        $contactInformationErrors = $this->getContactInformationErrors($data['requestBody']);

        if (count($contactInformationErrors) > 0) {
            $this->contactInformationErrorMessages = $contactInformationErrors;

            return false;
        }

        return true;
    }

    /**
     * @return array<string, string|array<string, mixed>> $validators An infinite associative array
     * where each field has a key => string pair that displays a custom error message.
     * ```
     * [
     *     'field1' => [
     *         'required' => "Field1 is required",
     *         'notEmpty' => "Field1 must be filled in",
     *         'minLength' => "Field1 must be at least 6 characters long",
     *         'maxLength' => "Field1 cannot be longer than 32 characters",
     *     ],
     *     'nestedField' => [
     *         'field1' => [
     *             'isNumber' => "Field1 must be a number",
     *             'between' => "Field1 must be between 4 and 21",
     *         ],
     *     ],
     * ]
     * ```
     */
    public function getErrorMessages(): array
    {
        return array_merge($this->validator->getErrorMessages(), $this->contactInformationErrorMessages);
    }

    /**
     * @param array<string, mixed> $arguments
     *
     * @return array<string, mixed>
     */
    private function getContactInformationErrors(array $arguments): array
    {
        // Check socials
        $usedSocials = $arguments['socials'];
        $illegalSocials = [];
        $emptySocials = [];

        foreach (array_keys($usedSocials) as $social) {
            if (!in_array($social, $this->context->allowedSocials())) {
                $illegalSocials[] = $social;
            }

            if (strlen($usedSocials[$social]) === 0) {
                $emptySocials[] = $social;
            }
        }

        if (count($illegalSocials) > 0) {
            return ['socials' => ['illegalValues' => $illegalSocials]];
        }

        if (count($emptySocials) > 0) {
            return ['socials' => ['notEmpty' => $emptySocials]];
        }

        return [];
    }
}
