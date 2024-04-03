<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\HealthCheck;

use Doctrine\ORM\EntityManagerInterface;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;

final class Handler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(): ResponseInterface
    {
        if (
            !$this->entityManager->getConnection()->isConnected() &&
            !$this->entityManager->getConnection()->connect()
        ) {
            return new Response(StatusCodeInterface::STATUS_SERVICE_UNAVAILABLE);
        }

        if (!$this->entityManager->getConnection()->isConnected()) {
            return new Response(StatusCodeInterface::STATUS_SERVICE_UNAVAILABLE);
        }

        return new Response(StatusCodeInterface::STATUS_OK);
    }
}
