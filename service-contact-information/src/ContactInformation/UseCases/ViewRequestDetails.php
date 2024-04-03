<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\UseCases;

use Framework\API\UseCases\UseCaseInterface;
use FriendFinder\ContactInformation\Domain\ContactInformationRequest;
use FriendFinder\ContactInformation\Repositories\ContactInformationRequestRepositoryInterface;
use FriendFinder\ContactInformation\UseCases\ViewRequestDetails\ArgsValidator;
use FriendFinder\ContactInformation\UseCases\ViewRequestDetails\Result;
use FriendFinder\ContactInformation\UseCases\ViewRequestDetails\ResultMessage;

final class ViewRequestDetails implements UseCaseInterface
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
                identity: '',
                argumentErrors: $this->argsValidator->getErrorMessages(),
            );
        }

        $request = $this->contactInformationRequestRepository->findByID($args['requestID']);

        if ($request === null) {
            return new Result(ResultMessage::NOT_FOUND, $args['identity']);
        }

        if (!$this->identityIsAllowedToAccessRequest($args['identity'], $request)) {
            return new Result(ResultMessage::NOT_AUTHORIZED, $args['identity']);
        }

        return new Result(ResultMessage::SUCCESS, $args['identity'], request: $request);
    }

    public function identityIsAllowedToAccessRequest(string $identity, ContactInformationRequest $request): bool
    {
        // Owner of the contact information request may see it
        if ($identity === $request->getSenderProfileID()) {
            return true;
        }

        // The contact information request recipient may see it
        if ($identity === $request->getReceiverProfileID()) {
            return true;
        }

        return false;
    }
}
