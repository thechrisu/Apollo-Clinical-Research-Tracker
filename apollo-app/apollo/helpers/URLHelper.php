<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 */


namespace Apollo\Helpers;


/**
 * Class URLHelper
 *
 * Contains various function related to URL parsing and composing
 *
 * @package Apollo\Helpers
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.5
 */
class URLHelper
{
    /**
     * Returns the absolute url to a page with an optional trailing slash.
     *
     * @param string $url
     * @param bool $trailing_slash
     * @return string
     */
    public static function url($url, $trailing_slash = true)
    {
        $url = URLHelper::stripBase($url);
        if ($trailing_slash) {
            $url = StringHelper::stripEnd($url, '/');
            $url .= '/';
        }
        return BASE_URL . $url;
    }

    /**
     * Method to split the url into parts, stripping the base url and the trailing
     * slash beforehand. If $base is not specified default app base is used.
     *
     * @param string $url
     * @param string $base
     * @return array
     * @since 0.0.4 Added a check before stripEnd()
     * @since 0.0.2 If the $url is empty then return an empty array
     * @since 0.0.1
     */
    public static function split($url, $base = BASE_URL)
    {
        $url = self::stripBase($url, $base);
        if($url != '/') {
            $url = StringHelper::stripEnd($url, '/');
        }
        return empty($url) ? [] : explode('/', $url);
    }


    /**
     * Method to strip the base url or the leading slash. If the custom base is not specified
     * the default application base url is used. NOTE: Case-insensitive for base value!
     *
     * @param string $url
     * @param string $base
     * @return string
     * @since 0.0.3 Removed dependency on StringHelper
     * @since 0.0.2 Added the $base parameter
     * @since 0.0.1
     */
    public static function stripBase($url, $base = BASE_URL)
    {
        $pattern = '/^(' . preg_quote($base, '/') . '|\\/)/i';
        return preg_replace($pattern, '', $url);
    }

}