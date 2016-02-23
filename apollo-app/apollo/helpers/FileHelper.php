<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license https://opensource.org/licenses/mit-license.php MIT License
 */


namespace Apollo\Helpers;


/**
 * Class FileHelper
 *
 * @package Apollo\Helpers
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.1
 */
class FileHelper
{
    /**
     * Returns the filename based on path
     *
     * @param string $path
     * @return string
     * @since 0.0.1
     */
    public static function fileName($path)
    {
        $path_parts = explode('/', $path);
        $name = count($path_parts) > 1 ? strtolower($path_parts[count($path_parts) - 1]) : $path_parts[0];
        return $name;
    }

    /**
     * Returns extension based on the filename or path.
     *
     * @param string $path
     * @return string
     * @since 0.0.1
     */
    public static function extension($path)
    {
        $name = self::fileName($path);
        $name_parts = explode('.', $name);
        $extension = count($name_parts) > 1 ? strtolower($name_parts[count($name_parts) - 1]) : '';
        return $extension;
    }

    /**
     * Returns the mime type of the file based on its extension
     *
     * @param string $extension
     * @return string
     * @since 0.0.1
     */
    public static function mimeType($extension)
    {
        $mime_type = null;
        switch($extension) {
            case 'css':
                $mime_type = 'text/css';
                break;
            case 'gif':
                $mime_type = 'image/gif';
                break;
            case 'jpeg':
                $mime_type = 'image/jpeg';
                break;
            case 'jpg':
                $mime_type = 'image/jpeg';
                break;
            case 'js':
                $mime_type = 'application/javascript';
                break;
            case 'png':
                $mime_type = 'image/png';
                break;
        }
        return $mime_type;
    }
}