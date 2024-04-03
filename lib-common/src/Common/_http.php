<?php

declare(strict_types=1);

namespace FriendFinder\Common\HTTP;

use Fig\Http\Message\StatusCodeInterface;
use FriendFinder\Common\Serialization\JSONSerializableInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;

use function FriendFinder\Common\JSON\parse_array_to_json;

/**
 * Returns the HTTP protocol and the site's host name as follows: `http://example.com` or `https://example.com`.
 */
function http_protocol_and_host_name(): string
{
    return isset($_SERVER['HTTPS']) ? 'https://'.$_SERVER['HTTP_HOST'] : 'http://'.$_SERVER['HTTP_HOST'];
}

/**
 * Creates a new JSON response.
 *
 * @param int $status A HTTP status code.
 * @param string $body The response body.
 * @param array<string, mixed> $headers An associative array of key => value headers.
 */
function json_response(
    int $status = StatusCodeInterface::STATUS_OK,
    string $body = '',
    array $headers = []
): ResponseInterface {
    $response = new Response($status);

    $response->getBody()->write($body);

    foreach ($headers as $key => $value) {
        $response = $response->withHeader($key, $value);
    }

    return $response->withHeader('Content-Type', 'application/json');
}

/**
 * Creates an automated JSON object response. It will return a 404 if the object is null.
 *
 * @param int $status A HTTP status code.
 * @param ?JSONSerializableInterface $body The object that needs to be returned.
 * @param array<string, mixed> $headers An associative array of key => value headers.
 */
function json_object_response(
    int $status = StatusCodeInterface::STATUS_OK,
    ?JSONSerializableInterface $obj = null,
    array $headers = []
): ResponseInterface {
    if ($obj === null) {
        return new Response(StatusCodeInterface::STATUS_NOT_FOUND);
    }

    $body = parse_array_to_json($obj->toJSONArray());

    return json_response($status, $body, $headers);
}
