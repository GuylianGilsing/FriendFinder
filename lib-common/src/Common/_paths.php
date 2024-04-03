<?php

declare(strict_types=1);

namespace FriendFinder\Common\Paths;

/**
 * Transforms a given string so that it complies with the internal CMS path format.
 */
function string_to_directory_path_format(string $segment): string
{
    $stringStartsCorrectly = false;
    $transformed = $segment;

    // Convert "\" to "/"
    $transformed = str_replace('\\', '/', $transformed);

    // Prevents breaking windows paths
    if (str_starts_with(strtolower($segment), 'c:/') || str_starts_with(strtolower($segment), 'c:\\')) {
        $stringStartsCorrectly = true;
    }

    // Prefix path with a "/" (unless it is a windows path)
    if (!$stringStartsCorrectly && !str_starts_with($transformed, '/')) {
        $transformed = '/'.$transformed;
    }

    // Remove double "/"
    $transformed = preg_replace('~\/{2,}~', '/', $transformed);

    if (strlen($transformed) === 1) {
        return $transformed;
    }

    // Remove trailing slash
    if (strlen($transformed) > 1 && (str_ends_with($transformed, '/') || str_ends_with($transformed, '\\'))) {
        $transformed = substr($transformed, 0, strlen($transformed) - 1);
    }

    return $transformed;
}
