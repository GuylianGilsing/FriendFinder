<?php

declare(strict_types=1);

namespace FriendFinder\ProfileInformation\RequestValidators;

use Fig\Http\Message\StatusCodeInterface;
use Framework\API\Requests\Validation\AbstractRequestValidator;
use FriendFinder\ProfileInformation\Validators\CreateProfileValidator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;

final class CreateProfileRequestValidator extends AbstractRequestValidator
{
    /** @var ?array<string, mixed> */
    private ?array $validationErrorMessages = null;

    public function __construct(
        private readonly CreateProfileValidator $validator,
    ) {
    }

    public function getResponse(): ?ResponseInterface
    {
        if ($this->internalValidStateIsMarkedAsValid()) {
            return null;
        }

        if ($this->validationErrorMessages !== null) {
            $response = new Response(StatusCodeInterface::STATUS_BAD_REQUEST);

            $response->getBody()->write(
                json_encode([
                    'validationErrors' => $this->validationErrorMessages,
                ])
            );

            return $response->withAddedHeader('Content-Type', 'application/json');
        }

        return null;
    }

    protected function validate(ServerRequestInterface $request): bool
    {
        $body = is_array($request->getParsedBody()) ? $request->getParsedBody() : [];

        if (!$this->validator->isValid($body)) {
            $this->validationErrorMessages = $this->validator->getErrorMessages();

            return $this->invalidState();
        }

        return $this->validState();
    }
}
