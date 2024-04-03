<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\UseCases\AnswerRequest;

use FriendFinder\ContactInformation\Domain\ContactInformationRequestAnswer;

final class Result
{
    /**
     * @param array<string, mixed> $errors An associative array that holds all error messages when the use case failed.
     * @param array<string, mixed> $argumentErrors An associative array that holds all argument error messages.
     */
    public function __construct(
        public ResultMessage $message,
        public string $identity,
        public ?ContactInformationRequestAnswer $answer = null,
        public array $errors = [],
        public array $argumentErrors = [],
    ) {
    }
}
