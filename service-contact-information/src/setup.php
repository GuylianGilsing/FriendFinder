<?php

declare(strict_types=1);

use Framework\API\REST;
use Psr\Log\LoggerInterface;
use Slim\App;

function configure_application(App $app): void
{
    \Doctrine\DBAL\Types\Type::addType('uuid', Ramsey\Uuid\Doctrine\UuidType::class);

    /** @var LoggerInterface $logger */
    $logger = $app->getContainer()->get(LoggerInterface::class);

    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();
    $app->addErrorMiddleware(displayErrorDetails: true, logErrors: true, logErrorDetails: true, logger: $logger);

    $app->add(\FriendFinder\Common\Middleware\StandardizeURLMiddleware::class);

    $app->get('/healthcheck', \FriendFinder\ContactInformation\HealthCheck\Handler::class);
}

function configure_rest_api(REST $api): void
{
    $api->registerEndpoint(new \FriendFinder\ContactInformation\Endpoints\AnswerRequestEndpoint());
    $api->registerEndpoint(new \FriendFinder\ContactInformation\Endpoints\CreateRequestEndpoint());
    $api->registerEndpoint(new \FriendFinder\ContactInformation\Endpoints\ViewOptionsEndpoint());
    $api->registerEndpoint(new \FriendFinder\ContactInformation\Endpoints\ViewReceivedRequestsEndpoint());
    $api->registerEndpoint(new \FriendFinder\ContactInformation\Endpoints\ViewRequestAnswerEndpoint());
    $api->registerEndpoint(new \FriendFinder\ContactInformation\Endpoints\ViewRequestDetailsEndpoint());
}
