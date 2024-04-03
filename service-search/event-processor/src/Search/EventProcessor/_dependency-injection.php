<?php

declare(strict_types=1);

namespace FriendFinder\Search\EventProcessor;

use DateTimeImmutable;
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
            $loggingDir = __DIR__.'/../../../logs';

            $logger = new Logger('FriendFinder.Service.Search.EventProcessor');

            // Formulate the log file name
            $logFileName = (new DateTimeImmutable())->format('Y-m-d').'.log';
            $logFilePath = $loggingDir.'/'.$logFileName;

            $logger->pushHandler(
                new StreamHandler($logFilePath, LogLevel::DEBUG)
            );

            return $logger;
        }),
    ];
}
