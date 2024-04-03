<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\Enums;

enum ContactInformationRequestAnswerOption: string
{
    case ACCEPTED = 'accepted';
    case DENIED = 'denied';
}
