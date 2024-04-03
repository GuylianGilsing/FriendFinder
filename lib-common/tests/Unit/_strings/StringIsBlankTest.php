<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use function FriendFinder\Common\Strings\string_is_blank;

final class StringIsBlankTest extends TestCase
{
    public function testStringWithNonEmptyCharactersReturnsFalse(): void
    {
        // Arrange
        $charactersToTest = [
            'test string',
        ];

        foreach ($charactersToTest as $character) {
            // Act
            $onlyContainsEmptyCharacters = string_is_blank($character);

            // Assert
            $this->assertFalse($onlyContainsEmptyCharacters);
        }
    }

    public function testStringWithOnlyEmptyCharactersReturnsTrue(): void
    {
        // Arrange
        $charactersToTest = [
            ' ', // Space
            '	', // Tab (U+0009)
            ' ', // &nbsp; (U+00A0)
            '
            ', // Carriage return (U+000D)
        ];

        foreach ($charactersToTest as $character) {
            // Act
            $onlyContainsEmptyCharacters = string_is_blank($character);

            // Assert
            $this->assertTrue($onlyContainsEmptyCharacters);
        }
    }
}
