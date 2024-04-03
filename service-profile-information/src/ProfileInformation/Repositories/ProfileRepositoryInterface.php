<?php

declare(strict_types=1);

namespace FriendFinder\ProfileInformation\Repositories;

use ErrorException;
use FriendFinder\ProfileInformation\Domain\Profile;

interface ProfileRepositoryInterface
{
    public function getByIdentity(string $identity): ?Profile;

    /**
     * @throws ErrorException when the create/update call couldn't be sent to a queue.
     */
    public function createOrUpdate(Profile $profile): Profile;

    /**
     * @throws ErrorException when the delete call couldn't be sent to a queue.
     */
    public function delete(string $identity): void;
}
