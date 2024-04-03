<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\UseCases;

use Framework\API\UseCases\UseCaseInterface;
use FriendFinder\ContactInformation\Domain\ContactInformationRequest;
use FriendFinder\ContactInformation\Domain\ContactInformationRequestAnswer;
use FriendFinder\ContactInformation\Enums\ContactInformationRequestAnswerOption;
use FriendFinder\ContactInformation\Repositories\ContactInformationRequestAnswerRepositoryInterface;
use FriendFinder\ContactInformation\Repositories\ContactInformationRequestRepositoryInterface;
use FriendFinder\ContactInformation\UseCases\AnswerRequest\ArgsValidator;
use FriendFinder\ContactInformation\UseCases\AnswerRequest\Result;
use FriendFinder\ContactInformation\UseCases\AnswerRequest\ResultMessage;

final class AnswerRequest implements UseCaseInterface
{
    public function __construct(
        private readonly ArgsValidator $argsValidator,
        private readonly ContactInformationRequestRepositoryInterface $contactInformationRequestRepository,
        private readonly ContactInformationRequestAnswerRepositoryInterface $contactInformationRequestAnswerRepository,
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
            return new Result(message: ResultMessage::NOT_FOUND, identity: '');
        }

        if (!$this->identityIsAllowedToAnswerRequest($args['identity'], $request)) {
            return new Result(message: ResultMessage::NOT_AUTHORIZED, identity: $args['identity']);
        }

        if ($request->getAnswer() !== null) {
            return new Result(
                message: ResultMessage::FAILED,
                identity: $args['identity'],
                errors: ['general' => 'Contact information request has already been answered'],
            );
        }

        $answerOption = ContactInformationRequestAnswerOption::from($args['requestBody']['answer']);

        $answer = new ContactInformationRequestAnswer(null, $args['identity'], $answerOption, $request);
        $createdAnswer = $this->contactInformationRequestAnswerRepository->create($answer);

        if ($createdAnswer === null) {
            return new Result(
                message: ResultMessage::FAILED,
                identity: $args['identity'],
                errors: ['general' => 'Answer could not be created'],
            );
        }

        return new Result(
            message: ResultMessage::SUCCEEDED,
            identity: $args['identity'],
            answer: $createdAnswer,
        );
    }

    private function identityIsAllowedToAnswerRequest(string $identity, ContactInformationRequest $request): bool
    {
        return $request->getSenderProfileID() !== $identity;
    }
}
