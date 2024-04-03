<?php

declare(strict_types=1);

namespace FriendFinder\ProfileInformation\Serializers\Profile;

use FriendFinder\ProfileInformation\Domain\Profile;
use FriendFinder\ProfileInformation\Serializers\Interests\InterestArraySerializer;

final class DetailedProfileSerializer
{
    public function __construct(
        private readonly InterestArraySerializer $interestArraySerializer,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function serialize(Profile $profile): array
    {
        return [
            'identity' => $profile->getIdentity(),
            'displayName' => $profile->getDisplayName(),
            'dateOfBirth' => $profile->getDateOfBirth()->format('Y-m-d'),
            'interests' => $this->interestArraySerializer->serialize($profile->getInterests()),
        ];
    }
}
