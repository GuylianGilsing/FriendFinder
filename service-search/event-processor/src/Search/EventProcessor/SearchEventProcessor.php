<?php

declare(strict_types=1);

namespace FriendFinder\Search\EventProcessor;

use FriendFinder\Search\EventProcessor\UseCases\SendEventPayloadToElasticsearch;
use FriendFinder\Search\EventProcessor\UseCases\SendEventPayloadToElasticsearch\Result;
use FriendFinder\Search\EventProcessor\UseCases\SendEventPayloadToElasticsearch\ResultMessage;
use Psr\Log\LoggerInterface;
use Spiral\RoadRunner\Jobs\Task\ReceivedTaskInterface;

use function FriendFinder\Common\JSON\parse_json_string_to_array;

final class SearchEventProcessor
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly SendEventPayloadToElasticsearch $useCase,
    ) {
    }

    public function process(ReceivedTaskInterface $task): void
    {
        /** @var Result $result */
        $result = $this->useCase->invoke(parse_json_string_to_array($task->getPayload()) ?? []);

        switch ($result->message) {
            case ResultMessage::SUCCEEDED:
                $task->complete();
                break;

            case ResultMessage::ARGS_ERROR:
                $task->fail(error: 'Payload format error', requeue: true);
                $this->logger->error('Use case (SendEventPayloadToElasticsearch) args error', $result->argumentErrors);
                break;

            case ResultMessage::FAILED:
                $task->fail(error: 'Event processing failed', requeue: true);
                $this->logger->error('Use case (SendEventPayloadToElasticsearch) failed');
                break;
        }
    }
}
