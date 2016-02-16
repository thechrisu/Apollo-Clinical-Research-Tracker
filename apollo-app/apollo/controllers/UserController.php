<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 */


namespace Apollo\Controllers;
use Apollo\Apollo;


/**
 * Class UserController
 *
 * @package Apollo\Components
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.1
 */
class UserController implements GenericController
{
    /**
     * Default User action, simply redirects to sign in screen
     * @since 0.0.1
     */
    public function index() {
        Apollo::getInstance()->getRequest()->sendTo('user/sign-in/');
    }
}