<?php

declare(strict_types=1);

namespace FriendFinder\Search\EventProcessor\UseCases\SendEventPayloadToElasticsearch;

enum ResultMessage: string
{
    case SUCCEEDED = 'send-event-payload-to-elastic-search-succeeded';
    case ARGS_ERROR = 'send-event-payload-to-elastic-search-args-error';
    case FAILED = 'send-event-payload-to-elastic-search-failed';
}
