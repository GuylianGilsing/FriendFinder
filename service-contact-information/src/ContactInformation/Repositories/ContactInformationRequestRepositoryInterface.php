<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\Repositories;

use FriendFinder\ContactInformation\Domain\ContactInformationRequest;

interface ContactInformationRequestRepositoryInterface
{
    /**
     * Find a contact information request by its ID.
     *
     * @param string $id The UUID that belongs to the contact information request that needs to be found.
     *
     * @return ?ContactInformationRequest The contact information requests if it exists, `null` otherwise.
     */
    public function findByID(string $id): ?ContactInformationRequest;

    /**
     * Find contact information requests that belong to a specific identity.
     *
     * @param string $identity the UUID that belongs to the contact information request owner.
     *
     * @return array<ContactInformationRequest>
     */
    public function findByIdentity(string $identity): array;

    /**
     * Find a contact information request by the combination of profile IDs.
     *
     * @param string $senderProfileID The UUID that belongs to the profile ID that sends the contact information
     * request.
     * @param string $receiverProfileID The UUID that belongs to the profile ID that the contact information request is
     * sent to.
     * @param string $identity the UUID that belongs to the contact information request owner.
     *
     * @return ?ContactInformationRequest The contact information requests if it exists, `null` otherwise.
     */
    public function findByProfileIDs(
        string $senderProfileID,
        string $receiverProfileID,
    ): ?ContactInformationRequest;

    /**
     * Create a new contact information request.
     *
     * @param ContactInformationRequest $entity The new contact information request that needs to be persisted.
     *
     * @return ?ContactInformationRequest The contact information requests if it has been created, `null` otherwise.
     */
    public function create(ContactInformationRequest $entity): ?ContactInformationRequest;

    /**
     * Updates an existing contact information request.
     *
     * @param ContactInformationRequest $entity The contact information request that needs to be updated.
     */
    public function update(ContactInformationRequest $entity): void;

    /**
     * Deletes a contact information request.
     *
     * @param string $id The UUID that belongs to the contact information request that needs to be deleted.
     *
     * @return bool `true` when the contact information request could be found and has been deleted, `false` otherwise.
     */
    public function delete(string $id): bool;
}
