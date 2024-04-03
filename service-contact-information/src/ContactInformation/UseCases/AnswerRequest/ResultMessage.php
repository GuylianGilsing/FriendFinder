<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\UseCases\AnswerRequest;

enum ResultMessage: string
{
    case SUCCEEDED = 'answer-request-succeeded';
    case NOT_FOUND = 'answer-request-not-found';
    case NOT_AUTHORIZED = 'answer-request-not-authorized';
    case ARGS_ERROR = 'answer-request-args-error';
    case FAILED = 'answer-request-failed';
}
