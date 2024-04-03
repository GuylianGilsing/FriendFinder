# Friend finder common library
Common functionality that can be used inside any backend.

<!-- TOC -->

- [Friend finder common library](#friend-finder-common-library)
    - [Validation](#validation)
        - [Creating a validator](#creating-a-validator)

<!-- /TOC -->

## Validation
A validation class that enables a developer to write array validators in a more standardized manner. The validation part of the core library provides a wrapper around my [PHP Validation](https://github.com/GuylianGilsing/PHPValidation) library. That library is written with no concept of infrastructure code. This means that developers need to write infrastructure code themselves to make creating validators easier. This part of the common library provides that infrastructure code.

### Creating a validator
A validation class can be made as follows:

```php
use FriendFinder\Common\Validation\AbstractValidator;

final class MyValidator extends AbstractValidator
{
    protected function getValidators(): array
    {
        return [];
    }

    protected function getValidationErrorMessages(): array
    {
        return [];
    }
}
```

The `getValidators()` method expects a GuylianGilsing\PHPValidation field validator array, [read more about this here](https://github.com/GuylianGilsing/PHPValidation#configuring-array-field-validation). The `getValidationErrorMessages()` method expects a validation error message array, [read more about this here](https://github.com/GuylianGilsing/PHPValidation#configuring-custom-error-messages). A finished validator class may look as follows:

```php
use FriendFinder\Common\Validation\AbstractValidator;

use function PHPValidation\Functions\email;
use function PHPValidation\Functions\maxLength;
use function PHPValidation\Functions\minLength;
use function PHPValidation\Functions\notEmpty;

final class UserValidator extends AbstractValidator
{
    protected function getValidators(): array
    {
        return [
            'email' => [notEmpty(), email()],
            'password' => [notEmpty(), minLength(6), maxLength(255)],
        ];
    }

    protected function getValidationErrorMessages(): array
    {
        return [
            'email' => [
                'notEmpty' => 'Custom error message...',
                'email' => 'Custom error message...',
            ],
            'password' => [
                'notEmpty' => 'Custom error message...',
                'minLength' => 'Custom error message...',
                'maxLength' => 'Custom error message...',
            ],
        ];
    }
}
```
