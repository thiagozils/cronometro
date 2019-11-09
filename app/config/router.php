<?php

$router = $di->getRouter();

// Define your routes here

// default
$router->add("/competidor/next", array(
    'controller' => 'competidor',
    'action' => 'next'
));

$router->add("/competicao/details/{id}", array(
    'controller' => 'competicao',
    'action' => 'details'
));

$router->add("/competicao/results/{id}", array(
    'controller' => 'competicao',
    'action' => 'results'
));


$router->add("/competicao/activate", array(
    'controller' => 'competicao',
    'action' => 'activate'
));

$router->add("/competicao/active", array(
    'controller' => 'competicao',
    'action' => 'active'
));

$router->add("/volta/createurl", array(
    'controller' => 'volta',
    'action' => 'createurl'
));

$router->handle();
