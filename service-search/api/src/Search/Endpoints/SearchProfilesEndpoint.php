<?php

declare(strict_types=1);

namespace FriendFinder\Search\Endpoints;

use Framework\API\Endpoints\EndpointInterface;
use Framework\API\Endpoints\RoutingInformation;
use FriendFinder\Search\UseCases\SearchProfiles;
use FriendFinder\Search\UseCases\SearchProfiles\PSR7ArgsFormatter;
use FriendFinder\Search\UseCases\SearchProfiles\PSR7ResultsHandler;

final class SearchProfilesEndpoint implements EndpointInterface
{
    public function getRoutingInformation(): RoutingInformation
    {
        return new RoutingInformation(
            methods: ['GET'],
            path: '/search/profile',
        );
    }

    /**
     * @return array<callable|string>
     */
    public function getMiddlewareStack(): array
    {
        return [];
    }

    public function getRequestValidator(): ?string
    {
        return null;
    }

    public function getUseCaseArgsFormatter(): ?string
    {
        return PSR7ArgsFormatter::class;
    }

    public function getUseCase(): string
    {
        return SearchProfiles::class;
    }

    public function getUseCaseResultHandler(): string
    {
        return PSR7ResultsHandler::class;
    }
}
