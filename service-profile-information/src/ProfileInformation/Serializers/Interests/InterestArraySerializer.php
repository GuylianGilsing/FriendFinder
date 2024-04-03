<?php

declare(strict_types=1);

namespace FriendFinder\ProfileInformation\Serializers\Interests;

use FriendFinder\ProfileInformation\Domain\Interest;

final class InterestArraySerializer
{
    /**
     * @param array<Interest> $interests
     *
     * @return array<string>
     */
    public function serialize(array $interests): array
    {
        $output = [];

        foreach ($interests as $interest) {
            $output[] = $interest->getText();
        }

        return $output;
    }
}
