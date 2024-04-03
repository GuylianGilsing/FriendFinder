<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\UseCases\ViewReceivedRequests;

use Framework\API\UseCases\UseCaseArgsFormatterInterface;
use Psr\Http\Message\ServerRequestInterface;

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
        $authTokenContent = get_auth_token_content($input);

        return [
            'identity' => $authTokenContent?->identity,
        ];
    }
}
