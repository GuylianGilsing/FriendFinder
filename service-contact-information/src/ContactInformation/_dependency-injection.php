<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\DependencyInjection;

use DateTimeImmutable;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use FriendFinder\ContactInformation\Repositories\ContactInformationRequestAnswerRepositoryInterface;
use FriendFinder\ContactInformation\Repositories\ContactInformationRequestRepositoryInterface;
use FriendFinder\ContactInformation\Repositories\Doctrine\DoctrineContactInformationRequestAnswerRepository;
use FriendFinder\ContactInformation\Repositories\Doctrine\DoctrineContactInformationRequestRepository;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * @return array<string, mixed> A configured PHP-DI dependency definition array.
 */
function get_dependency_definitions(): array
{
    return [
        LoggerInterface::class => \DI\factory(static function () {
            $loggingDir = __DIR__.'/../../logs';

            $logger = new Logger('FriendFinder.Service.ContactInformation');

            // Formulate the log file name
            $logFileName = (new DateTimeImmutable())->format('Y-m-d').'.log';
            $logFilePath = $loggingDir.'/'.$logFileName;

            $logger->pushHandler(
                new StreamHandler($logFilePath, LogLevel::DEBUG)
            );

            return $logger;
        }),
        EntityManagerInterface::class => \DI\factory(static function () {
            $entitiesPath = [__DIR__.'/Domain'];
            $dbParams = [
                'driver' => DB_DRIVER,
                'host' => DB_HOST,
                'port' => DB_PORT,
                'user' => DB_USER,
                'password' => DB_PASSWORD,
                'dbname' => DB_NAME,
            ];

            $config = ORMSetup::createAttributeMetadataConfiguration($entitiesPath);
            $connection = DriverManager::getConnection($dbParams, $config);

            return new EntityManager($connection, $config);
        }),
        ContactInformationRequestRepositoryInterface::class => \DI\autowire(
            DoctrineContactInformationRequestRepository::class
        ),
        ContactInformationRequestAnswerRepositoryInterface::class => \DI\autowire(
            DoctrineContactInformationRequestAnswerRepository::class
        ),
    ];
}
