<?php

declare(strict_types=1);

namespace FriendFinder\ProfileInformation\Validators;

use DateTimeImmutable;
use DateTimeInterface;
use PHPValidation\Factories\ValidatorFactory;
use PHPValidation\ValidatorInterface;

use function PHPValidation\Functions\dateHasFormat;
use function PHPValidation\Functions\dateLowerEqual;
use function PHPValidation\Functions\isArray;
use function PHPValidation\Functions\isString;
use function PHPValidation\Functions\notEmpty;
use function PHPValidation\Functions\required;

final class CreateProfileValidator implements ValidatorInterface
{
    private ValidatorInterface $validator;

    public function __construct(
        private readonly ValidatorFactory $validatorFactory,
    ) {
        $dateFormat = DateTimeInterface::RFC3339_EXTENDED;
        $date18YearsAgo = (new DateTimeImmutable())->modify('-18 year');
        $validators = [
            'displayName' => [required(), isString(), notEmpty()],
            'dateOfBirth' => [
                required(), isString(), dateHasFormat($dateFormat), dateLowerEqual($date18YearsAgo, $dateFormat),
            ],
            'interests' => [required(), isArray(), notEmpty()],
        ];
        $errorMessages = [];

        $this->validator = $validatorFactory->createDefaultValidator($validators, $errorMessages);
    }
    /**
     * @param array<string, mixed> $data
     */
    public function isValid(array $data): bool
    {
        return $this->validator->isValid($data);
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
        return $this->validator->getErrorMessages();
    }
}
