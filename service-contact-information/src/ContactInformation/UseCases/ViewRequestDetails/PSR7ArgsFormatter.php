<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\UseCases\ViewRequestDetails;

use Framework\API\UseCases\UseCaseArgsFormatterInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;

use function FriendFinder\Common\Authorization\get_auth_token_content;

final class PSR7ArgsFormatter implements UseCaseArgsFormatterInterface
{
    /**
     * @param ServerRequestInterface $input
     *
     * @return array<string, mixed>
     */
    public function format(mixed $input): array
    {
        $routeContext = RouteContext::fromRequest($input);
        $route = $routeContext->getRoute();
        $routeArguments = $route->getArguments();

        $authTokenContent = get_auth_token_content($input);

        return [
            'requestID' => array_key_exists('id', $routeArguments) ? $routeArguments['id'] : null,
            'identity' => $authTokenContent?->identity,
        ];
    }
}
