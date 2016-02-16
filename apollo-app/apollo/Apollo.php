<?php
/**
 * Main Apollo application class file
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 */


namespace Apollo;
use Apollo\Components\Request;
use Apollo\Components\User;
use Apollo\Components\View;


/**
 * Class Apollo
 *
 * Main Apollo class responsible for creating the request object and directing it to
 * the appropriate controller.
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.2
 */
class Apollo
{
    /**
     * Instance of the Apollo class to act as a singleton
     * @var Apollo
     */
    private static $instance;

    /**
     * Object containing the request information
     *
     * @var Request
     */
    private $request;

    /**
     * Object containing all user information
     *
     * @var User
     */
    private $user;

    /**
     * Apollo constructor.
     *
     * Populates the class variables
     *
     * @since 0.0.1
     */
    public function __construct()
    {
        $this->request = new Request();
        $this->user = new User();
    }

    /**
     * Function to create an instance of Apollo
     * @since 0.0.2
     */
    public static function prepare() {
        self::$instance = new Apollo();
    }

    /**
     * Initialises the application by parsing the request
     * @access public
     * @since 0.0.1
     */
    public function start()
    {
        if($this->user->isGuest()) {
            if($this->request->getController() != 'User') {
                $this->request->sendTo('user/signin/?return=' . $this->request->getUrl(), false);
            }
        }
        echo View::getView()->make('error', ['error' => '404', 'error_message' => 'Page Not Found!']);
    }

    /**
     * @return Apollo
     */
    public static function getInstance()
    {
        return self::$instance;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}