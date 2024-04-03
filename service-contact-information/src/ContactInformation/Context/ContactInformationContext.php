<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\Context;

final class ContactInformationContext
{
    /**
     * @return array<string>
     */
    public function allowedSocials(): array
    {
        return ['x', 'discord', 'instagram', 'snapchat', 'phone_number', 'email'];
    }
}
