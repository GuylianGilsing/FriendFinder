<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\Repositories;

use FriendFinder\ContactInformation\Domain\ContactInformationRequestAnswer;

interface ContactInformationRequestAnswerRepositoryInterface
{
    /**
     * Find a contact information request answer by its linked request ID.
     *
     * @param string $id The UUID that belongs to the contact information request that contains the answer.
     *
     * @return ?ContactInformationRequestAnswer The contact information request answer if it exists, `null` otherwise.
     */
    public function findByRequestID(string $id): ?ContactInformationRequestAnswer;

    /**
     * Creates a new contact information request answer.
     *
     * @return ?ContactInformationRequestAnswer The contact information request answer if it exists, `null` otherwise.
     */
    public function create(ContactInformationRequestAnswer $entity): ?ContactInformationRequestAnswer;
}
