<?php

declare(strict_types=1);

namespace FriendFinder\Search\UseCases\SearchProfiles;

use Fig\Http\Message\StatusCodeInterface;
use Framework\API\UseCases\UseCaseResultHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;

use function FriendFinder\Common\HTTP\json_response;
use function FriendFinder\Common\JSON\parse_array_to_json;

final class PSR7ResultsHandler implements UseCaseResultHandlerInterface
{
    /**
     * @param Result $result
     *
     * @return ResponseInterface
     */
    public function handle(object $result): mixed
    {
        $response = new Response(StatusCodeInterface::STATUS_NO_CONTENT);

        switch($result->message) {
            case ResultMessage::SUCCEEDED:
                $response = json_response(
                    status: StatusCodeInterface::STATUS_OK,
                    body: parse_array_to_json($result->data)
                );
                break;

            case ResultMessage::ARGS_ERROR:
                $response = json_response(
                    status: StatusCodeInterface::STATUS_BAD_REQUEST,
                    body: parse_array_to_json($result->argumentErrors)
                );
                break;

            case ResultMessage::FAILED:
                $response = new Response(StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR);
                $response->getBody()->write('Could not search for profile data, please try again later');
                break;
        }

        return $response;
    }
}
