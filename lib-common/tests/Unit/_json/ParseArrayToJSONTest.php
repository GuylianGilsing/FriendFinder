<?php

declare(strict_types=1);

namespace Tests\Unit\JSON;

use PHPUnit\Framework\TestCase;
use stdClass;

use function FriendFinder\Common\JSON\parse_array_to_json;

/**
 * Old PHP Unit tests that I currently do not want to convert to Pest tests.
 */
final class ParseArrayToJSONTest extends TestCase
{
    public function testIfEmptyArraysAreBeingKeptAsEmptyArraysWhenParsingToJSON(): void
    {
        // Arrange
        $arrayToParse = [
            'empty_array' => [],
        ];

        // Act
        $parsed = parse_array_to_json($arrayToParse);

        // Assert
        $this->assertIsString($parsed);
        $this->assertEquals('{"empty_array":[]}', $parsed);
    }

    public function testIfEmptyObjectIsBeingConvertedToNullValueWhenParsingToJSON(): void
    {
        // Arrange
        $arrayToParse = [
            'empty_object_as_null' => new stdClass(),
        ];

        // Act
        $parsed = parse_array_to_json($arrayToParse);

        // Assert
        $this->assertIsString($parsed);
        $this->assertEquals('{"empty_object_as_null":null}', $parsed);
    }

    public function testIfEmptyObjectsInsideStringsAreNotBeingConvertedToNullValuesWhenParsingToJSON(): void
    {
        // Arrange
        $arraysToParse = [
            ['test' => "{}{}{}"],
            ['test":' => "{}{}{}"],
            ['":test' => "\":{}{}{}"],
            ['test' => "1\":{}{}\":{}"],
        ];

        foreach ($arraysToParse as $arrayToParse)
        {
            // Act
            $parsed = parse_array_to_json($arrayToParse);

            // Assert
            $this->assertIsString($parsed);

            $resultKey = addslashes(array_keys($arrayToParse)[0]);
            $resultValue = addslashes(array_values($arrayToParse)[0]);

            $resultString = '{"'.$resultKey.'":"'.$resultValue.'"}';
            $this->assertEquals($resultString, $parsed);
        }
    }
}
