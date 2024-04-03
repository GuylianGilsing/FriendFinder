<?php

declare(strict_types=1);

namespace FriendFinder\Search\DependencyInjection;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use FriendFinder\Search\Repositories\NoSQL\ElasticsearchSearchProfileInformationRepository;
use FriendFinder\Search\Repositories\SearchProfileInformationRepositoryInterface;

/**
 * @return array<string, mixed> A configured PHP-DI dependency definition array.
 */
function search_common_dependency_injection_definitions(): array
{
    return [
        Client::class => \DI\factory(static function () {
            return ClientBuilder::create()
                ->setHosts(ELASTICSEARCH_HOSTS)
                ->setBasicAuthentication(ELASTICSEARCH_USERNAME, ELASTICSEARCH_PASSWORD)
                ->build();
        }),

        SearchProfileInformationRepositoryInterface::class => \DI\autowire(
            ElasticsearchSearchProfileInformationRepository::class
        ),
    ];
}
