<?php

declare(strict_types=1);

namespace FriendFinder\ProfileInformation\UseCases\ViewProfile;

use Framework\API\UseCases\UseCaseArgsFormatterInterface;
use FriendFinder\ProfileInformation\Validators\ViewProfileValidator;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;

final class ViewProfilePSR7ArgsFormatter implements UseCaseArgsFormatterInterface
{
    public function __construct(
        private readonly ViewProfileValidator $validator,
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
