<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\Endpoints;

use Framework\API\Endpoints\EndpointInterface;
use Framework\API\Endpoints\RoutingInformation;
use FriendFinder\ContactInformation\UseCases\ViewOptions;
use FriendFinder\ContactInformation\UseCases\ViewOptions\PSR7ResultHandler;

final class ViewOptionsEndpoint implements EndpointInterface
{
    public function getRoutingInformation(): RoutingInformation
    {
        return new RoutingInformation(
            methods: ['GET'],
            path: '/contact-information/options',
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
        return null;
    }

    public function getUseCase(): string
    {
        return ViewOptions::class;
    }

    public function getUseCaseResultHandler(): string
    {
        return PSR7ResultHandler::class;
    }
}
