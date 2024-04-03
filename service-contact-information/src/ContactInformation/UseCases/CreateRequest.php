<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\UseCases;

use Exception;
use Framework\API\UseCases\UseCaseInterface;
use FriendFinder\ContactInformation\Domain\ContactInformationRequest;
use FriendFinder\ContactInformation\Repositories\ContactInformationRequestRepositoryInterface;
use FriendFinder\ContactInformation\UseCases\CreateRequest\ArgsValidator;
use FriendFinder\ContactInformation\UseCases\CreateRequest\Result;
use FriendFinder\ContactInformation\UseCases\CreateRequest\ResultMessage;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Rfc4122\UuidV4;

final class CreateRequest implements UseCaseInterface
{
    public function __construct(
        private readonly ArgsValidator $argsValidator,
        private readonly ContactInformationRequestRepositoryInterface $contactInformationRequestRepository,
        private readonly LoggerInterface $logger,
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

        $request = $this->generateRequestObject(
            senderProfileID: $args['identity'],
            receiverProfileID: $args['requestBody']['receiverProfileID'],
            message: $args['requestBody']['message'],
            socials: $args['requestBody']['socials'],
        );

        if ($request !== null) {
            $requestErrors = $this->getRequestErrors($request);

            if ($requestErrors !== null) {
                return new Result(
                    message: ResultMessage::FAILED,
                    identity: '',
                    errors: ['request' => $requestErrors],
                );
            }

            $request = $this->contactInformationRequestRepository->create($request);
        }

        if ($request === null) {
            return new Result(
                message: ResultMessage::FAILED,
                identity: '',
                errors: ['general' => 'Can\'t create contact information request'],
            );
        }

        return new Result(
            message: ResultMessage::SUCCEEDED,
            identity: $args['identity'],
            request: $request,
        );
    }

    /**
     * @param array<string, string> $socials
     */
    private function generateRequestObject(
        string $senderProfileID,
        string $receiverProfileID,
        string $message,
        array $socials,
    ): ?ContactInformationRequest {
        try {
            $request = new ContactInformationRequest(
                id: null,
                senderProfileID: $senderProfileID,
                receiverProfileID: $receiverProfileID,
                message: $message,
                socials: $socials,
                answer: null,
            );
        } catch (Exception $e) {
            $this->logger->warning('(CreateRequest) '.$e->getMessage());

            return null;
        }

        return $request;
    }

    private function getRequestErrors(ContactInformationRequest $request): ?string
    {
        if ($request->getSenderProfileID() === $request->getReceiverProfileID()) {
            return 'A contact information request can\'t be send to yourself';
        }

        if (!UuidV4::isValid($request->getSenderProfileID())) {
            return 'Sender profile ID does not contain a valid UUID';
        }

        if (!UuidV4::isValid($request->getReceiverProfileID())) {
            return 'Receiver profile ID does not contain a valid UUID';
        }

        $sameRequest = $this->contactInformationRequestRepository->findByProfileIDs(
            $request->getSenderProfileID(),
            $request->getReceiverProfileID()
        );

        if ($sameRequest !== null) {
            return 'A contact information request can only be send once';
        }

        return null;
    }
}
