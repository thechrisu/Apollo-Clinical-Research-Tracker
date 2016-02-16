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
use Apollo\Controllers\GenericController;
use ReflectionMethod;


/**
 * Class Apollo
 *
 * Main Apollo class responsible for creating the request object and directing it to
 * the appropriate controller.
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.3
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
    public static function prepare()
    {
        self::$instance = new Apollo();
    }

    /**
     * Initialises the application by parsing the request and directing it to an appropriate
     * controller and an appropriate action inside said controller.
     * @access public
     * @since 0.0.3 Added conversion from request parameters to function arguments
     * @since 0.0.2 Proper controller/action parsing
     * @since 0.0.1
     */
    public function start()
    {
        // Redirect guest users to sign in page
        if ($this->user->isGuest()) {
            if ($this->request->getController() != 'User' || $this->request->getAction() != 'SignIn') {
                $this->request->sendTo('user/sign-in/' . (empty($this->request->getStrippedUrl()) ? '' : '?return=' . $this->request->getStrippedUrl()), false);
            }
        }
        //TODO: Chris will most likely complain that this looks ugly, might wanna considering refactoring
        // Check that the requested controller exists
        $controller_path = __DIR__ . '/controllers/' . $this->request->getController() . 'Controller.php';
        if (file_exists($controller_path)) {
            $controller_class = 'Apollo\\Controllers\\' . $this->request->getController() . 'Controller';
            /**
             * @var GenericController $controller_instance
             */
            $controller_instance = new $controller_class();
            if (!$this->request->hasAction()) {
                $controller_instance->index();
            } else {
                $action_name = 'action' . $this->request->getAction();
                // Check that the requested action exists
                if (method_exists($controller_instance, $action_name)) {
                    // Check how many arguments the action is expecting
                    $method = new ReflectionMethod($controller_class, $action_name);
                    $arguments_expected = $method->getNumberOfParameters();
                    $arguments = [];
                    // Convert request parameters into arguments
                    for ($i = 0; $i < $arguments_expected; $i++) {
                        if (count($this->request->getParameters()) > $i)
                            $arguments[$i] = $this->request->getParameters()[$i] ?: null;
                    }
                    call_user_func_array([$controller_instance, $action_name], $arguments);
                } else {
                    $this->error('404', 'Page not found! (Action ' . $this->request->getAction() . ' not found in Controller ' . $this->request->getController() . ')');
                }
            }
        } else {
            $this->error('404', 'Page not found! (Controller ' . $this->request->getController() . ' not found)');
        }

    }

    public function error($error, $message)
    {
        echo View::getView()->make('error', ['error' => $error, 'error_message' => $message])->render();
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