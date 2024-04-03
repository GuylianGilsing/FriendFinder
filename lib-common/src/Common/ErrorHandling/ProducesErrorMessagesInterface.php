<?php

declare(strict_types=1);

namespace FriendFinder\Common\ErrorHandling;

/**
 * Indicates that a software component generates error messages.
 */
interface ProducesErrorMessagesInterface
{
    /**
     * Retrieves all error messages that may or may not have been triggered by an action.
     *
     * @return array<string> An indexed array of error message strings.
     */
    public function getErrorMessages(): array;
}
