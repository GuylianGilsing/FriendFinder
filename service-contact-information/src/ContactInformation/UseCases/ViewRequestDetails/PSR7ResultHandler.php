<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\UseCases\ViewRequestDetails;

use Fig\Http\Message\StatusCodeInterface;
use Framework\API\UseCases\UseCaseResultHandlerInterface;
use FriendFinder\ContactInformation\Domain\ContactInformationRequest;
use FriendFinder\ContactInformation\Serializers\ContactInformationRequest\DetailedContactInformationRequestSerializer;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Response;

use function FriendFinder\Common\HTTP\json_response;
use function FriendFinder\Common\JSON\parse_array_to_json;

final class PSR7ResultHandler implements UseCaseResultHandlerInterface
{
    public function __construct(
        private readonly DetailedContactInformationRequestSerializer $serializer,
        private readonly LoggerInterface $logger,
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
            case ResultMessage::SUCCESS:
                $response = json_response(
                    status: StatusCodeInterface::STATUS_OK,
                    body: $this->transformToResponseBody($result->request, $result->identity),
                );
                break;

            case ResultMessage::ARGS_ERROR:
                $this->logger->warning('(ViewRequestAnswer) args error caught', $result->argumentErrors);
                $response = new Response(StatusCodeInterface::STATUS_BAD_REQUEST);
                break;

            case ResultMessage::NOT_FOUND:
                $response = new Response(StatusCodeInterface::STATUS_NOT_FOUND);
                break;

            case ResultMessage::NOT_AUTHORIZED:
                $response = new Response(StatusCodeInterface::STATUS_UNAUTHORIZED);
                break;
        }

        return $response;
    }

    private function transformToResponseBody(ContactInformationRequest $request, string $identity): string
    {
        return parse_array_to_json($this->serializer->serialize($request, $identity));
    }
}
