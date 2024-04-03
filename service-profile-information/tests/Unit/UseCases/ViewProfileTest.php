<?php

declare(strict_types=1);

use FriendFinder\ProfileInformation\Domain\Profile;
use FriendFinder\ProfileInformation\Repositories\ProfileRepositoryInterface;
use FriendFinder\ProfileInformation\UseCases\ViewProfile;
use Mockery\MockInterface;
use PHPValidation\ValidatorInterface;

test('Not passing the identity argument results in "null" value', function () {
    // Arrange
    $profileRepository = Mockery::mock(ProfileRepositoryInterface::class);
    $validator = Mockery::mock(ValidatorInterface::class);

    if ($validator instanceof MockInterface) {
        $validator->expects('isValid')->times(1)->with([])->andReturn(false);
    }

    $useCase = new ViewProfile($profileRepository, $validator);

    // Act
    $result = $useCase->invoke();

    // Assert
    expect($result->profile)->toBeNull();
});
