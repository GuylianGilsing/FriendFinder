<?php

declare(strict_types=1);

namespace FriendFinder\ProfileInformation\UseCases;

use Framework\API\UseCases\UseCaseInterface;
use FriendFinder\ProfileInformation\Repositories\ProfileRepositoryInterface;
use FriendFinder\ProfileInformation\UseCases\ViewProfile\ViewProfileResult;
use PHPValidation\ValidatorInterface;

final class ViewProfile implements UseCaseInterface
{
    public function __construct(
        private readonly ProfileRepositoryInterface $profileRepository,
        private readonly ValidatorInterface $validator,
    ) {
    }

    /**
     * @param array<string, mixed> $args
     *
     * @return ViewProfileResult
     */
    public function invoke(array $args = []): object
    {
        if (!$this->validator->isValid($args)) {
            return new ViewProfileResult(profile: null);
        }

        return new ViewProfileResult(
            profile: $this->profileRepository->getByIdentity($args['identity'])
        );
    }
}
