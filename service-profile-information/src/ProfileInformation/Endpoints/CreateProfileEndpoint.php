<?php

declare(strict_types=1);

namespace FriendFinder\ProfileInformation\Endpoints;

use Framework\API\Endpoints\EndpointInterface;
use Framework\API\Endpoints\RoutingInformation;
use FriendFinder\Common\Middleware\RequireAuthBearerToken;
use FriendFinder\ProfileInformation\RequestValidators\CreateProfileRequestValidator;
use FriendFinder\ProfileInformation\UseCases\CreateProfile;
use FriendFinder\ProfileInformation\UseCases\CreateProfile\CreateProfilePSR7ArgsFormatter;
use FriendFinder\ProfileInformation\UseCases\CreateProfile\CreateProfilePSR7ResultHandler;

final class CreateProfileEndpoint implements EndpointInterface
{
    public function getRoutingInformation(): RoutingInformation
    {
        return new RoutingInformation(
            methods: ['POST'],
            path: '/profile-information',
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
        return CreateProfileRequestValidator::class;
    }

    public function getUseCaseArgsFormatter(): ?string
    {
        return CreateProfilePSR7ArgsFormatter::class;
    }

    public function getUseCase(): string
    {
        return CreateProfile::class;
    }

    public function getUseCaseResultHandler(): string
    {
        return CreateProfilePSR7ResultHandler::class;
    }
}
