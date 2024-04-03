<?php

declare(strict_types=1);

namespace FriendFinder\Common\Strings;

/**
 * Validates that a string only contains empty characters like spaces or tabs.
 */
function string_is_blank(string $text): bool
{
    $targetEmptyCharsPattern = '[\\n\\r\s\\t]+';

    $cleanedText = preg_replace('~'.$targetEmptyCharsPattern.'~', '', $text);

    return strlen($cleanedText) === 0;
}

function generate_summary(string $text, int $maxLength): string
{
    $postFix = '...';

    if (strlen($text) <= $maxLength) {
        return $text;
    }

    return substr($text, 0, $maxLength - strlen($postFix)).$postFix;
}
