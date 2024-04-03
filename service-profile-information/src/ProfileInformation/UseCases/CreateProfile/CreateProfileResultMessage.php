<?php

declare(strict_types=1);

namespace FriendFinder\ProfileInformation\UseCases\CreateProfile;

enum CreateProfileResultMessage: string
{
    case SUCCEEDED = 'create-profile-information-succeeded';
    case VALIDATION_ERROR = 'create-profile-information-validation-error';
    case ARGS_ERROR = 'create-profile-information-args-error';
}
