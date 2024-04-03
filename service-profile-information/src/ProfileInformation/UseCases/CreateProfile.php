<?php

declare(strict_types=1);

namespace FriendFinder\ProfileInformation\UseCases;

use DateTimeImmutable;
use DateTimeInterface;
use Framework\API\UseCases\UseCaseInterface;
use FriendFinder\ProfileInformation\Domain\Profile;
use FriendFinder\ProfileInformation\Repositories\ProfileRepositoryInterface;
use FriendFinder\ProfileInformation\UseCases\CreateProfile\CreateProfileResult;
use FriendFinder\ProfileInformation\UseCases\CreateProfile\CreateProfileResultMessage;
use PHPValidation\ValidatorInterface;

final class CreateProfile implements UseCaseInterface
{
    /**
     * @param ViewProfile $viewProfile
     */
    public function __construct(
        private readonly ProfileRepositoryInterface $profileRepository,
        private readonly ValidatorInterface $requestBodyValidator,
        private readonly ValidatorInterface $argsValidator,
        private readonly UseCaseInterface $viewProfile,
    ) {
    }

    /**
     * @param array<string, mixed> $args
     *
     * @return CreateProfileResult
     */
    public function invoke(array $args = []): object
    {
        if (!$this->argsValidator->isValid($args)) {
            return new CreateProfileResult(
                profile: null,
                message: CreateProfileResultMessage::ARGS_ERROR,
                argumentErrors: $this->argsValidator->getErrorMessages(),
            );
        }

        if (!$this->requestBodyValidator->isValid($args['requestBody'])) {
            return new CreateProfileResult(
                profile: null,
                message: CreateProfileResultMessage::ARGS_ERROR,
                argumentErrors: $this->requestBodyValidator->getErrorMessages(),
            );
        }

        $viewProfileResult = $this->viewProfile->invoke(['identity' => $args['identity']]);

        if ($viewProfileResult->profile !== null) {
            return new CreateProfileResult(
                profile: null,
                message: CreateProfileResultMessage::VALIDATION_ERROR,
                validationErrors: ['You already have a profile'],
            );
        }

        $profile = new Profile(
            $args['identity'],
            $args['requestBody']['displayName'],
            DateTimeImmutable::createFromFormat(
                DateTimeInterface::RFC3339_EXTENDED,
                $args['requestBody']['dateOfBirth']
            ),
        );

        foreach ($args['requestBody']['interests'] as $interest) {
            if ($profile->hasInterest($interest)) {
                return new CreateProfileResult(
                    profile: null,
                    message: CreateProfileResultMessage::VALIDATION_ERROR,
                    validationErrors: ['Duplicate interests are not allowed'],
                );
            }

            $profile->addInterest($interest);
        }

        return new CreateProfileResult(
            profile: $this->profileRepository->createOrUpdate($profile),
            message: CreateProfileResultMessage::SUCCEEDED,
        );
    }
}
