<?php

declare(strict_types=1);

namespace FriendFinder\ProfileInformation\HealthCheck;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use Spiral\RoadRunner\Jobs\JobsInterface;

final class Handler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly JobsInterface $jobs,
    ) {
    }

    public function __invoke(): ResponseInterface
    {
        $mysqlConnectionResponse = $this->getMySQLConnectionResponse();

        if ($mysqlConnectionResponse !== null) {
            return $mysqlConnectionResponse;
        }

        $messageQueueConnectionResponse = $this->getMessageQueueConnectionResponse();

        if ($messageQueueConnectionResponse !== null) {
            return $messageQueueConnectionResponse;
        }

        return new Response(StatusCodeInterface::STATUS_OK);
    }

    private function getMySQLConnectionResponse(): ?ResponseInterface
    {
        // Try to connect when no connection has been made
        if (
            !$this->entityManager->getConnection()->isConnected() &&
            !$this->entityManager->getConnection()->connect()
        ) {
            return new Response(StatusCodeInterface::STATUS_SERVICE_UNAVAILABLE);
        }

        // Fail safe connection check
        if (!$this->entityManager->getConnection()->isConnected()) {
            return new Response(StatusCodeInterface::STATUS_SERVICE_UNAVAILABLE);
        }

        return null;
    }

    private function getMessageQueueConnectionResponse(): ?ResponseInterface
    {
        try {
            // This probably won't throw anything, still added it just in case somewhere deep inside it throws something
            $this->jobs->connect(PROFILE_INFORMATION_QUEUE_NAME);
        } catch (Exception $e) {
            return new Response(StatusCodeInterface::STATUS_SERVICE_UNAVAILABLE);
        }

        return null;
    }
}
