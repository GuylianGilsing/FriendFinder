<?php

declare(strict_types=1);

use FriendFinder\ProfileInformation\Domain\Interest;
use FriendFinder\ProfileInformation\Domain\Profile;

test('Can create interest', function () {
    // Arrange
    $id = 1;
    $profile = new Profile('test', 'test', DateTimeImmutable::createFromFormat('Y-m-d', '2000-01-01'));
    $text = 'test';

    // Act
    $interest = new Interest($id, $profile, $text);

    // Assert
    expect($interest->getID())->toBe($id);
    expect($interest->getProfile())->toBe($profile);
    expect($interest->getText())->toBe($text);
});

describe('Test exceptions', function () {
    it('Should throw an exception when the text of an interest is set to an empty value', function () {
        // Arrange
        $id = 1;
        $profile = new Profile('test', 'test', DateTimeImmutable::createFromFormat('Y-m-d', '2000-01-01'));
        $text = 'not-empty';

        $interest = new Interest($id, $profile, $text);
        $expectedExceptionMessage = 'Given text can\'t be blank';

        // Act
        $action = fn () => $interest->setText('');

        // Assert
        expect($action)->toThrow(ErrorException::class, $expectedExceptionMessage);
    });

    it(
        'Should throw an exception when the text of an interest is set to a value that exceeds the character limit',
        function () {
            // Arrange
            $id = 1;
            $profile = new Profile('test', 'test', DateTimeImmutable::createFromFormat('Y-m-d', '2000-01-01'));
            $text = 'not-empty';

            $interest = new Interest($id, $profile, $text);
            $expectedExceptionMessage = 'Given text can\'t be longer than 32 characters';

            // Act
            $action = fn () => $interest->setText('88005657986471695126193065636083x');

            // Assert
            expect($action)->toThrow(ErrorException::class, $expectedExceptionMessage);
        }
    );

    it('Should throw an exception when an interest is constructed with an empty text value', function () {
        // Arrange
        $id = 1;
        $profile = new Profile('test', 'test', DateTimeImmutable::createFromFormat('Y-m-d', '2000-01-01'));
        $text = '';

        $expectedExceptionMessage = 'Given text can\'t be blank';

        // Act
        $action = fn () => new Interest($id, $profile, $text);

        // Assert
        expect($action)->toThrow(ErrorException::class, $expectedExceptionMessage);
    });

    it(
        'Should throw an exception when an interest is constructed with a text value that exceeds the character limit',
        function () {
            // Arrange
            $id = 1;
            $profile = new Profile('test', 'test', DateTimeImmutable::createFromFormat('Y-m-d', '2000-01-01'));
            $text = '88005657986471695126193065636083x';

            $expectedExceptionMessage = 'Given text can\'t be longer than 32 characters';

            // Act
            $action = fn () => new Interest($id, $profile, $text);

            // Assert
            expect($action)->toThrow(ErrorException::class, $expectedExceptionMessage);
        }
    );
});
