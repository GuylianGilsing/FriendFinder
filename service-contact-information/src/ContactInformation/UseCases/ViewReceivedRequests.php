<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\UseCases;

use Framework\API\UseCases\UseCaseInterface;
use FriendFinder\ContactInformation\Repositories\ContactInformationRequestRepositoryInterface;
use FriendFinder\ContactInformation\UseCases\ViewReceivedRequests\ArgsValidator;
use FriendFinder\ContactInformation\UseCases\ViewReceivedRequests\Result;
use FriendFinder\ContactInformation\UseCases\ViewReceivedRequests\ResultMessage;

final class ViewReceivedRequests implements UseCaseInterface
{
    public function __construct(
        private readonly ArgsValidator $argsValidator,
        private readonly ContactInformationRequestRepositoryInterface $contactInformationRequestRepository,
    ) {
    }

    /**
     * @param array<string, mixed> $args
     *
     * @return Result
     */
    public function invoke(array $args = []): object
    {
        if (!$this->argsValidator->isValid($args)) {
            return new Result(
                message: ResultMessage::ARGS_ERROR,
                argumentErrors: $this->argsValidator->getErrorMessages(),
            );
        }

        return new Result(
            message: ResultMessage::SUCCESS,
            receivedRequests: $this->contactInformationRequestRepository->findByIdentity($args['identity']),
        );
    }
}
