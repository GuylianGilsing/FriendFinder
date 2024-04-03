<?php

declare(strict_types=1);

namespace FriendFinder\Common\Middleware;

use Exception;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Response;

use function FriendFinder\Common\Authorization\get_auth_token_content;

final class RequireAuthBearerToken
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $tokenContent = get_auth_token_content($request);
        } catch (Exception $e) {
            $this->logger->warning($e->__toString());

            return new Response(StatusCodeInterface::STATUS_UNAUTHORIZED);
        }

        if ($tokenContent === null) {
            return new Response(StatusCodeInterface::STATUS_UNAUTHORIZED);
        }

        return $handler->handle($request);
    }
}
