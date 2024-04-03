<?php

declare(strict_types=1);

namespace FriendFinder;

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

use function FriendFinder\Search\DependencyInjection\search_common_dependency_injection_definitions;
use function FriendFinder\Search\EventProcessor\get_dependency_definitions;

function get_dependency_container_instance(): ContainerInterface
{
    static $container = null;

    if ($container === null) {
        $builder = new ContainerBuilder();

        $builder->useAttributes(false);
        $builder->useAutowiring(true);

        $builder->addDefinitions(get_dependency_definitions());
        $builder->addDefinitions(search_common_dependency_injection_definitions());

        $container = $builder->build();
    }

    return $container;
}
