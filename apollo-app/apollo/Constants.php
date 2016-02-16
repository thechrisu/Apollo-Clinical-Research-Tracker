<?php
/**
 * Constants file
 *
 * All Apollo application constants are defined in this file
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 * @version 0.0.3
 */


/**
 * The base url of the website with a slash "/" in the end
 * @since 0.0.1
 */
define('BASE_URL_AUTO_DETECT', true);
define('BASE_URL', BASE_URL_AUTO_DETECT ? "http://$_SERVER[HTTP_HOST]/" : 'http://82.0.141.65/');

/**
 * Absolute path to the 'apollo' folder
 * @since 0.0.3
 */
define('APP_DIR', __DIR__);

/**
 * Various relative paths to folder for components
 * @since 0.0.3
 */
define('DOCTRINE_ENTITIES_PATH', '/entities');
define('BLADE_VIEWS_PATH', '/views');
define('BLADE_CACHE_PATH', '/cache');

/**
 * Constants for database connection, self-explanatory
 * @since 0.0.2
 */
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'apollo');
define('DB_USER', 'root');
define('DB_PASS', '');