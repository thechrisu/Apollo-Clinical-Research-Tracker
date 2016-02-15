<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 * @version 0.0.2
 */


namespace Apollo\Helpers;


/**
 * Class URLHelper
 *
 * Contains various function related to URL parsing and composing
 *
 * @package Apollo\Helpers
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @since 0.0.1
 */
class URLHelper
{

    /**
     * Method to split the url into parts, stripping the base url beforehand.
     * If $base is not specified default app base is used.
     *
     * @param string $url
     * @param string $base
     * @return array
     * @since 0.0.1
     */
    public static function split($url, $base = BASE_URL) {

        $url = self::stripBase($url, $base);
        $url = StringHelper::stripEnd($url, '/');

        return explode('/', $url);

    }


    /**
     * Method to strip the base url or the leading slash. If the custom base is not specified
     * the default application base url is used. NOTE: Case-insensitive for base value!
     *
     * @param string $url
     * @param string $base
     * @return string
     * @since 0.0.2 Added the $base parameter
     * @since 0.0.1
     */
    public static function stripBase($url, $base = BASE_URL) {

        $url = StringHelper::stripBeginning($url, $base);
        $url = StringHelper::stripBeginning($url, '/');

        return $url;

    }

}