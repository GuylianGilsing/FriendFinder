<?php

declare(strict_types=1);

namespace FriendFinder\ProfileInformation\RequestValidators;

use Fig\Http\Message\StatusCodeInterface;
use Framework\API\Requests\Validation\AbstractRequestValidator;
use FriendFinder\ProfileInformation\Validators\ViewProfileValidator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

final class ViewProfileRequestValidator extends AbstractRequestValidator
{
    public function __construct(
        private readonly ViewProfileValidator $validator,
    ) {
    }

    public function getResponse(): ?ResponseInterface
    {
        if ($this->internalValidStateIsMarkedAsValid()) {
            return null;
        }

        return new Response(StatusCodeInterface::STATUS_NOT_FOUND);
    }

    protected function validate(ServerRequestInterface $request): bool
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        $routeArguments = $route->getArguments();

        if (!$this->validator->isValid($routeArguments)) {
            return $this->invalidState();
        }

        return $this->validState();
    }
}
