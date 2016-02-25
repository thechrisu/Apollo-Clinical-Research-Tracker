<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */


namespace Apollo\Controllers;

use Apollo\Apollo;
use Apollo\Helpers\FileHelper;
use Apollo\Helpers\StringHelper;


/**
 * Class AssetController
 *
 * Serves JavaScript, CSS and various image files.
 * Note that this controller does not allow access to SASS source code.
 *
 * @package Apollo\Controllers
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.3
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
     * @since 0.0.3 Removed dependency on fileinfo module
     * @since 0.0.1
     */
    private function serveFrom($dir, $allowed_extensions = []) {
        $params = Apollo::getInstance()->getRequest()->getParameters();
        $params_dir = implode('/', $params);
        $extension = FileHelper::extension($params[count($params) - 1]);
        $mime_type = FileHelper::mimeType($extension);
        if(!empty($dir)) {
            $dir = StringHelper::stripEnd($dir, '/');
            $dir .= '/';
        }
        if((count($allowed_extensions) == 0 || in_array($extension, $allowed_extensions)) && $mime_type != null) {
            $path = ASSET_DIR . $dir . $params_dir;
            if(file_exists($path)) {
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