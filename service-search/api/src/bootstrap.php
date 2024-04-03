<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Framework\API\Application\Wrappers\SlimApplicationWrapper;
use Framework\API\REST;
use Psr\Container\ContainerInterface;
use Slim\App;

use function FriendFinder\Common\DependencyInjection\roadrunner_http_dependency_container_definitions;
use function FriendFinder\Search\DependencyInjection\get_dependency_definitions;
use function FriendFinder\Search\DependencyInjection\search_common_dependency_injection_definitions;

function get_rest_api_instance(): REST
{
    static $api = null;

    if ($api === null) {
        $api = new REST(new SlimApplicationWrapper(get_application_instance()));
        configure_rest_api($api);
    }

    return $api;
}

function get_application_instance(): App
{
    static $app = null;

    if ($app === null) {
        $app = \DI\Bridge\Slim\Bridge::create(get_dependency_container_instance());
        configure_application($app);
    }

    return $app;
}

function get_dependency_container_instance(): ContainerInterface
{
    static $container = null;

    if ($container === null) {
        $builder = new ContainerBuilder();

        $builder->useAttributes(false);
        $builder->useAutowiring(true);

        $builder->addDefinitions(roadrunner_http_dependency_container_definitions());
        $builder->addDefinitions(search_common_dependency_injection_definitions());
        $builder->addDefinitions(get_dependency_definitions());

        $container = $builder->build();
    }

    return $container;
}
