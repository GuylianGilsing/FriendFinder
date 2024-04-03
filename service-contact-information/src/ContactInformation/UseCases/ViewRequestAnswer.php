<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\UseCases;

use Framework\API\UseCases\UseCaseInterface;
use FriendFinder\ContactInformation\Domain\ContactInformationRequest;
use FriendFinder\ContactInformation\Repositories\ContactInformationRequestRepositoryInterface;
use FriendFinder\ContactInformation\UseCases\ViewRequestAnswer\ArgsValidator;
use FriendFinder\ContactInformation\UseCases\ViewRequestAnswer\Result;
use FriendFinder\ContactInformation\UseCases\ViewRequestAnswer\ResultMessage;

final class ViewRequestAnswer implements UseCaseInterface
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

        $request = $this->contactInformationRequestRepository->findByID($args['requestID']);

        if ($request === null || $request->getAnswer() === null) {
            return new Result(ResultMessage::NOT_FOUND);
        }

        if (!$this->identityIsAllowedToAccesAnswer($args['identity'], $request)) {
            return new Result(ResultMessage::NOT_AUTHORIZED);
        }

        return new Result(ResultMessage::SUCCESS, answer: $request->getAnswer());
    }

    private function identityIsAllowedToAccesAnswer(string $identity, ContactInformationRequest $request): bool
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
