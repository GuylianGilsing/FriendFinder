<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\UseCases\ViewRequestDetails;

enum ResultMessage: string
{
    case SUCCESS = 'view-request-details-success';
    case ARGS_ERROR = 'view-request-details-args-error';
    case NOT_FOUND = 'view-request-details-not-found';
    case NOT_AUTHORIZED = 'view-request-details-not-authorized';
}
