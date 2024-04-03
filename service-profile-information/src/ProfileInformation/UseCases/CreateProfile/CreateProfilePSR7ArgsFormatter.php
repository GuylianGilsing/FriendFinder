<?php

declare(strict_types=1);

namespace FriendFinder\ProfileInformation\UseCases\CreateProfile;

use Framework\API\UseCases\UseCaseArgsFormatterInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

use function FriendFinder\Common\Authorization\get_auth_token_content;

final class CreateProfilePSR7ArgsFormatter implements UseCaseArgsFormatterInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * @param ServerRequestInterface $input
     *
     * @return array<string, mixed>
     */
    public function format(mixed $input): array
    {
        $requestBody = is_array($input->getParsedBody()) ? $input->getParsedBody() : [];
        $authTokenContent = get_auth_token_content($input);

        return [
            'requestBody' => $requestBody,
            'identity' => $authTokenContent->identity,
        ];
    }
}
