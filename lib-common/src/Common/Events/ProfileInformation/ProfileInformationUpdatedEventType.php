<?php

declare(strict_types=1);

namespace FriendFinder\Common\Events\ProfileInformation;

enum ProfileInformationUpdatedEventType: string
{
    case UPDATED = 'updated';
    case DELETED = 'deleted';
}
