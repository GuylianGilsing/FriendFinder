<?php

declare(strict_types=1);

namespace FriendFinder\ProfileInformation\Endpoints;

use Framework\API\Endpoints\EndpointInterface;
use Framework\API\Endpoints\RoutingInformation;
use FriendFinder\Common\Middleware\RequireAuthBearerToken;
use FriendFinder\ProfileInformation\RequestValidators\ViewProfileRequestValidator;
use FriendFinder\ProfileInformation\UseCases\ViewProfile;
use FriendFinder\ProfileInformation\UseCases\ViewProfile\ViewProfilePSR7ArgsFormatter;
use FriendFinder\ProfileInformation\UseCases\ViewProfile\ViewProfilePSR7ResultHandler;

final class ViewProfileEndpoint implements EndpointInterface
{
    public function getRoutingInformation(): RoutingInformation
    {
        return new RoutingInformation(
            methods: ['GET'],
            path: '/profile-information/{identity}',
        );
    }

    /**
     * @return array<callable|string>
     */
    public function getMiddlewareStack(): array
    {
        return [
            RequireAuthBearerToken::class,
        ];
    }

    public function getRequestValidator(): ?string
    {
        return ViewProfileRequestValidator::class;
    }

    public function getUseCaseArgsFormatter(): ?string
    {
        return ViewProfilePSR7ArgsFormatter::class;
    }

    public function getUseCase(): string
    {
        return ViewProfile::class;
    }

    public function getUseCaseResultHandler(): string
    {
        return ViewProfilePSR7ResultHandler::class;
    }
}
