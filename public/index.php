<?php
/*
 * Application: DbM Framework version 2
 * Author: Arthur Malinowski (C) Design by Malina
 * Web page: www.dbm.org.pl
 * License: MIT
*/

### DECLARATION strict typing ###
declare(strict_types=1);

### OUTPUT BUFFERING for htmlErrorHandler()
ob_start();

### BASE PATH ###
define('DS', DIRECTORY_SEPARATOR);
define('BASE_DIRECTORY', dirname(__DIR__) . DS);

### FUNCTIONS - application starting
require(BASE_DIRECTORY . 'application' . DS . 'start.php');

// ERROR HANDLING & TIME ZONE
setupErrorHandling();
// Time zone; Info: Not every server needed!
@date_default_timezone_set('Europe/Warsaw');

### STARTING APPLICATION, configuration and autoloading.
$pathConfig = BASE_DIRECTORY . '.env';
$pathAutoload = BASE_DIRECTORY . 'vendor' . DS . 'autoload.php';

// Configuration settings
configurationSettings($pathConfig);

// Autoloading with and without Composer
autoloadingWithWithoutComposer($pathAutoload);

// Environment variables
use Dbm\Classes\DotEnv;
(new DotEnv($pathConfig))->load();

// Session configuration
initializeSession();

// Database connection
use Dbm\Classes\Database;
$database = (getenv('DB_NAME')) ? new Database() : null;

// Routing
$routes = require BASE_DIRECTORY . 'application' . DS . 'routes.php';
$routes($database);
