<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\UseCases\ViewReceivedRequests;

use Fig\Http\Message\StatusCodeInterface;
use Framework\API\UseCases\UseCaseResultHandlerInterface;
use FriendFinder\ContactInformation\Domain\ContactInformationRequest;
use FriendFinder\ContactInformation\Serializers\ContactInformationRequest\SimpleContactInformationRequestSerializer;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Response;

use function FriendFinder\Common\HTTP\json_response;
use function FriendFinder\Common\JSON\parse_array_to_json;

final class PSR7ResultHandler implements UseCaseResultHandlerInterface
{
    public function __construct(
        private readonly SimpleContactInformationRequestSerializer $serializer,
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
                    body: $this->transformToResponseBody($result->receivedRequests, $result->identity),
                );
                break;

            case ResultMessage::ARGS_ERROR:
                $this->logger->warning('(ViewReceivedRequests) args error caught', $result->argumentErrors);
                $response = new Response(StatusCodeInterface::STATUS_BAD_REQUEST);
                break;
        }

        return $response;
    }

    /**
     * @param array<ContactInformationRequest> $receivedRequestExchanges
     */
    private function transformToResponseBody(array $receivedRequestExchanges, string $identity): string
    {
        $body = [];

        /** @var ContactInformationRequest $receivedRequestExchange */
        foreach ($receivedRequestExchanges as $receivedRequestExchange) {
            $body[] = $this->serializer->serialize($receivedRequestExchange, $identity);
        }

        return parse_array_to_json($body);
    }
}
