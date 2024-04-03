<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\UseCases\ViewOptions;

final class Result
{
    /**
     * @param array<string, mixed> $data
     */
    public function __construct(
        public array $data,
    ) {
    }
}
