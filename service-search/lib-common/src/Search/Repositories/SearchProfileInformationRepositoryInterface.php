<?php

declare(strict_types=1);

namespace FriendFinder\Search\Repositories;

/**
 * Search service specific implementation contract for a class that retrieves profile information from a NoSQL database.
 */
interface SearchProfileInformationRepositoryInterface
{
    /**
     * @return array<string, mixed> The persisted profile information.
     */
    public function getByIdentity(string $identity): ?array;

    /**
     * @param array<string, mixed> $elasticQuery
     *
     * @return array<array<string, mixed>>
     */
    public function search(array $elasticQuery): array;

    /**
     * @param array<string, mixed> $profileData The profile data from a profile information event.
     */
    public function insert(array $profileData): bool;

    /**
     * @param array<string, mixed> $profileData The profile data from a profile information event.
     */
    public function update(array $profileData): bool;

    public function delete(string $identity): bool;
}
