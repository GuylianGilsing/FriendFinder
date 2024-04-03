<?php

declare(strict_types=1);

use FriendFinder\Common\Events\ProfileInformation\ProfileInformationUpdatedEvent;
use FriendFinder\Common\Events\ProfileInformation\ProfileInformationUpdatedEventType;

test('Can create event', function () {
    // Arrange
    $type = ProfileInformationUpdatedEventType::UPDATED;
    $payload = ['test' => 'payload'];

    // Act
    $event = new ProfileInformationUpdatedEvent($type, $payload);

    // Assert
    expect($event->getType())->toBe($type);
    expect($event->getPayload())->toBe($payload);
    expect($event->getCreatedAt())->toBeInstanceOf(DateTimeImmutable::class);
});

describe('Test exceptions', function () {
    it('Should throw an exception when invalid payload is given', function () {
        // Arrange
        $type = ProfileInformationUpdatedEventType::UPDATED;
        $payload = [];

        // Act
        $action = fn () => new ProfileInformationUpdatedEvent($type, $payload);

        // Assert
        expect($action)->toThrow(ErrorException::class, 'No valid payload is given');
    });
});
