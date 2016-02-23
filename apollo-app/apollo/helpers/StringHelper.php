<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 */


namespace Apollo\Helpers;


/**
 * Class StringHelper
 *
 * @package Apollo\Helpers
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.5
 */
class StringHelper
{

    /**
     * Returns the capitalized string
     *
     * @param string $string
     * @return string
     * @since 0.0.3
     */
    public static function capitalize($string)
    {
        return ucfirst(strtolower($string));
    }

    /**
     * Converts lisp-case to PascalCase
     *
     * @param string $string
     * @return string
     * @since 0.0.4
     */
    public static function lispCaseToPascalCase($string)
    {
        $parts = explode('-', $string);
        $string = '';
        foreach ($parts as $part) {
            $string .= self::capitalize($part);
        }
        return $string;
    }

    /**
     * Checks if the supplied $string begins with a certain substring $replace, if yes removes said
     * substring from the original string. Case-insensitive unless $case is set to true.
     *
     * @param string $string
     * @param string $replace
     * @param bool $case
     * @return string
     * @since 0.0.1
     */
    public static function stripBeginning($string, $replace, $case = false)
    {

        $pattern = '/^' . preg_quote($replace, '/') . '/' . ($case ? '' : 'i');

        return preg_replace($pattern, '', $string);

    }

    /**
     * Checks if the supplied $string ends with a certain substring $replace, if yes removes said
     * substring from the original string. Case-insensitive unless $case is set to true.
     *
     * @param string $string
     * @param string $replace
     * @param bool $case
     * @return string
     * @since 0.0.2
     */
    public static function stripEnd($string, $replace, $case = false)
    {

        $pattern = '/' . preg_quote($replace, '/') . '$/' . ($case ? '' : 'i');

        return preg_replace($pattern, '', $string);

    }

    /**
     * Returns the integer as a string with leading zeros
     *
     * @param int $integer
     * @param int $amount
     * @return string
     * @since 0.0.5
     */
    public static function leadingZeros($integer, $amount = 5)
    {
        return sprintf('%0' . $amount . 'd', $integer);
    }

}