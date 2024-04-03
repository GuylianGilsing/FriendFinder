<?php

declare(strict_types=1);

namespace FriendFinder\ProfileInformation\DependencyInjection;

use DateTimeImmutable;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use FriendFinder\ProfileInformation\Repositories\Doctrine\DoctrineProfileRepository;
use FriendFinder\ProfileInformation\Repositories\ProfileRepositoryInterface;
use FriendFinder\ProfileInformation\UseCases\CreateProfile;
use FriendFinder\ProfileInformation\UseCases\CreateProfile\CreateProfilePSR7ArgsValidator;
use FriendFinder\ProfileInformation\UseCases\ViewProfile;
use FriendFinder\ProfileInformation\Validators\CreateProfileValidator;
use FriendFinder\ProfileInformation\Validators\ViewProfileValidator;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Spiral\Goridge\RPC\RPC;
use Spiral\RoadRunner\Jobs\Jobs;
use Spiral\RoadRunner\Jobs\JobsInterface;

/**
 * @return array<string, mixed> A configured PHP-DI dependency definition array.
 */
function get_dependency_definitions(): array
{
    return [
        LoggerInterface::class => \DI\factory(static function () {
            $loggingDir = __DIR__.'/../../logs';

            $logger = new Logger('FriendFinder.Service.ProfileInformation');

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
        JobsInterface::class => \DI\factory(static function () {
            return new Jobs(RPC::create('tcp://127.0.0.1:6001'));
        }),

        ProfileRepositoryInterface::class => \DI\autowire(DoctrineProfileRepository::class),

        CreateProfile::class => \DI\create()->constructor(
            \DI\get(ProfileRepositoryInterface::class),
            \DI\autowire(CreateProfileValidator::class),
            \DI\autowire(CreateProfilePSR7ArgsValidator::class),
            \DI\get(ViewProfile::class),
        ),

        ViewProfile::class => \DI\create()->constructor(
            \DI\get(ProfileRepositoryInterface::class),
            \DI\autowire(ViewProfileValidator::class),
        ),
    ];
}
