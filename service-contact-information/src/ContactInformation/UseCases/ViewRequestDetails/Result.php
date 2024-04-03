<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\UseCases\ViewRequestDetails;

use FriendFinder\ContactInformation\Domain\ContactInformationRequest;

final class Result
{
    /**
     * @param array<string, mixed> $argumentErrors An associative array that holds all argument error messages.
     */
    public function __construct(
        public ResultMessage $message,
        public string $identity,
        public ?ContactInformationRequest $request = null,
        public array $argumentErrors = [],
    ) {
    }
}
