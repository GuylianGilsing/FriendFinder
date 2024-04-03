<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\UseCases\ViewRequestAnswer;

enum ResultMessage: string
{
    case SUCCESS = 'view-request-answer-success';
    case ARGS_ERROR = 'view-request-answer-args-error';
    case NOT_FOUND = 'view-request-answer-not-found';
    case NOT_AUTHORIZED = 'answer-request-not-authorized';
}
