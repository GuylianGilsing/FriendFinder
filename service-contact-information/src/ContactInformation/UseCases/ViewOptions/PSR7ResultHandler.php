<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\UseCases\ViewOptions;

use Fig\Http\Message\StatusCodeInterface;
use Framework\API\UseCases\UseCaseResultHandlerInterface;
use Psr\Http\Message\ResponseInterface;

use function FriendFinder\Common\HTTP\json_response;
use function FriendFinder\Common\JSON\parse_array_to_json;

final class PSR7ResultHandler implements UseCaseResultHandlerInterface
{
    /**
     * @param Result $result
     *
     * @return ResponseInterface
     */
    public function handle(object $result): mixed
    {
        return json_response(
            status: StatusCodeInterface::STATUS_OK,
            body: parse_array_to_json($result->data),
        );
    }
}
