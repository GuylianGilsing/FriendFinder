<?php

declare(strict_types=1);

namespace Tests\Feature;

use ErrorException;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

use function FriendFinder\Common\URLs\standardize_request_url;

final class StandardizeRequestURLTest extends TestCase
{
    public function testExceptionIsThrownWhenStandardizedURLIsPassed(): void
    {
        // Arrange
        $request = $this->getRequestStub('/standardized/url');

        // Assert
        $this->expectException(ErrorException::class);

        // Act
        standardize_request_url($request);
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
