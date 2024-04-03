<?php

declare(strict_types=1);

namespace Tests\Unit\URLs;

use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

use function FriendFinder\Common\URLs\standardize_request_url;

/**
 * Old PHP Unit tests that I currently do not want to convert to Pest tests.
 */
final class StandardizeRequestURLTest extends TestCase
{
    public function testRequestURLWithRepeatingSlashesIsStandardizedProperly(): void
    {
        // Arrange
        $request = $this->getRequestStub('/////incorrect///request/////url',);

        // Act
        $standardizedURL = standardize_request_url($request);

        // Assert
        $this->assertEquals('/incorrect/request/url', $standardizedURL);
    }

    public function testRequestURLWithTrailingSlashesIsStandardizedProperly(): void
    {
        // Arrange
        $request = $this->getRequestStub('/incorrect/request/url///');

        // Act
        $standardizedURL = standardize_request_url($request);

        // Assert
        $this->assertEquals('/incorrect/request/url', $standardizedURL);
    }

    public function testIncorrectRequestURLWithLeavesQueryStringIntactAfterStandardization(): void
    {
        // Arrange
        $request = $this->getRequestStub('/////incorrect///request/////url?test=12',);

        // Act
        $standardizedURL = standardize_request_url($request);

        // Assert
        $this->assertEquals('/incorrect/request/url?test=12', $standardizedURL);
    }

    public function testIncorrectRequestURLWithLeavesHashIntactAfterStandardization(): void
    {
        // Arrange
        $request = $this->getRequestStub('/////incorrect///request/////url#some-heading',);

        // Act
        $standardizedURL = standardize_request_url($request);

        // Assert
        $this->assertEquals('/incorrect/request/url#some-heading', $standardizedURL);
    }

    public function testIncorrectRequestURLWithLeavesQueryStringAndHashIntactAfterStandardization(): void
    {
        // Arrange
        $request = $this->getRequestStub('/////incorrect///request/////url#some-heading?test=12',);

        // Act
        $standardizedURL = standardize_request_url($request);

        // Assert
        $this->assertEquals('/incorrect/request/url#some-heading?test=12', $standardizedURL);
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
