<?php

declare(strict_types=1);

namespace FriendFinder\ProfileInformation\UseCases\ViewProfile;

use FriendFinder\ProfileInformation\Domain\Profile;

final class ViewProfileResult
{
    public function __construct(
        public ?Profile $profile,
    ) {
    }
}
