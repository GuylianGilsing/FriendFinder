<?php

declare(strict_types=1);

namespace FriendFinder\Common\Authorization;

/**
 * Indicates that an object holds a reference to the UUID of its owner.
 */
interface IdentityAwareInterface
{
    public function getIdentity(): string;
}
