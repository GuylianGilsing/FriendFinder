<?php

declare(strict_types=1);

use FriendFinder\ProfileInformation\Domain\Profile;
use FriendFinder\ProfileInformation\Repositories\ProfileRepositoryInterface;
use FriendFinder\ProfileInformation\UseCases\ViewProfile;
use Mockery\MockInterface;
use PHPValidation\ValidatorInterface;

test('Can retrieve the information of a profile', function () {
    // Arrange
    $identity = 'identity';
    $profile = new Profile($identity, 'test', DateTimeImmutable::createFromFormat('Y-m-d', '2000-01-01'));

    $profileRepository = Mockery::mock(ProfileRepositoryInterface::class);
    $validator = Mockery::mock(ValidatorInterface::class);

    if ($profileRepository instanceof MockInterface) {
        $profileRepository->expects('getByIdentity')->times(1)->with($identity)->andReturn($profile);
    }

    if ($validator instanceof MockInterface) {
        $validator->expects('isValid')->times(1)->andReturn(true);
    }

    $useCase = new ViewProfile($profileRepository, $validator);

    // Act
    $result = $useCase->invoke(['identity' => $identity]);

    // Assert
    expect($result->profile)->not()->toBeNull();
});

test('Can\'t retrieve the information of a non existent profile', function () {
    // Arrange
    $identity = 'identity';

    $profileRepository = Mockery::mock(ProfileRepositoryInterface::class);
    $validator = Mockery::mock(ValidatorInterface::class);

    if ($profileRepository instanceof MockInterface) {
        $profileRepository->expects('getByIdentity')->times(1)->with($identity)->andReturn(null);
    }

    if ($validator instanceof MockInterface) {
        $validator->expects('isValid')->times(1)->andReturn(true);
    }

    $useCase = new ViewProfile($profileRepository, $validator);

    // Act
    $result = $useCase->invoke(['identity' => $identity]);

    // Assert
    expect($result->profile)->toBeNull();
});
