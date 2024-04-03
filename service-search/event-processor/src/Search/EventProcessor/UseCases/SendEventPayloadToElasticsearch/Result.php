<?php

declare(strict_types=1);

namespace FriendFinder\Search\EventProcessor\UseCases\SendEventPayloadToElasticsearch;

final class Result
{
    /**
     * @param array<string, mixed> $argumentErrors An associative array that holds all argument error messages.
     */
    public function __construct(
        public readonly ResultMessage $message,
        public readonly array $argumentErrors = [],
    ) {
    }
}
