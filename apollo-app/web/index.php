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


require_once '../vendor/autoload.php';


use Apollo\Apollo;

// Defining constants
define('BASE_URL', 'http://82.0.141.65/');


$app = new Apollo();

$app->start();