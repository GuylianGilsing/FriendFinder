<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\UseCases\AnswerRequest;

use Fig\Http\Message\StatusCodeInterface;
use Framework\API\UseCases\UseCaseResultHandlerInterface;
use FriendFinder\ContactInformation\Serializers\ContactInformationRequestAnswer\DetailedContactInformationRequestAnswerSerializer;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;

use function FriendFinder\Common\HTTP\json_response;
use function FriendFinder\Common\JSON\parse_array_to_json;

final class PSR7ResultHandler implements UseCaseResultHandlerInterface
{
    public function __construct(
        private readonly DetailedContactInformationRequestAnswerSerializer $serializer,
    ) {
    }

    /**
     * @param Result $result
     *
     * @return ResponseInterface
     */
    public function handle(object $result): mixed
    {
        $response = new Response(StatusCodeInterface::STATUS_NOT_IMPLEMENTED);

        switch ($result->message) {
            case ResultMessage::SUCCEEDED:
                $response = json_response(
                    status: StatusCodeInterface::STATUS_CREATED,
                    body: parse_array_to_json($this->serializer->serialize($result->answer)),
                );
                break;

            case ResultMessage::NOT_FOUND:
                $response = new Response(StatusCodeInterface::STATUS_NOT_FOUND);
                break;

            case ResultMessage::NOT_AUTHORIZED:
                $response = new Response(StatusCodeInterface::STATUS_UNAUTHORIZED);
                break;

            case ResultMessage::ARGS_ERROR:
                $response = json_response(
                    status: StatusCodeInterface::STATUS_BAD_REQUEST,
                    body: parse_array_to_json($result->argumentErrors),
                );
                break;

            case ResultMessage::FAILED:
                $response = json_response(
                    status: StatusCodeInterface::STATUS_BAD_REQUEST,
                    body: parse_array_to_json($result->errors),
                );
                break;
        }

        return $response;
    }
}
