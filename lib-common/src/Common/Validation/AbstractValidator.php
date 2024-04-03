<?php

declare(strict_types=1);

namespace FriendFinder\Common\Validation;

use PHPValidation\Builders\ValidatorBuilder;
use PHPValidation\Strategies\DefaultValidationStrategy;
use PHPValidation\Strategies\ValidationStrategyInterface;
use PHPValidation\Validator;
use PHPValidation\ValidatorInterface;

abstract class AbstractValidator implements ValidatorInterface
{
    private Validator $validator;

    public function __construct()
    {
        $builder = new ValidatorBuilder($this->getValidationStrategy());

        $builder->setValidators($this->getValidators());
        $builder->setErrorMessages($this->getValidationErrorMessages());

        $this->validator = $builder->build();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function isValid(array $data): bool
    {
        return $this->validator->isValid($data);
    }

    /**
     * @return array<string, mixed>
     */
    public function getErrorMessages(): array
    {
        return $this->validator->getErrorMessages();
    }

    /**
     * @return array<string, mixed>
     */
    abstract protected function getValidators(): array;

    /**
     * @return array<string, mixed>
     */
    abstract protected function getValidationErrorMessages(): array;

    protected function getValidationStrategy(): ValidationStrategyInterface
    {
        return new DefaultValidationStrategy();
    }
}
