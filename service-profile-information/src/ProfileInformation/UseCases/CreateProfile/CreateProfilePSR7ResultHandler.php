<?php

declare(strict_types=1);

namespace FriendFinder\ProfileInformation\UseCases\CreateProfile;

use Fig\Http\Message\StatusCodeInterface;
use Framework\API\UseCases\UseCaseResultHandlerInterface;
use FriendFinder\ProfileInformation\Serializers\Profile\DetailedProfileSerializer;
use Psr\Http\Message\ResponseInterface;

use function FriendFinder\Common\HTTP\json_response;
use function FriendFinder\Common\JSON\parse_array_to_json;

final class CreateProfilePSR7ResultHandler implements UseCaseResultHandlerInterface
{
    public function __construct(
        private readonly DetailedProfileSerializer $detailedProfileSerializer,
    ) {
    }

    /**
     * @param CreateProfileResult $result
     *
     * @return ResponseInterface
     */
    public function handle(object $result): mixed
    {
        $status = StatusCodeInterface::STATUS_OK;
        $body = [];

        switch ($result->message) {
            case CreateProfileResultMessage::ARGS_ERROR:
                $status = StatusCodeInterface::STATUS_BAD_REQUEST;
                $body = ['argumentErrors' => $result->argumentErrors];
                break;

            case CreateProfileResultMessage::VALIDATION_ERROR:
                $status = StatusCodeInterface::STATUS_BAD_REQUEST;
                $body = ['validationErrors' => $result->validationErrors];
                break;

            case CreateProfileResultMessage::SUCCEEDED:
                $body = $this->detailedProfileSerializer->serialize($result->profile);
                break;
        }

        return json_response($status, parse_array_to_json($body));
    }
}
