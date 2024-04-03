<?php

declare(strict_types=1);

namespace FriendFinder\ProfileInformation\UseCases\DeleteProfile;

use Framework\API\UseCases\UseCaseArgsFormatterInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;

final class DeleteProfilePSR7ArgsFormatter implements UseCaseArgsFormatterInterface
{
    public function __construct(
        private readonly DeleteProfilePSR7ArgsValidator $validator,
    ) {
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return array<string, mixed>
     */
    public function format(mixed $input): array
    {
        $routeContext = RouteContext::fromRequest($input);
        $route = $routeContext->getRoute();
        $routeArguments = $route->getArguments();

        if (!$this->validator->isValid($routeArguments)) {
            return [];
        }

        return [
            'identity' => $routeArguments['identity'],
        ];
    }
}
