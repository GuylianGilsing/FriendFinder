<?php

declare(strict_types=1);

namespace FriendFinder\ProfileInformation\UseCases\DeleteProfile;

final class DeleteProfileResult
{
    public function __construct(
        public DeleteProfileResultMessage $message,
    ) {
    }
}
