<?php

$router = $di->getRouter();

// Define your routes here

// default
$router->add("/competidor/searchJson", array(
    'controller' => 'competidor',
    'action' => 'searchjson'
));




$router->handle();
