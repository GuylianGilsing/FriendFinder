<?php

declare(strict_types=1);

namespace Tests\Unit\Authorization;

use ErrorException;
use FriendFinder\Common\Authorization\AuthTokenContent;
use Mockery;
use Mockery\MockInterface;
use Psr\Http\Message\ServerRequestInterface;

use function FriendFinder\Common\Authorization\get_auth_token_content;

describe('Failure cases', function () {
    it('should return nothing when the "Authorization" header is not set', function () {
        // Arrange
        $request = Mockery::mock(ServerRequestInterface::class);

        if ($request instanceof MockInterface) {
            $request->expects('hasHeader')->with('Authorization')->andReturn(false);
        }

        // Act
        $extractedToken = get_auth_token_content($request);

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
        $extractedToken = get_auth_token_content($request);

        // Assert
        expect($extractedToken)->toBeNull();
    });

    it('should return nothing when the token does not contain a valid JWT structure', function () {
        // Arrange
        $token = 'mock-jwt';
        $tokenPrefix = 'Bearer ';
        $request = Mockery::mock(ServerRequestInterface::class);

        if ($request instanceof MockInterface) {
            $request->expects('hasHeader')->with('Authorization')->andReturn(true);
            $request->expects('getHeader')->with('Authorization')->andReturn([$tokenPrefix.$token]);
        }

        // Act
        $extractedToken = get_auth_token_content($request);

        // Assert
        expect($extractedToken)->toBeNull();
    });

    it('should return nothing when the token does not contain a valid b64 encoded JSON payload', function () {
        // Arrange
        $token = 'headers.payload.signature';
        $tokenPrefix = 'Bearer ';
        $request = Mockery::mock(ServerRequestInterface::class);

        if ($request instanceof MockInterface) {
            $request->expects('hasHeader')->with('Authorization')->andReturn(true);
            $request->expects('getHeader')->with('Authorization')->andReturn([$tokenPrefix.$token]);
        }

        // Act
        $extractedToken = get_auth_token_content($request);

        // Assert
        expect($extractedToken)->toBeNull();
    });
});

describe('"sub" exceptions', function () {
    it('should throw exception when no sub field exists within JWT payload', function () {
        // Arrange
        $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.e30.Et9HFtf9R3GEMA0IICOfFMVXY7kkTX1wr4qCyhIf58U';
        $tokenPrefix = 'Bearer ';
        $request = Mockery::mock(ServerRequestInterface::class);

        if ($request instanceof MockInterface) {
            $request->expects('hasHeader')->with('Authorization')->andReturn(true);
            $request->expects('getHeader')->with('Authorization')->andReturn([$tokenPrefix.$token]);
        }

        $expectedExceptionMessage = 'Token identity is not valid';

        // Act
        $action = fn () => get_auth_token_content($request);

        // Assert
        expect($action)->toThrow(ErrorException::class, $expectedExceptionMessage);
    });

    it('should throw exception when an invalid UUID is used', function () {
        // Arrange
        $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiJzb21lLXZhbHVlIn0.CF-tn-8hoPY6uF3LxVKU7Qg285CQ614HB8F7Cj7l8c0';
        $tokenPrefix = 'Bearer ';
        $request = Mockery::mock(ServerRequestInterface::class);

        if ($request instanceof MockInterface) {
            $request->expects('hasHeader')->with('Authorization')->andReturn(true);
            $request->expects('getHeader')->with('Authorization')->andReturn([$tokenPrefix.$token]);
        }

        $expectedExceptionMessage = 'Token identity is not a valid UUID';

        // Act
        $action = fn () => get_auth_token_content($request);

        // Assert
        expect($action)->toThrow(ErrorException::class, $expectedExceptionMessage);
    });
});

test('Can get token content', function () {
    // Arrange
    $token = 'eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICI0UkV2Wldhd2x5Z01GN1E0VU9MQkNKeEZEaVVMMVVOYS0yZFQ5M3hXcThRIn0.eyJleHAiOjE3MDEzNDc4MDksImlhdCI6MTcwMTM0NzUwOSwianRpIjoiODdjY2I4MGMtOWQ2Yy00MDA1LWFjMjgtOGZkNDdlODkwM2E3IiwiaXNzIjoiaHR0cHM6Ly8xMjcuMC4wLjE6ODEwMi9yZWFsbXMvZnJpZW5kLWZpbmRlciIsInN1YiI6ImNlMGU0OTY0LWNkNzUtNDFmOS04ZWYyLTdiYjhkMzk5NDU3MyIsInR5cCI6IkJlYXJlciIsImF6cCI6ImFkbWluLWNsaSIsInNlc3Npb25fc3RhdGUiOiJmNGQ0NDM1NS03M2Q3LTQxN2MtYTFmMS1mYmFkNjI0NjY3MWYiLCJhY3IiOiIxIiwic2NvcGUiOiJwcm9maWxlIGVtYWlsIiwic2lkIjoiZjRkNDQzNTUtNzNkNy00MTdjLWExZjEtZmJhZDYyNDY2NzFmIiwiZW1haWxfdmVyaWZpZWQiOmZhbHNlLCJuYW1lIjoiR3V5bGlhbiBHaWxzaW5nIiwicHJlZmVycmVkX3VzZXJuYW1lIjoiZ3V5bGlhbndhc2hpZXJAZ21haWwuY29tIiwiZ2l2ZW5fbmFtZSI6Ikd1eWxpYW4iLCJmYW1pbHlfbmFtZSI6IkdpbHNpbmciLCJlbWFpbCI6Imd1eWxpYW53YXNoaWVyQGdtYWlsLmNvbSJ9.OEHNKy16B4PpddylrM5dijjwL9jUx5z4DdyPlOgcAM-2bdUYM82hijrrqO8GajpP9nvCLtZjsvFUwEXIggjCgASmu6XtGr9eL8fs9j3qvFBt0LGSov0exCztZPdoiUAGZx8dnoQKac3tEBHfwv0frYIsJTgEqB_H354Xoa9Ngx_93qQyTFrhZbaG67VeYPaDw8qxQdyDuevYqIdLy5bZ1b8mqVj_WG0rtPBOSN9krqqjLu_cDZsG23_ksJVsl0CGZDIbvnPnqSZEdaaJQLg2Kh0jUVrRhpSZ5uIfuaqXa0BvnxcAltXVvsR1gxt5OT5QI5FWbA8AMax5yz2KhymLZA';
    $tokenPrefix = 'Bearer ';
    $request = Mockery::mock(ServerRequestInterface::class);

    if ($request instanceof MockInterface) {
        $request->expects('hasHeader')->with('Authorization')->andReturn(true);
        $request->expects('getHeader')->with('Authorization')->andReturn([$tokenPrefix.$token]);
    }

    // Expected token fields
    $sub = 'ce0e4964-cd75-41f9-8ef2-7bb8d3994573'; // Taken from $token

    // Act
    $extractedTokenContent = get_auth_token_content($request);

    // Assert
    expect($extractedTokenContent)->toBeInstanceOf(AuthTokenContent::class);
    expect($extractedTokenContent->identity)->toBe($sub);
});
