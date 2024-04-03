<?php

declare(strict_types=1);

namespace FriendFinder\ProfileInformation\Endpoints;

use Framework\API\Endpoints\EndpointInterface;
use Framework\API\Endpoints\RoutingInformation;
use FriendFinder\Common\Middleware\RequireAuthBearerToken;
use FriendFinder\ProfileInformation\UseCases\DeleteProfile;
use FriendFinder\ProfileInformation\UseCases\DeleteProfile\DeleteProfilePSR7ArgsFormatter;
use FriendFinder\ProfileInformation\UseCases\DeleteProfile\DeleteProfilePSR7ResultHandler;

final class DeleteProfileEndpoint implements EndpointInterface
{
    public function getRoutingInformation(): RoutingInformation
    {
        return new RoutingInformation(
            methods: ['DELETE'],
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
        return null;
    }

    public function getUseCaseArgsFormatter(): ?string
    {
        return DeleteProfilePSR7ArgsFormatter::class;
    }

    public function getUseCase(): string
    {
        return DeleteProfile::class;
    }

    public function getUseCaseResultHandler(): string
    {
        return DeleteProfilePSR7ResultHandler::class;
    }
}
