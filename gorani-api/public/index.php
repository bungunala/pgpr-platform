<?php

/**
 * CodeIgniter Bootstrap File
 *
 * This file serves as the "front controller" for your CodeIgniter application,
 * initializing the base resources needed to run the application.
 */

// Load the Composer autoloader
require __DIR__ . '/../vendor/autoload.php';

// Load the CodeIgniter bootstrap file
$app = require_once __DIR__ . '/../vendor/codeigniter4/framework/system/Bootstrap.php';

// Run the application
$app->run();