<?php

use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\DI\FactoryDefault;

try {

    // Register an autoloader
    $loader = new Loader();
    $loader->registerDirs([
        '../app/controllers/'
    ])->register();

    // Create a FactoryDefault DI
    $di = new FactoryDefault();

    // Setting up the view component
    $di['view'] = function () {
        $view = new View();
        $view->setViewsDir('../app/views/');

        return $view;
    };

    // Handle the request
    $application = new Application($di);

    $application->handle()->send();

} catch (Exception $e) {
    echo "Exception: ", $e->getMessage();
}
