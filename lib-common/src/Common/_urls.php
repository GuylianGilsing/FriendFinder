<?php

declare(strict_types=1);

namespace FriendFinder\Common\URLs;

use ErrorException;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Checks if a request URL, that exists within a request object, complies with the internal URL standard.
 */
function request_url_is_standardized(ServerRequestInterface $request): bool
{
    $overflowingSlashRegex = '~\/{2,}~';
    $trailingSlashRegex = '~\/+$~';

    $uri = $request->getUri();
    $path = $uri->getPath();

    // Make sure that we only redirect when invalid paths are detected
    preg_match($overflowingSlashRegex, $path, $overFlowingSlashMatches);
    preg_match($trailingSlashRegex, $path, $trailingSlashMatches);

    if ($path === '/') {
        return true;
    }

    if (count($overFlowingSlashMatches) <= 0 && count($trailingSlashMatches) <= 0) {
        return true;
    }

    return false;
}

/**
 * Tries to convert a non-standardized URL to a standardized URL. CAUTION: some URL data can get lost.
 *
 * @param ServerRequestInterface The request that contains the incorrect URL.
 *
 * @return string The normalized string.
 *
 * @throws ErrorException when an already standardized server request URL is given.
 */
function standardize_request_url(ServerRequestInterface $request): string
{
    if (request_url_is_standardized($request)) {
        throw new ErrorException('Given URL is already normalized.');
    }

    $overflowingSlashRegex = '~\/{2,}~';
    $trailingSlashRegex = '~\/+$~';

    $uri = $request->getUri();
    $path = $uri->getPath();
    $query = $uri->getQuery();

    // Fix the url and redirect
    $fixedPath = preg_replace($overflowingSlashRegex, '/', $path);

    if ($fixedPath !== '/') {
        $fixedPath = preg_replace($trailingSlashRegex, '', $fixedPath);
    }

    // Add query variables back to the url
    if (strlen($query) > 0) {
        $fixedPath .= '?'.$query;
    }

    return $fixedPath;
}
