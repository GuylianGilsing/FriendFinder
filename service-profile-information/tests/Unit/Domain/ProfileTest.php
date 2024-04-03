<?php

declare(strict_types=1);

use FriendFinder\ProfileInformation\Domain\Profile;

describe('Test exceptions', function () {
    it('Should throw an exception when a profile is constructed with an empty identity value', function () {
        // Arrange
        $identity = '';
        $displayName = 'test';
        $dateOfBirth = DateTimeImmutable::createFromFormat('Y-m-d', '2000-01-01');

        $expectedExceptionMessage = 'Identity can\'t be empty';

        // Act
        $action = fn () => new Profile($identity, $displayName, $dateOfBirth);

        // Assert
        expect($action)->toThrow(ErrorException::class, $expectedExceptionMessage);
    });

    it('Should throw an exception when a profile is constructed with an empty display name value', function () {
        // Arrange
        $identity = 'test';
        $displayName = '';
        $dateOfBirth = DateTimeImmutable::createFromFormat('Y-m-d', '2000-01-01');

        $expectedExceptionMessage = 'Given display name can\'t be empty';

        // Act
        $action = fn () => new Profile($identity, $displayName, $dateOfBirth);

        // Assert
        expect($action)->toThrow(ErrorException::class, $expectedExceptionMessage);
    });

    it('Should throw an exception when a profile is constructed for someone under 18', function () {
        // Arrange
        $identity = 'test';
        $displayName = 'test';
        $invalidDate = (new DateTimeImmutable())->modify('-18 year')->modify('+1 day');

        $expectedExceptionMessage = 'Given date is not that of someone that is 18 years or older';

        // Act
        $action = fn () => new Profile($identity, $displayName, $invalidDate);

        // Assert
        expect($action)->toThrow(ErrorException::class, $expectedExceptionMessage);
    });

    it('Should throw an exception when an interest with an empty text value is passed', function () {
        // Arrange
        $expectedExceptionMessage = 'Interest can\'t be blank';

        $profile = new Profile('test', 'test', DateTimeImmutable::createFromFormat('Y-m-d', '2000-01-01'));

        // Act
        $action = fn () => $profile->addInterest('');

        // Assert
        expect($action)->toThrow(ErrorException::class, $expectedExceptionMessage);
    });

    it('Should throw an exception when an interest that exceeds the character limit is passed', function () {
        // Arrange
        $expectedExceptionMessage = 'Interest can\'t be longer than 32 characters';

        $profile = new Profile('test', 'test', DateTimeImmutable::createFromFormat('Y-m-d', '2000-01-01'));

        // Act
        $action = fn () => $profile->addInterest('88005657986471695126193065636083x');

        // Assert
        expect($action)->toThrow(ErrorException::class, $expectedExceptionMessage);
    });

    it('Should throw an exception when a duplicate interest is being added to the profile', function () {
        // Arrange
        $interest = 'test';
        $expectedExceptionMessage = 'Interest "'.$interest.'" has already been added to your profile';

        $profile = new Profile('test', 'test', DateTimeImmutable::createFromFormat('Y-m-d', '2000-01-01'));
        $profile->addInterest($interest);

        // Act
        $action = fn () => $profile->addInterest($interest);

        // Assert
        expect($action)->toThrow(ErrorException::class, $expectedExceptionMessage);
    });
});

describe('Test interests', function () {
    it('should be able to add interests', function () {
        // Arrange
        $interestToAdd = 'Test interest';
        $profile = new Profile('test', 'test', DateTimeImmutable::createFromFormat('Y-m-d', '2000-01-01'));

        // Act
        $profile->addInterest($interestToAdd);

        // Assert
        expect($profile->getInterests())->not()->toBeEmpty();
        expect($profile->getInterests())->toHaveCount(1);
        expect($profile->getInterests()[0]->getText())->toBe($interestToAdd);
    });

    it('should be able to check if a profile doesn\'t have an interest listed', function () {
        // Arrange
        $interest = 'Test interest';
        $profile = new Profile('test', 'test', DateTimeImmutable::createFromFormat('Y-m-d', '2000-01-01'));

        // Act
        $hasInterest = $profile->hasInterest($interest);

        // Assert
        expect($hasInterest)->toBeFalse();

        // Act
        $profile->addInterest('Other interest');
        $hasInterest = $profile->hasInterest($interest);

        // Assert
        expect($hasInterest)->toBeFalse();
    });

    it('should be able to check if a profile has an interest listed', function () {
        // Arrange
        $interest = 'Test interest';
        $profile = new Profile('test', 'test', DateTimeImmutable::createFromFormat('Y-m-d', '2000-01-01'));

        $profile->addInterest($interest);

        // Act
        $hasInterest = $profile->hasInterest($interest);

        // Assert
        expect($hasInterest)->toBeTrue();
    });

    it('should be able to return all interests as an array', function () {
        // Arrange
        $profile = new Profile('test', 'test', DateTimeImmutable::createFromFormat('Y-m-d', '2000-01-01'));

        $profile->addInterest('Interest 1');
        $profile->addInterest('Interest 2');
        $profile->addInterest('Interest 3');

        // Act
        $action = fn () => $profile->getInterests();

        // Assert
        expect($action)->not()->toThrow(Exception::class);
    });
});

test('Can create profile', function () {
    // Arrange
    $identity = 'identity';
    $displayName = 'displayName';
    $dateOfBirth = DateTimeImmutable::createFromFormat('Y-m-d', '2000-01-01');
    $interests = ['Interest 1', 'Interest 2'];

    // Act
    $profile = new Profile($identity, $displayName, $dateOfBirth, $interests);

    // Assert
    expect($profile->getIdentity())->toBe($identity);
    expect($profile->getDisplayName())->toBe($displayName);
    expect($profile->getDateOfBirth())->toBe($dateOfBirth);
    expect($profile->getInterests())->toHaveLength(2);
    expect($profile->getInterests()[0])->toBe($interests[0]);
    expect($profile->getInterests()[1])->toBe($interests[1]);
});
