<?php

declare(strict_types=1);

namespace FriendFinder\ProfileInformation\UseCases\DeleteProfile;

enum DeleteProfileResultMessage: string
{
    case SUCCEEDED = 'create-profile-information-succeeded';
    case NOT_FOUND = 'create-profile-information-not-found';
}
