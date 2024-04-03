<?php

declare(strict_types=1);

namespace FriendFinder\ProfileInformation\UseCases\ViewProfile;

use Fig\Http\Message\StatusCodeInterface;
use Framework\API\UseCases\UseCaseResultHandlerInterface;
use FriendFinder\ProfileInformation\Serializers\Profile\DetailedProfileSerializer;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;

use function FriendFinder\Common\HTTP\json_response;
use function FriendFinder\Common\JSON\parse_array_to_json;

final class ViewProfilePSR7ResultHandler implements UseCaseResultHandlerInterface
{
    public function __construct(
        private readonly DetailedProfileSerializer $detailedProfileSerializer,
    ) {
    }

    /**
     * @param ViewProfileResult $result
     *
     * @return ResponseInterface
     */
    public function handle(object $result): mixed
    {
        if ($result->profile === null) {
            return new Response(StatusCodeInterface::STATUS_NOT_FOUND);
        }

        $serializedProfile = $this->detailedProfileSerializer->serialize($result->profile);

        return json_response(body: parse_array_to_json($serializedProfile));
    }
}
