<?php
/**
 * Apollo application entry script file
 *
 * This file defines the autoload function to parse namespaces for functions and
 * creates an instance of the Apollo object and starts the application.
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 * @version 0.0.1
 */

// Registering an autoload function to resolve namespaces
spl_autoload_register(function ($name) {
    $parts = explode("\\", $name);
    $count = count($parts);
    $class = $parts[$count - 1];
    $namespace = '';
    for ($i = 0; $i < $count - 1; $i++) {
        $namespace .= strtolower($parts[$i]) . '/';
    }
    require_once '../' . $namespace . $class . '.php';
});


use Apollo\Apollo;


define('BASE_URL', 'http://82.0.141.65/');

$app = new Apollo();

$app->start();