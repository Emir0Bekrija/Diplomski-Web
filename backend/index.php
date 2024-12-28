<?php

require 'rest/config.php'; 
require 'vendor/autoload.php';
require 'rest/routes/products_routes.php';
require 'rest/routes/prices_routes.php';
require 'rest/routes/websites_routes.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


Flight::route('/', function(){
    echo 'hello world!';
});

Flight::start();