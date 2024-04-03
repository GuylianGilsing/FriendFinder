<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\UseCases\CreateRequest;

enum ResultMessage: string
{
    case SUCCEEDED = 'create-request-succeeded';
    case ARGS_ERROR = 'create-request-args-error';
    case FAILED = 'create-request-failed';
}
