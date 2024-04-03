<?php

declare(strict_types=1);

namespace Tests\Unit\URLs;

use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

use function FriendFinder\Common\URLs\request_url_is_standardized;

/**
 * Old PHP Unit tests that I currently do not want to convert to Pest tests.
 */
final class RequestURLIsStandardizedTest extends TestCase
{
    public function testIfCorrectURLIsNormalized(): void
    {
        // Arrange
        $request = $this->getRequestStub('/correct/test/url');
        // Act
        $isNormalized = request_url_is_standardized($request);

        // Assert
        $this->assertTrue($isNormalized);
    }

    public function testIfIncorrectURLIsNotNormalized(): void
    {
        // Arrange
        $request = $this->getRequestStub('///incorrect//request////url///////////');

        // Act
        $isNormalized = request_url_is_standardized($request);

        // Assert
        $this->assertFalse($isNormalized);
    }

    private function getRequestStub(string $pathValue, string $queryValue = ''): ServerRequestInterface
    {
        $stub = $this->createStub(ServerRequestInterface::class);

        if ($stub instanceof Stub) {
            $stub->method('getUri')->willReturn($this->getRequestURIStub($pathValue, $queryValue));
        }

        return $stub;
    }

    private function getRequestURIStub(string $pathValue, string $queryValue = ''): UriInterface
    {
        $stub = $this->createStub(UriInterface::class);

        if ($stub instanceof Stub) {
            $stub->method('getPath')->willReturn($pathValue);
            $stub->method('getQuery')->willReturn($queryValue);
        }

        $stub->getPath();

        return $stub;
    }
}
