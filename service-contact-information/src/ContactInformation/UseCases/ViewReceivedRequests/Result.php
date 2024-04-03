<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\UseCases\ViewReceivedRequests;

final class Result
{
    /**
     * @param array<string, mixed> $receivedRequests
     * @param array<string, mixed> $argumentErrors An associative array that holds all argument error messages.
     */
    public function __construct(
        public ResultMessage $message,
        public array $receivedRequests = [],
        public string $identity = '',
        public array $argumentErrors = [],
    ) {
    }
}
