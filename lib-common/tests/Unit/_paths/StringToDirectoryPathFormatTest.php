<?php

declare(strict_types=1);

namespace Tests\Unit\Paths;

use PHPUnit\Framework\TestCase;

use function FriendFinder\Common\Paths\string_to_directory_path_format;

/**
 * Old PHP Unit tests that I currently do not want to convert to Pest tests.
 */
final class StringToDirectoryPathFormatTest extends TestCase
{
    public function testIfEmptyStringPointsToTheLinuxRootDirectory(): void
    {
        // Arrange
        $stringToTest = '';

        // Act
        $transformedString = string_to_directory_path_format($stringToTest);

        // Assert
        $this->assertEquals('/', $transformedString);
    }

    public function testIfStringThatDoesNotStartWithASlashStartsWithASlash(): void
    {
        // Arrange
        $stringToTest = 'directory-1/directory2';

        // Act
        $transformedString = string_to_directory_path_format($stringToTest);

        // Assert
        $this->assertEquals('/directory-1/directory2', $transformedString);
    }

    public function testIfStringThatEndsWithASlashDoesNotEndWithASlash(): void
    {
        // Arrange
        $stringToTest = '/directory-1/directory2/';

        // Act
        $transformedString = string_to_directory_path_format($stringToTest);

        // Assert
        $this->assertEquals('/directory-1/directory2', $transformedString);
    }

    public function testIfWrongStringIsFormattedCorrectly(): void
    {
        // Arrange
        $stringToTest = 'directory-1/directory2/';

        // Act
        $transformedString = string_to_directory_path_format($stringToTest);

        // Assert
        $this->assertEquals('/directory-1/directory2', $transformedString);
    }

    public function testIfWindowsStringIsNotConvertedToLinuxRootString(): void
    {
        // Arrange
        $stringToTest = 'C:\directory-1/directory2/';

        // Act
        $transformedString = string_to_directory_path_format($stringToTest);

        // Assert
        $this->assertEquals('C:/directory-1/directory2', $transformedString);
    }

    public function testIfStringWithTrailingSlashesIsConvertedToStringThatEndsWithNoSlashes(): void
    {
        // Arrange
        $stringToTest = '/directory-1/directory2///';

        // Act
        $transformedString = string_to_directory_path_format($stringToTest);

        // Assert
        $this->assertEquals('/directory-1/directory2', $transformedString);
    }

    public function testIfRepeatingForwardSlashesAreReplacedWithSingularForwardSlash(): void
    {
        // Arrange
        $stringToTest = '////////directory-1///directory2//';

        // Act
        $transformedString = string_to_directory_path_format($stringToTest);

        // Assert
        $this->assertEquals('/directory-1/directory2', $transformedString);
    }

    public function testIfRepeatingBackwardSlashesAreReplacedWithSingularForwardSlash(): void
    {
        // Arrange
        $stringToTest = '\directory-1\\directory2\\';

        // Act
        $transformedString = string_to_directory_path_format($stringToTest);

        // Assert
        $this->assertEquals('/directory-1/directory2', $transformedString);
    }
}
