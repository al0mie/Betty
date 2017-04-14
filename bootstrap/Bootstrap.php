<?php

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/Router.php';

$container = \DI\ContainerBuilder::buildDevContainer();

$uri = Router::makeUri();

// Check uri and compare with routes
if ($params = Router::matchUri($uri)) {
    $controller = 'App\Controllers\\' . ucwords($params['controller']) . 'Controller';
    $method = $params['method'];

    unset($params['controller'], $params['method']);

    if (class_exists($controller)) {
        if (method_exists($controller, $method)) {
            $controller = $container->get($controller);
            $controller->$method($params);
        } else {
            throw new Exception('Bootstrap : No method found ' . $method);
        }
    } else {
        throw new Exception('Bootstrap : No controller found ' . $controller);
    }
} else {
    throw new Exception('Bootstrap : No route found ' . $uri);
}