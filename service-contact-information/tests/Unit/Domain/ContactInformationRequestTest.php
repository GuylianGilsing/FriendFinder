<?php

declare(strict_types=1);

use FriendFinder\ContactInformation\Domain\ContactInformationRequest;

test('Can construct model properly', function () {
    // Arrange
    $id = 'id';
    $senderProfileID = 'senderProfileID';
    $receiverProfileID = 'senderProfileID';
    $message = 'lorem ipsum dolor sit amet.';
    $socials = [
        'x' => '@jane.doe',
        'instagram' => '@jane.doe',
    ];
    $answer = null;

    // Act
    $model = new ContactInformationRequest($id, $senderProfileID, $receiverProfileID, $message, $socials, $answer);

    // Assert
    expect($model->getID())->toBe($id);
    expect($model->getSenderProfileID())->toBe($senderProfileID);
    expect($model->getReceiverProfileID())->toBe($receiverProfileID);
    expect($model->getMessage())->toBe($message);
    expect($model->getSocials())->toBe($socials);
    expect($model->getAnswer())->toBe($answer);
});

describe('Test exceptions', function () {
    it('should thrown an ErrorException when an empty ID string is used', function () {
        // Arrange
        $id = '';
        $senderProfileID = 'senderProfileID';
        $receiverProfileID = 'senderProfileID';
        $message = 'lorem ipsum dolor sit amet.';
        $socials = ['x' => '@jane.doe'];
        $answer = null;

        // Act
        $action = fn () => new ContactInformationRequest($id, $senderProfileID, $receiverProfileID, $message, $socials, $answer);

        // Assert
        expect($action)->toThrow(ErrorException::class);
    });

    it('should thrown an ErrorException when an empty sneder profile ID string is used', function () {
        // Arrange
        $id = 'id';
        $senderProfileID = '';
        $receiverProfileID = 'senderProfileID';
        $message = 'lorem ipsum dolor sit amet.';
        $socials = ['x' => '@jane.doe'];
        $answer = null;

        // Act
        $action = fn () => new ContactInformationRequest($id, $senderProfileID, $receiverProfileID, $message, $socials, $answer);

        // Assert
        expect($action)->toThrow(ErrorException::class);
    });

    it('should thrown an ErrorException when an empty receiver profile ID string is used', function () {
        // Arrange
        $id = 'id';
        $senderProfileID = 'senderProfileID';
        $receiverProfileID = '';
        $message = 'lorem ipsum dolor sit amet.';
        $socials = ['x' => '@jane.doe'];
        $answer = null;

        // Act
        $action = fn () => new ContactInformationRequest($id, $senderProfileID, $receiverProfileID, $message, $socials, $answer);

        // Assert
        expect($action)->toThrow(ErrorException::class);
    });

    it('should thrown an ErrorException when an empty message string is used', function () {
        // Arrange
        $id = 'id';
        $senderProfileID = 'senderProfileID';
        $receiverProfileID = 'senderProfileID';
        $message = '';
        $socials = ['x' => '@jane.doe'];
        $answer = null;

        // Act
        $action = fn () => new ContactInformationRequest($id, $senderProfileID, $receiverProfileID, $message, $socials, $answer);

        // Assert
        expect($action)->toThrow(ErrorException::class);
    });
});
