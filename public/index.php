<?php

try {
    // Register an autoloader
    $loader = new Phalcon\Loader();
    $loader->registerDirs([
        '../app/controllers/',
        '../app/models/'
    ])->register();

    // Create a FactoryDefault DI
    $di = new Phalcon\DI\FactoryDefault();

    // Register Volt as a service
    $di['voltService'] = function ($view, $di) {
        $volt = new Phalcon\Mvc\View\Engine\Volt($view, $di);

        $volt->setOptions([
            "compiledPath"      => realpath('..') . "/runtime/",
            "compiledExtension" => ".compiled",
        ]);

        return $volt;
    };

    // Register Volt as template engine
    $di['view'] = function () {
        $view = new Phalcon\Mvc\View();
        $view->setViewsDir('../app/views/');

        $view->registerEngines([
            ".volt" => "voltService",
        ]);

        return $view;
    };

    // Handle the request
    $application = new Phalcon\Mvc\Application($di);

    $application->handle()->send();

} catch (Exception $e) {
    echo $e->getMessage();
}
