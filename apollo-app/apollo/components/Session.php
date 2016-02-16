<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 */


namespace Apollo\Components;


/**
 * Class Session
 *
 * @package Apollo\Components
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.1
 */
class Session
{
    /**
     * Generates a fingerprint for the user based on their IP and user agent.
     * Optional $salt can be specified to make the function a bit more secure.
     *
     * @param string $salt
     * @return string
     * @since 0.0.1
     */
    public static function getFingerprint($salt = '') {
        return md5($_SERVER['HTTP_USER_AGENT'] . $salt . $_SERVER['REMOTE_ADDR']);
    }

    /**
     * Assigns the value to a key in the session
     *
     * @param string $key
     * @param string $value
     */
    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    /**
     * Returns the value of the key or null if it is not set
     *
     * @param string$key
     * @return string
     */
    public static function get($key) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    /**
     * Destroy the session and unset all variables
     */
    public static function destroy() {
        session_unset();
        session_destroy();
    }
}