<?php

declare(strict_types=1);

namespace Tests\Unit\Authorization;

use Mockery;
use Mockery\MockInterface;
use Psr\Http\Message\ServerRequestInterface;

use function FriendFinder\Common\Authorization\extract_auth_token_from_request;

describe('Failure cases', function () {
    it('should return nothing when the "Authorization" header is not set', function () {
        // Arrange
        $request = Mockery::mock(ServerRequestInterface::class);

        if ($request instanceof MockInterface) {
            $request->expects('hasHeader')->with('Authorization')->andReturn(false);
        }

        // Act
        $extractedToken = extract_auth_token_from_request($request);

        // Assert
        expect($extractedToken)->toBeNull();
    });

    it('should return nothing when the "Authorization" header prefix is not set', function () {
        // Arrange
        $request = Mockery::mock(ServerRequestInterface::class);

        if ($request instanceof MockInterface) {
            $request->expects('hasHeader')->with('Authorization')->andReturn(true);
            $request->expects('getHeader')->with('Authorization')->andReturn(['token with missing prefix']);
        }

        // Act
        $extractedToken = extract_auth_token_from_request($request);

        // Assert
        expect($extractedToken)->toBeNull();
    });
});

test('Can extract token', function () {
    // Arrange
    $token = 'mock-jwt';
    $tokenPrefix = 'Bearer ';
    $request = Mockery::mock(ServerRequestInterface::class);

    if ($request instanceof MockInterface) {
        $request->expects('hasHeader')->with('Authorization')->andReturn(true);
        $request->expects('getHeader')->with('Authorization')->andReturn([$tokenPrefix.$token]);
    }

    // Act
    $extractedToken = extract_auth_token_from_request($request);

    // Assert
    expect($extractedToken)->toBe($token);
});
