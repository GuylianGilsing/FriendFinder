<?php

declare(strict_types=1);

namespace FriendFinder\Search\UseCases\SearchProfiles;

enum ResultMessage: string
{
    case SUCCEEDED = 'get-profile-by-id-succeeded';
    case ARGS_ERROR = 'get-profile-by-id-args-error';
    case FAILED = 'get-profile-by-id-failed';
}
