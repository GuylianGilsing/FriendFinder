<?php

declare(strict_types=1);

namespace FriendFinder\ProfileInformation\UseCases\CreateProfile;

use FriendFinder\ProfileInformation\Domain\Profile;

final class CreateProfileResult
{
    /**
     * @param array<string, mixed> $argumentErrors An associative array that holds all argument error messages.
     * @param array<string, mixed> $validationErrors An associative array that holds all validation error messages.
     * @param array<string, mixed> $processErrors An associative array that holds all process error messages.
     */
    public function __construct(
        public ?Profile $profile,
        public CreateProfileResultMessage $message,
        public array $argumentErrors = [],
        public array $validationErrors = [],
        public array $processErrors = [],
    ) {
    }
}
