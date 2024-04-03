<?php

declare(strict_types=1);

namespace FriendFinder\Common\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

use function FriendFinder\Common\URLs\request_url_is_standardized;
use function FriendFinder\Common\URLs\standardize_request_url;

final class StandardizeURLMiddleware
{
    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (request_url_is_standardized($request)) {
            return $handler->handle($request);
        }

        return (new Response(301))->withHeader('Location', standardize_request_url($request));
    }
}
