<?php

declare(strict_types=1);

namespace FriendFinder\ContactInformation\UseCases;

use Framework\API\UseCases\UseCaseInterface;
use FriendFinder\ContactInformation\Context\ContactInformationContext;
use FriendFinder\ContactInformation\UseCases\ViewOptions\Result;

final class ViewOptions implements UseCaseInterface
{
    public function __construct(
        private readonly ContactInformationContext $context,
    ) {
    }

    /**
     * @param array<string, mixed> $args
     *
     * @return Result
     */
    public function invoke(array $args = []): object
    {
        $args; // Stops linter from whining about "unused parameter $args"
        return new Result([
            'socials' => $this->context->allowedSocials(),
        ]);
    }
}
