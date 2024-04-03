<?php

declare(strict_types=1);

use Framework\API\UseCases\UseCaseInterface;
use FriendFinder\ProfileInformation\Domain\Profile;
use FriendFinder\ProfileInformation\Repositories\ProfileRepositoryInterface;
use FriendFinder\ProfileInformation\UseCases\CreateProfile;
use FriendFinder\ProfileInformation\UseCases\CreateProfile\CreateProfileResultMessage;
use FriendFinder\ProfileInformation\UseCases\ViewProfile\ViewProfileResult;
use Mockery\MockInterface;
use PHPValidation\ValidatorInterface;

test('Args validator faillure results in args error', function () {
    // Arrange
    $useCaseArgs = [];

    $repository = Mockery::mock(ProfileRepositoryInterface::class);
    $requestBodyValidator = Mockery::mock(ValidatorInterface::class);
    $argsValidator = Mockery::mock(ValidatorInterface::class);
    $viewProfileUseCase = Mockery::mock(UseCaseInterface::class);

    if ($argsValidator instanceof MockInterface) {
        $argsValidator->expects('isValid')->with($useCaseArgs)->times(1)->andReturn(false);
        $argsValidator->expects('getErrorMessages')->times(1)->andReturn(['exampleField' => ['required' => "message"]]);
    }

    $useCase = new CreateProfile($repository, $requestBodyValidator, $argsValidator, $viewProfileUseCase);

    // Act
    $result = $useCase->invoke($useCaseArgs);

    // Assert
    expect($result->profile)->toBeNull();
    expect($result->message)->toBe(CreateProfileResultMessage::ARGS_ERROR);
    expect($result->argumentErrors)->not()->toBeEmpty();
});

test('Request body validator faillure results in args error', function () {
    // Arrange
    $useCaseArgs = ['requestBody' => []];

    $repository = Mockery::mock(ProfileRepositoryInterface::class);
    $requestBodyValidator = Mockery::mock(ValidatorInterface::class);
    $argsValidator = Mockery::mock(ValidatorInterface::class);
    $viewProfileUseCase = Mockery::mock(UseCaseInterface::class);

    if ($argsValidator instanceof MockInterface) {
        $argsValidator->expects('isValid')->with($useCaseArgs)->times(1)->andReturn(true);
    }

    if ($requestBodyValidator instanceof MockInterface) {
        $requestBodyValidator->expects('isValid')->with($useCaseArgs['requestBody'])->times(1)->andReturn(false);
        $requestBodyValidator->expects('getErrorMessages')->times(1)->andReturn(['exampleField' => ['required' => "message"]]);
    }

    $useCase = new CreateProfile($repository, $requestBodyValidator, $argsValidator, $viewProfileUseCase);

    // Act
    $result = $useCase->invoke($useCaseArgs);

    // Assert
    expect($result->profile)->toBeNull();
    expect($result->message)->toBe(CreateProfileResultMessage::ARGS_ERROR);
    expect($result->argumentErrors)->not()->toBeEmpty();
});

test('When an existing profile is found it results in a validation error', function () {
    // Arrange
    $useCaseArgs = [
        'identity' => 'test',
        'requestBody' => [
            'displayName' => 'test',
            'dateOfBirth' => '2000-01-01T00:00:00.000Z',
            'interests' => ['Interest 1', 'Interest 1'],
        ],
    ];

    $repository = Mockery::mock(ProfileRepositoryInterface::class);
    $requestBodyValidator = Mockery::mock(ValidatorInterface::class);
    $argsValidator = Mockery::mock(ValidatorInterface::class);
    $viewProfileUseCase = Mockery::mock(UseCaseInterface::class);

    $profile = Mockery::mock(Profile::class);

    if ($argsValidator instanceof MockInterface) {
        $argsValidator->expects('isValid')->with($useCaseArgs)->times(1)->andReturn(true);
    }

    if ($requestBodyValidator instanceof MockInterface) {
        $requestBodyValidator->expects('isValid')->with($useCaseArgs['requestBody'])->times(1)->andReturn(true);
    }

    if ($viewProfileUseCase instanceof MockInterface) {
        $viewProfileUseCase->expects('invoke')->times(1)->andReturn(new ViewProfileResult($profile));
    }

    $useCase = new CreateProfile($repository, $requestBodyValidator, $argsValidator, $viewProfileUseCase);

    // Act
    $result = $useCase->invoke($useCaseArgs);

    // Assert
    expect($result->profile)->toBeNull();
    expect($result->message)->toBe(CreateProfileResultMessage::VALIDATION_ERROR);
    expect($result->validationErrors)->toBe(['You already have a profile']);
});

test('Duplicate interests found results in validation error', function () {
    // Arrange
    $useCaseArgs = [
        'identity' => 'test',
        'requestBody' => [
            'displayName' => 'test',
            'dateOfBirth' => '2000-01-01T00:00:00.000Z',
            'interests' => ['Interest 1', 'Interest 1'],
        ],
    ];

    $repository = Mockery::mock(ProfileRepositoryInterface::class);
    $requestBodyValidator = Mockery::mock(ValidatorInterface::class);
    $argsValidator = Mockery::mock(ValidatorInterface::class);
    $viewProfileUseCase = Mockery::mock(UseCaseInterface::class);

    if ($argsValidator instanceof MockInterface) {
        $argsValidator->expects('isValid')->with($useCaseArgs)->times(1)->andReturn(true);
    }

    if ($requestBodyValidator instanceof MockInterface) {
        $requestBodyValidator->expects('isValid')->with($useCaseArgs['requestBody'])->times(1)->andReturn(true);
    }

    if ($viewProfileUseCase instanceof MockInterface) {
        $viewProfileUseCase->expects('invoke')->times(1)->andReturn(new ViewProfileResult(null));
    }

    $useCase = new CreateProfile($repository, $requestBodyValidator, $argsValidator, $viewProfileUseCase);

    // Act
    $result = $useCase->invoke($useCaseArgs);

    // Assert
    expect($result->profile)->toBeNull();
    expect($result->message)->toBe(CreateProfileResultMessage::VALIDATION_ERROR);
    expect($result->validationErrors)->toBe(['Duplicate interests are not allowed']);
});
