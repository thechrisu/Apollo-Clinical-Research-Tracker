<?php
/**
 * Constants file
 *
 * All Apollo application constants are defined in this file
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.8
 */

/**
 * Application name
 * @since 0.0.9
 */
define('APP_NAME', 'Apollo');

/**
 * The base url of the website with a trailing slash "/".
 * @since 0.0.4 Added auto-detection
 * @since 0.0.1
 */
define('BASE_URL_AUTO_DETECT', true);
define('BASE_URL', BASE_URL_AUTO_DETECT ? "http://$_SERVER[HTTP_HOST]/" : 'http://82.0.141.65/');

/**
 * The default controller that authorised users will be redirected to when accessing the index page
 * @since 0.0.9
 */
define('DEFAULT_CONTROLLER', 'record');

/**
 * Absolute path to the 'apollo' folder
 * @since 0.0.3
 */
define('APP_DIR', __DIR__);

/**
 * Absolute path to the 'assets' folder
 * @since 0.0.7 Fixed the typo in ASSET_BASE_URL
 * @since 0.0.6 Added ASSET_BASE_URL
 * @since 0.0.5
 */
define('ASSET_DIR', APP_DIR . '/assets/');
define('ASSET_BASE_URL', BASE_URL . 'asset/');

/**
 * Various relative paths to folder for components
 * @since 0.0.3
 */
//TODO Tim: Might wanna change this to absolute values
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