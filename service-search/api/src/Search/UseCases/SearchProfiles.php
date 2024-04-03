<?php

declare(strict_types=1);

namespace FriendFinder\Search\UseCases;

use Framework\API\UseCases\UseCaseInterface;
use FriendFinder\Search\Repositories\SearchProfileInformationRepositoryInterface;
use FriendFinder\Search\UseCases\SearchProfiles\ArgsValidator;
use FriendFinder\Search\UseCases\SearchProfiles\Result;
use FriendFinder\Search\UseCases\SearchProfiles\ResultMessage;
use Psr\Log\LoggerInterface;

final class SearchProfiles implements UseCaseInterface
{
    public function __construct(
        private readonly ArgsValidator $argsValidator,
        private readonly SearchProfileInformationRepositoryInterface $searchProfileInformationRepository,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * @param array<string, mixed> $args
     *
     * @return Result
     */
    public function invoke(array $args = []): object
    {
        if (!$this->argsValidator->isValid($args)) {
            return new Result(
                message: ResultMessage::ARGS_ERROR,
                argumentErrors: $this->argsValidator->getErrorMessages()
            );
        }

        $query = [
            'bool' => [
                'must_not' => [
                    [
                        'match' => [
                            'identity' => ['query' => $args['identity']],
                        ],
                    ],
                ],
            ],
        ];

        $profileInformation = $this->searchProfileInformationRepository->search($query);

        return new Result(message: ResultMessage::SUCCEEDED, data: $profileInformation);
    }
}
