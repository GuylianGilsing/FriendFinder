<?php

declare(strict_types=1);

namespace FriendFinder\Common\Strings;

use ErrorException;

trait ValidateStringTrait
{
    /**
     * @throws ErrorException when an empty piece of text is given.
     */
    protected function validateString(string $field, string $text): void
    {
        if (string_is_blank($text) || strlen($text) === 0) {
            throw new ErrorException($field.' can\'t be empty');
        }
    }
}
