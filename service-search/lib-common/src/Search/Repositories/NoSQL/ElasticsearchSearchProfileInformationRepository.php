<?php

declare(strict_types=1);

namespace FriendFinder\Search\Repositories\NoSQL;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Response\Elasticsearch;
use Exception;
use FriendFinder\Search\Repositories\SearchProfileInformationRepositoryInterface;
use Psr\Log\LoggerInterface;

final class ElasticsearchSearchProfileInformationRepository implements SearchProfileInformationRepositoryInterface
{
    private const ELASTIC_INDEX = 'profile-information';

    public function __construct(
        private readonly Client $elasticsearch,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * @return array<string, mixed> The persisted profile information.
     */
    public function getByIdentity(string $identity): ?array
    {
        $this->createIndexOfNotExists();

        try {
            $response = $this->elasticsearch->get(['index' => self::ELASTIC_INDEX, 'id' => $identity]);
        } catch (Exception $e) {
            // Is triggered by a 4xx error, do not log anything
            if ($e instanceof ClientResponseException) {
                return null;
            }

            // Log any other errors
            $this->logger->error('(ElasticsearchProfileInformationRepository) -> get:', [
                'message' => $e->getMessage(),
            ]);

            return null;
        }

        $content = null;

        try {
            $content = $response->asArray();
        } catch (Exception $e) {
            $content = null;
            $this->logger->error('(ElasticsearchProfileInformationRepository) -> get:', [
                'message' => $e->getMessage(),
            ]);
        }

        return $content;
    }

    /**
     * @param array<string, mixed> $elasticQuery
     *
     * @return array<array<string, mixed>>
     */
    public function search(array $elasticQuery): array
    {
        $this->createIndexOfNotExists();

        try {
            $response = $this->elasticsearch->search([
                'index' => self::ELASTIC_INDEX,
                'body' => [
                    'query' => $elasticQuery,
                ],
            ]);
        } catch (Exception $e) {
            // Is triggered by a 4xx error, do not log anything
            if ($e instanceof ClientResponseException) {
                return [];
            }

            // Log any other errors
            $this->logger->error('(ElasticsearchProfileInformationRepository) -> get:', [
                'message' => $e->getMessage(),
            ]);

            return [];
        }

        return $this->getContentFromSearchResult($response);
    }

    /**
     * @param array<string, mixed> $profileData The profile data from a profile information event.
     */
    public function insert(array $profileData): bool
    {
        $this->createIndexOfNotExists();

        $response = $this->elasticsearch->index([
            'index' => self::ELASTIC_INDEX,
            'id' => $profileData['identity'],
            'body' => $profileData,
        ]);

        $indexed = false;

        try {
            $response->asArray();
            $indexed = true;
        } catch (Exception $e) {
            $indexed = false;
            $this->logger->error('(ElasticsearchProfileInformationRepository) -> insert:', [
                'message' => $e->getMessage(),
            ]);
        }

        return $indexed;
    }

    /**
     * @param array<string, mixed> $profileData The profile data from a profile information event.
     */
    public function update(array $profileData): bool
    {
        $this->createIndexOfNotExists();

        $response = $this->elasticsearch->update([
            'index' => self::ELASTIC_INDEX,
            'id' => $profileData['identity'],
            'body' => $profileData,
        ]);

        $updated = false;

        try {
            $response->asArray();
            $updated = true;
        } catch (Exception $e) {
            $updated = false;
            $this->logger->error('(ElasticsearchProfileInformationRepository) -> update:', [
                'message' => $e->getMessage(),
            ]);
        }

        return $updated;
    }

    public function delete(string $identity): bool
    {
        if ($this->getByIdentity($identity) === null) {
            return false;
        }

        $this->createIndexOfNotExists();

        $response = $this->elasticsearch->delete([
            'index' => self::ELASTIC_INDEX,
            'id' => $identity,
        ]);

        $deleted = false;

        try {
            $response->asArray();
            $deleted = true;
        } catch (Exception $e) {
            $deleted = false;
            $this->logger->error('(ElasticsearchProfileInformationRepository) -> delete:', [
                'message' => $e->getMessage(),
            ]);
        }

        return $deleted;
    }

    private function createIndexOfNotExists(): void
    {
        if (!$this->elasticsearch->indices()->exists(['index' => self::ELASTIC_INDEX])) {
            $this->elasticsearch->indices()->create(['index' => self::ELASTIC_INDEX]);
        }
    }

    /**
     * @return array<array<string, mixed>>
     */
    private function getContentFromSearchResult(Elasticsearch $response): array
    {
        $content = [];

        try {
            $content = $response->asArray();

            if (!array_key_exists('hits', $content) || !is_array($content['hits'])) {
                return [];
            }

            if (!array_key_exists('hits', $content['hits']) || !is_array($content['hits']['hits'])) {
                return [];
            }

            $profiles = [];

            foreach ($content['hits']['hits'] as $hit) {
                if (!array_key_exists('_source', $hit) || !is_array($hit['_source'])) {
                    continue;
                }

                $profiles[] = $hit['_source'];
            }

            $content = $profiles;
        } catch (Exception $e) {
            $content = [];
            $this->logger->error('(ElasticsearchProfileInformationRepository) -> get:', [
                'message' => $e->getMessage(),
            ]);
        }

        return $content;
    }
}
