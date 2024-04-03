<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\Repositories\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use FriendFinder\ContactInformation\Domain\ContactInformationRequest;
use FriendFinder\ContactInformation\Repositories\ContactInformationRequestRepositoryInterface;
use Psr\Log\LoggerInterface;

final class DoctrineContactInformationRequestRepository implements ContactInformationRequestRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function findByID(string $id): ?ContactInformationRequest
    {
        $repository = $this->getRepository();
        $entity = $repository->find($id);

        if (!($entity instanceof ContactInformationRequest)) {
            return null;
        }

        return $entity;
    }

    /**
     * @return array<ContactInformationRequest>
     */
    public function findByIdentity(string $identity): array
    {
        $repository = $this->getRepository();
        $queryBuilder = $repository->createQueryBuilder('o');

        $this->logger->info('findByIdentity', [
            'identity' => $identity,
        ]);

        // Select all contact information requests that's either sent or received by the given identity
        $query = $queryBuilder
            ->select('o')
            ->where($queryBuilder->expr()->eq('o.senderProfileID', ':identity'))
            ->orWhere($queryBuilder->expr()->eq('o.receiverProfileID', ':identity'))
            ->setParameters(['identity' => $identity])
            ->getQuery();

        return $query->getResult();
    }

    public function findByProfileIDs(
        string $senderProfileID,
        string $receiverProfileID,
    ): ?ContactInformationRequest {
        $repository = $this->getRepository();
        $entity = $repository->findOneBy([
            'senderProfileID' => $senderProfileID,
            'receiverProfileID' => $receiverProfileID,
        ]);

        if (!($entity instanceof ContactInformationRequest)) {
            return null;
        }

        return $entity;
    }

    public function create(ContactInformationRequest $entity): ?ContactInformationRequest
    {
        if ($entity->getID() !== null) {
            return $entity;
        }

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        if ($entity->getID() === null) {
            return null;
        }

        return $entity;
    }

    public function update(ContactInformationRequest $entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function delete(string $id): bool
    {
        $entity = $this->findByID($id);

        if ($entity === null) {
            return false;
        }

        $this->entityManager->remove($entity);
        $this->entityManager->flush();

        return true;
    }

    private function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(ContactInformationRequest::class);
    }
}
