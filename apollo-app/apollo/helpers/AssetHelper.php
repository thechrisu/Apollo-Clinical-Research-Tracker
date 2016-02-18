<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license https://opensource.org/licenses/mit-license.php MIT License
 */


namespace Apollo\Helpers;


/**
 * Class AssetHelper
 *
 * @package Apollo\Helpers
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.1
 */
class AssetHelper
{
    /**
     * Prints out the absolute url of the stylesheet based on the path specified
     *
     * @param string $path
     * @since 0.0.1
     */
    public static function css($path)
    {
        echo self::getCss($path);
    }

    /**
     * Calls getAssetFrom on folder "css"
     *
     * @param string $path
     * @return string
     * @since 0.0.1
     */
    public static function getCss($path)
    {
        return self::getAssetFrom('css', $path . '.css');
    }

    /**
     * Prints out the absolute url of the image based on the path specified
     *
     * @param string $path
     * @since 0.0.1
     */
    public static function image($path)
    {
        echo self::getImg($path);
    }

    /**
     * Calls getAssetFrom on folder "img"
     *
     * @param string $path
     * @return string
     * @since 0.0.1
     */
    public static function getImg($path)
    {
        return self::getAssetFrom('img', $path);
    }

    /**
     * Prints out the absolute url of the script based on the path specified
     *
     * @param string $path
     * @since 0.0.1
     */
    public static function js($path)
    {
        echo self::getJs($path);
    }

    /**
     * Calls getAssetFrom on folder "js"
     *
     * @param string $path
     * @return string
     * @since 0.0.1
     */
    public static function getJs($path)
    {
        return self::getAssetFrom('js', $path . '.js');
    }

    /**
     * Returns the absolute url of the asset given by the path
     *
     * @param string $path
     * @return string
     * @since 0.0.1
     */
    private static function getAssetFrom($folder, $path) {
        return ASSET_BASE_URL . $folder . '/' . URLHelper::stripBase($path);
    }
}