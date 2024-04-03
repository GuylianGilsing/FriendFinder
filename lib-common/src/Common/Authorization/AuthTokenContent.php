<?php

declare(strict_types=1);

namespace FriendFinder\Common\Authorization;

use ErrorException;
use Ramsey\Uuid\Rfc4122\UuidV4;

use function FriendFinder\Common\Strings\string_is_blank;

final class AuthTokenContent
{
    /**
     * @throws ErrorException when an invalid identity value is given.
     */
    public function __construct(
        public readonly string $identity,
    ) {
        $this->validateAndGenerateExceptions();
    }

    /**
     * @throws ErrorException when an invalid identity value is given.
     */
    private function validateAndGenerateExceptions(): void
    {
        $this->performIdentityFieldValidations();
    }

    /**
     * @throws ErrorException when an invalid identity value is given.
     */
    private function performIdentityFieldValidations(): void
    {
        if (string_is_blank($this->identity)) {
            throw new ErrorException('Token identity is not valid');
        }

        if (!UuidV4::isValid($this->identity)) {
            throw new ErrorException('Token identity is not a valid UUID');
        }
    }
}
