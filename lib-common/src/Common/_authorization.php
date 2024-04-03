<?php

declare(strict_types=1);

namespace FriendFinder\Common\Authorization;

use Psr\Http\Message\ServerRequestInterface;

use function FriendFinder\Common\JSON\parse_json_string_to_array;

function extract_auth_token_from_request(ServerRequestInterface $request): ?string
{
    if (!$request->hasHeader('Authorization')) {
        return null;
    }

    $tokenPrefix = 'Bearer ';
    $token = $request->getHeader('Authorization')[0];

    // Make sure the header includes the proper prefix
    if (substr($token, 0, strlen($tokenPrefix)) !== $tokenPrefix) {
        return null;
    }

    return substr($token, strlen($tokenPrefix), strlen($token));
}

/**
 * @throws ErrorException when an invalid identity value is found.
 */
function get_auth_token_content(ServerRequestInterface $request): ?AuthTokenContent
{
    $token = extract_auth_token_from_request($request);

    if ($token === null) {
        return null;
    }

    $tokenParts = explode('.', $token);

    if (count($tokenParts) !== 3) {
        return null;
    }

    $jwtPayload = parse_json_string_to_array(base64_decode($tokenParts[1]));

    if (!is_array($jwtPayload)) {
        return null;
    }

    return new AuthTokenContent(
        identity: $jwtPayload['sub'] ?? '',
    );
}
