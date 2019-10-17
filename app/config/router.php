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

$router->add("/competicao/activate", array(
    'controller' => 'competicao',
    'action' => 'activate'
));



$router->handle();
