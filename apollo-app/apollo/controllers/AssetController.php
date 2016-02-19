<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */


namespace Apollo\Controllers;

use Apollo\Apollo;
use finfo;


/**
 * Class AssetController
 *
 * Serves JavaScript, CSS and various image files.
 * Note that this controller does not allow access to SASS source code.
 *
 * @package Apollo\Controllers
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.1
 */
class AssetController extends GenericController
{
    /**
     * Since no actions nor parameters are specified the request is malformed, hence return
     * the 400 Bad Request status code.
     *
     * @since 0.0.1
     */
    public function index()
    {
        Apollo::getInstance()->getRequest()->error(400, 'Bad Request');
    }

    /**
     * Specifies Css, Img and Js actions as the ones accepting an arbitrary amount of arguments
     *
     * @return array
     * @since 0.0.1
     */
    public function arbitraryArgumentsActions()
    {
        //TODO Tim: No longer needed, might wanna remove
        return [
            'Css',
            'Img',
            'Js',
        ];
    }

    /**
     * Same behaviour as index(), except this time an invalid action has been specified
     *
     * @since 0.0.1
     */
    public function notFound()
    {
        Apollo::getInstance()->getRequest()->error(400, 'Bad Request: Action you have requested does not exist.');
    }

    /**
     * Calls serveFrom() on directory "css"
     *
     * @since 0.0.1
     */
    public function actionCss() {
        $this->serveFrom('css', ['css']);
    }

    /**
     * Calls serveFrom() on directory "img"
     *
     * @since 0.0.1
     */
    public function actionImg() {
        $this->serveFrom('img', ['png', 'jpeg', 'jpg', 'svg']);
    }

    /**
     * Calls serveFrom() on directory "js"
     *
     * @since 0.0.1
     */
    public function actionJs() {
        $this->serveFrom('js', ['js']);
    }

    /**
     * Serves a file in request parameters from a certain directory relative to the "assets" folder.
     * If $allowed_extensions is an empty array then allow all extensions.
     *
     * @param string $dir
     * @param array $allowed_extensions
     */
    private function serveFrom($dir, $allowed_extensions = []) {
        $params = Apollo::getInstance()->getRequest()->getParameters();
        $params_dir = implode('/', $params);
        $file_name = $params[count($params) - 1];
        $file_name_parts = explode('.', $file_name);
        $extension = count($file_name_parts) > 1 ? strtolower($file_name_parts[count($file_name_parts) - 1]) : '';
        if(!empty($dir)) {
            $dir .= '/';
        }
        if(count($allowed_extensions) == 0 || in_array($extension, $allowed_extensions)) {
            $path = ASSET_DIR . $dir . $params_dir;
            if(file_exists($path)) {
                switch($extension){
                    case 'css':
                        $mime_type = 'text/css';
                        break;
                    case 'js':
                        $mime_type = 'application/javascript';
                        break;
                    default:
                        $file_info = new finfo();
                        $mime_type = $file_info->file($path, FILEINFO_MIME_TYPE);
                        break;
                }
                //TODO Tim: Add headers for caching and other stuff
                header('Content-Type: ' . $mime_type);
                readfile($path);
            } else {
                Apollo::getInstance()->getRequest()->error(404, 'The requested file was not found.');
            }
        } else {
            Apollo::getInstance()->getRequest()->error(400, 'Bad Request: The file your requested has an invalid extension.');
        }
    }
}