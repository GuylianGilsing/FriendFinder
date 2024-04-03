<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\UseCases\ViewRequestAnswer;

use FriendFinder\ContactInformation\Domain\ContactInformationRequestAnswer;

final class Result
{
    /**
     * @param array<string, mixed> $argumentErrors An associative array that holds all argument error messages.
     */
    public function __construct(
        public ResultMessage $message,
        public ?ContactInformationRequestAnswer $answer = null,
        public array $argumentErrors = [],
    ) {
    }
}
