<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\Endpoints;

use Framework\API\Endpoints\EndpointInterface;
use Framework\API\Endpoints\RoutingInformation;
use FriendFinder\Common\Middleware\RequireAuthBearerToken;
use FriendFinder\ContactInformation\UseCases\ViewRequestDetails;
use FriendFinder\ContactInformation\UseCases\ViewRequestDetails\PSR7ArgsFormatter;
use FriendFinder\ContactInformation\UseCases\ViewRequestDetails\PSR7ResultHandler;

final class ViewRequestDetailsEndpoint implements EndpointInterface
{
    public function getRoutingInformation(): RoutingInformation
    {
        return new RoutingInformation(
            methods: ['GET'],
            path: '/contact-information/{id}',
        );
    }

    /**
     * @return array<callable|string>
     */
    public function getMiddlewareStack(): array
    {
        return [RequireAuthBearerToken::class];
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
        return ViewRequestDetails::class;
    }

    public function getUseCaseResultHandler(): string
    {
        return PSR7ResultHandler::class;
    }
}
