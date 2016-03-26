<?php
/**
 * Constants file
 *
 * All Apollo application constants are defined in this file
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.1.2
 */

/**
 * Load config file if it exists
 * @since 0.1.0
 */
if(file_exists(__DIR__ . '/Config.php')) {
    require_once __DIR__ . '/Config.php';
}

/**
 * Application name
 * @since 0.0.9
 */
define('APP_NAME', 'Apollo');

/**
 * The base url of the website with a trailing slash "/".
 * @since 0.1.0 Now points to localhost by default
 * @since 0.0.4 Added auto-detection
 * @since 0.0.1
 */
define('BASE_URL_AUTO_DETECT', true);
define('BASE_URL', BASE_URL_AUTO_DETECT ? "http://$_SERVER[HTTP_HOST]/" : 'http://localhost/');

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
 * @since 0.1.0 Now checks if the values has been predefined
 * @since 0.0.2
 */
defined('DB_HOST') OR define('DB_HOST', '127.0.0.1');
defined('DB_NAME') OR define('DB_NAME', 'apollo');
defined('DB_USER') OR define('DB_USER', 'root');
defined('DB_PASS') OR define('DB_PASS', '');

/**
 * Constants for fields IDs
 * @since 0.1.2 Added pseudo-IDs for the name (to be used in API)
 * @since 0.1.1
 */
define('FIELD_GIVEN_NAME', -1);
define('FIELD_MIDDLE_NAME', -2);
define('FIELD_LAST_NAME', -3);
define('FIELD_RECORD_NAME', 1);
define('FIELD_START_DATE', 2);
define('FIELD_END_DATE', 3);
define('FIELD_EMAIL', 4);
define('FIELD_PHONE', 5);
define('FIELD_ADDRESS', 6);