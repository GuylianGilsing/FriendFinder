<?php

declare(strict_types=1);

namespace FriendFinder\Common\Serialization;

/**
 * Indicates that an object can be serialized to JSON.
 */
interface JSONSerializableInterface
{
    /**
     * Serializes this object to a JSON array.
     *
     * @return array<string, mixed>
     */
    public function toJSONArray(): array;
}
