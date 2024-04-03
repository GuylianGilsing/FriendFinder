<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\UseCases\ViewReceivedRequests;

enum ResultMessage: string
{
    case SUCCESS = 'view-received-requests-success';
    case ARGS_ERROR = 'view-received-requests-args-error';
}
