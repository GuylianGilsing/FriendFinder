<?php

declare(strict_types=1);

use function FriendFinder\Common\Strings\generate_summary;

test('That the full text is returned if it does not exceed the content limit', function () {
    // Arrange
    $text = '123456789';
    $contentLength = 9;

    // Act
    $summary = generate_summary($text, maxLength: $contentLength);

    // Assert
    expect($summary)->toBe($text);
});

test('That a summary is created when the text exceeds the content limit', function () {
    // Arrange
    $text = '123456789';
    $contentLength = 8;

    // Act
    $summary = generate_summary($text, maxLength: $contentLength);

    // Assert
    expect($summary)->toBe('12345...');
});
