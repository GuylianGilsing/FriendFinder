<?php

declare(strict_types=1);

namespace FriendFinder\ProfileInformation\UseCases\DeleteProfile;

use Fig\Http\Message\StatusCodeInterface;
use Framework\API\UseCases\UseCaseResultHandlerInterface;
use Psr\Http\Message\ResponseInterface;

use function FriendFinder\Common\HTTP\json_response;

final class DeleteProfilePSR7ResultHandler implements UseCaseResultHandlerInterface
{
    /**
     * @param CreateProfileResult $result
     *
     * @return ResponseInterface
     */
    public function handle(object $result): mixed
    {
        $status = StatusCodeInterface::STATUS_OK;

        switch ($result->message) {
            case DeleteProfileResultMessage::SUCCEEDED:
                $status = StatusCodeInterface::STATUS_NO_CONTENT;
                break;

            case DeleteProfileResultMessage::NOT_FOUND:
                $status = StatusCodeInterface::STATUS_NOT_FOUND;
                break;
        }

        return json_response($status);
    }
}
