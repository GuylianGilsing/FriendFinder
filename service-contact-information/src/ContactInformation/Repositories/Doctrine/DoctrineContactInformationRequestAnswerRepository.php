<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\Repositories\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use FriendFinder\ContactInformation\Domain\ContactInformationRequestAnswer;
use FriendFinder\ContactInformation\Repositories\ContactInformationRequestAnswerRepositoryInterface;

final class DoctrineContactInformationRequestAnswerRepository implements ContactInformationRequestAnswerRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function findByRequestID(string $id): ?ContactInformationRequestAnswer
    {
        $repository = $this->getRepository();
        $entity = $repository->findOneBy(['request' => $id]);

        if (!($entity instanceof ContactInformationRequestAnswer)) {
            return null;
        }

        return $entity;
    }

    public function create(ContactInformationRequestAnswer $entity): ?ContactInformationRequestAnswer
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

    private function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(ContactInformationRequestAnswer::class);
    }
}
