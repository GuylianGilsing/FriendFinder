<?php

declare(strict_types=1);

use FriendFinder\Search\EventProcessor\SearchEventProcessor;
use Spiral\RoadRunner\Jobs\Consumer;
use Spiral\RoadRunner\Jobs\Task\ReceivedTaskInterface;

use function FriendFinder\get_dependency_container_instance;

require_once __DIR__.'/../../vendor/autoload.php';

$dependencyContainer = get_dependency_container_instance();

/** @var SearchEventProcessor $eventProcessor */
$eventProcessor = $dependencyContainer->get(SearchEventProcessor::class);

$consumer = new Consumer();

/** @var ReceivedTaskInterface $task */
while ($task = $consumer->waitTask()) {
    $eventProcessor->process($task);
}
