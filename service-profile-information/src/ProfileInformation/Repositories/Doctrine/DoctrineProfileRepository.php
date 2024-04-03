<?php

declare(strict_types=1);

namespace FriendFinder\ProfileInformation\Repositories\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use ErrorException;
use Exception;
use FriendFinder\Common\Events\ProfileInformation\ProfileInformationUpdatedEvent;
use FriendFinder\Common\Events\ProfileInformation\ProfileInformationUpdatedEventType;
use FriendFinder\Common\Serialization\JSONSerializableInterface;
use FriendFinder\ProfileInformation\Domain\Profile;
use FriendFinder\ProfileInformation\Repositories\ProfileRepositoryInterface;
use Psr\Log\LoggerInterface;
use Spiral\RoadRunner\Jobs\JobsInterface;

final class DoctrineProfileRepository implements ProfileRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
        private readonly JobsInterface $jobs,
    ) {
    }

    public function getByIdentity(string $identity): ?Profile
    {
        $repository = $this->getRepository();
        $profile = $repository->findOneBy(['identity' => $identity]);

        if (!($profile instanceof Profile)) {
            return null;
        }

        return $profile;
    }

    /**
     * @throws ErrorException when the create/update call couldn't be sent to a queue.
     */
    public function createOrUpdate(Profile $entity): Profile
    {
        $event = $this->constructProfileInformationEvent(ProfileInformationUpdatedEventType::UPDATED, $entity);

        if (!$this->sentProfileInformationEventToQueue($event)) {
            throw new ErrorException('Can\'t send task to queue');
        }

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    /**
     * @throws ErrorException when the delete call couldn't be sent to a queue.
     */
    public function delete(string $identity): void
    {
        $entity = $this->getByIdentity($identity);

        if ($entity === null) {
            return;
        }

        $event = $this->constructProfileInformationEvent(ProfileInformationUpdatedEventType::DELETED, $entity);

        if (!$this->sentProfileInformationEventToQueue($event)) {
            throw new ErrorException('Can\'t send task to queue');
        }

        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    private function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(Profile::class);
    }

    private function constructProfileInformationEvent(
        ProfileInformationUpdatedEventType $eventType,
        JSONSerializableInterface $obj,
    ): ProfileInformationUpdatedEvent {
        return new ProfileInformationUpdatedEvent($eventType, $obj->toJSONArray());
    }

    private function sentProfileInformationEventToQueue(ProfileInformationUpdatedEvent $event): bool
    {
        $queue = $this->jobs->connect(PROFILE_INFORMATION_QUEUE_NAME);
        $eventPayload = $event->toJSONString();

        if ($eventPayload === null) {
            $this->logger->warning('Event can\'t be serialized to JSON string', [
                'type' => $event->getType()->value,
                'payload' => $event->getPayload(),
                'createdAt' => $event->getCreatedAt()->format('Y-m-d H:i:s'),
            ]);
            return false;
        }

        try {
            $task = $queue->create(name: 'ProfileInformationUpdated', payload: $eventPayload);
            $queue->dispatch($task);

            return true;
        } catch (Exception $e) {
            $this->logger->critical('(DoctrineProfileRepository) Can\'t queue event', ['message' => $e->getMessage()]);
        }

        return false;
    }
}
