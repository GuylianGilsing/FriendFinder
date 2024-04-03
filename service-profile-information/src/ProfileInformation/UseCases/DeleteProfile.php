<?php

declare(strict_types=1);

namespace FriendFinder\ProfileInformation\UseCases;

use Framework\API\UseCases\UseCaseInterface;
use FriendFinder\ProfileInformation\Repositories\ProfileRepositoryInterface;
use FriendFinder\ProfileInformation\UseCases\DeleteProfile\DeleteProfilePSR7ArgsValidator;
use FriendFinder\ProfileInformation\UseCases\DeleteProfile\DeleteProfileResult;
use FriendFinder\ProfileInformation\UseCases\DeleteProfile\DeleteProfileResultMessage;

final class DeleteProfile implements UseCaseInterface
{
    public function __construct(
        private readonly DeleteProfilePSR7ArgsValidator $argsValidator,
        private readonly ProfileRepositoryInterface $profileRepository,
    ) {
    }

    /**
     * @param array<string, mixed> $args
     *
     * @return DeleteProfileResult
     */
    public function invoke(array $args = []): object
    {
        if (!$this->argsValidator->isValid($args)) {
            return new DeleteProfileResult(DeleteProfileResultMessage::NOT_FOUND);
        }

        $profile = $this->profileRepository->getByIdentity($args['identity']);

        if ($profile === null) {
            return new DeleteProfileResult(DeleteProfileResultMessage::NOT_FOUND);
        }

        $this->profileRepository->delete($args['identity']);

        return new DeleteProfileResult(DeleteProfileResultMessage::SUCCEEDED);
    }
}
