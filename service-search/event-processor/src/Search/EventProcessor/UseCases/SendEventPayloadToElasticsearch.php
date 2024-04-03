<?php

declare(strict_types=1);

namespace FriendFinder\Search\EventProcessor\UseCases;

use Framework\API\UseCases\UseCaseInterface;
use FriendFinder\Common\Events\ProfileInformation\ProfileInformationUpdatedEventType;
use FriendFinder\Search\EventProcessor\UseCases\SendEventPayloadToElasticsearch\ArgsValidator;
use FriendFinder\Search\EventProcessor\UseCases\SendEventPayloadToElasticsearch\Result;
use FriendFinder\Search\EventProcessor\UseCases\SendEventPayloadToElasticsearch\ResultMessage;
use FriendFinder\Search\Repositories\SearchProfileInformationRepositoryInterface;

final class SendEventPayloadToElasticsearch implements UseCaseInterface
{
    public function __construct(
        private ArgsValidator $argsValidator,
        private SearchProfileInformationRepositoryInterface $profileInformationRepository,
    ) {
    }

    /**
     * @param array<string, mixed> $args
     */
    public function invoke(array $args = []): object
    {
        if (!$this->argsValidator->isValid($args)) {
            return new Result(
                message: ResultMessage::ARGS_ERROR,
                argumentErrors: $this->argsValidator->getErrorMessages(),
            );
        }

        return $this->process($args);
    }

    /**
     * @param array<string, mixed> $args
     */
    private function process(array $args): Result
    {
        $existingProfileInformation = $this->profileInformationRepository->getByIdentity($args['data']['identity']);
        $result = new Result(ResultMessage::FAILED);

        switch ($args['type']) {
            case ProfileInformationUpdatedEventType::UPDATED->value:
                $succeeded = false;

                if ($existingProfileInformation === null) {
                    $succeeded = $this->profileInformationRepository->insert($args['data']);
                } else {
                    $succeeded = $this->profileInformationRepository->update($args['data']);
                }

                $result = $succeeded ? new Result(ResultMessage::SUCCEEDED) : new Result(ResultMessage::FAILED);
                break;

            case ProfileInformationUpdatedEventType::DELETED->value:
                $succeeded = false;

                if ($existingProfileInformation === null) {
                    $succeeded = true;
                } else {
                    $succeeded = $this->profileInformationRepository->delete($args['data']['identity']);
                }

                $result = $succeeded ? new Result(ResultMessage::SUCCEEDED) : new Result(ResultMessage::FAILED);
                break;
        }

        return $result;
    }
}
