<?php

declare(strict_types=1);

namespace FriendFinder\Search\UseCases\SearchProfiles;

final class Result
{
    /**
     * @param array<array<string, mixed>> $data
     * @param array<string, mixed> $argumentErrors An associative array that holds all argument error messages.
     */
    public function __construct(
        public readonly ResultMessage $message,
        public readonly ?array $data = null,
        public array $argumentErrors = [],
    ) {
    }
}
