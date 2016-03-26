<?php
/**
 * Application config file
 *
 * NOTE: Rename this file from Config.example.php to Config.php before using
 *
 * Config.php is not necessary but it is a safe way to input confidential details into the app
 * without the risk of them getting pushed to the repo since it's ignored by Git.
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license https://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.1
 */

/**
 * Constants for database connection, self-explanatory
 * @since 0.0.1
 */
defined('DB_HOST') OR define('DB_HOST', 'kawaiidesu.me');
defined('DB_NAME') OR define('DB_NAME', 'apollo');
defined('DB_USER') OR define('DB_USER', 'group30');
defined('DB_PASS') OR define('DB_PASS', 'group30');
