<?php

declare(strict_types=1);

namespace FriendFinder\Common\DependencyInjection;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Factory\UploadedFileFactory;
use Spiral\RoadRunner\Http\PSR7Worker;
use Spiral\RoadRunner\Http\PSR7WorkerInterface;
use Spiral\RoadRunner\Worker;
use Spiral\RoadRunner\WorkerInterface;

/**
 * @return array<string, mixed>
 */
function roadrunner_http_dependency_container_definitions(): array
{
    return [
        ResponseFactoryInterface::class => \DI\autowire(ResponseFactory::class),
        ServerRequestFactoryInterface::class => \DI\autowire(ServerRequestFactory::class),
        StreamFactoryInterface::class => \DI\autowire(StreamFactory::class),
        UploadedFileFactoryInterface::class => \DI\autowire(UploadedFileFactory::class),
        WorkerInterface::class => \DI\factory(static fn () => Worker::create()),
        PSR7WorkerInterface::class => \DI\autowire(PSR7Worker::class),
    ];
}
