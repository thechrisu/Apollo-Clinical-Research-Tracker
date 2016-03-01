<?php
/**
 * Main Apollo application class file
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */


namespace Apollo;

use Apollo\Components\Request;
use Apollo\Components\User;
use Apollo\Controllers\GenericController;
use ReflectionMethod;


/**
 * Class Apollo
 *
 * Main Apollo class responsible for creating the request object and directing it to
 * the appropriate controller.
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.1.0
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
     * Initialises the application by checking if the user if authorised, if the request is valid
     * and redirecting the user to the default page if the index is accessed.
     *
     * All request parsing is done in a separate function, parseRequest();
     *
     * @since 0.1.0 Now uses the DEFAULT_CONTROLLER constant
     * @since 0.0.9 Moved request parsing to parseRequest() function, redirect to default now raises 301 code
     * @since 0.0.8 No longer provides arbitrary amount of parameters, use the Request instance instead
     * @since 0.0.7 Made index() in RecordController the default action
     * @since 0.0.6 Now uses notFound() function from the controller instead of custom error
     * @since 0.0.5 Error pages are now rendered using the Request class
     * @since 0.0.4 Improved parameter to argument conversion to allow arbitrary amount of arguments
     * @since 0.0.3 Added conversion from request parameters to function arguments
     * @since 0.0.2 Proper controller/action parsing
     * @since 0.0.1
     */
    public function start()
    {
        if ($this->user->isGuest()) {
            if ($this->request->getController() != 'User' || $this->request->getAction() != 'SignIn') {
                $this->request->sendTo('user/sign-in/' . (empty($this->request->getStrippedUrl()) ? '' : '?return=' . $this->request->getStrippedUrl()), false);
            }
        }
        if ($this->request->isIndex()) {
            http_response_code(301);
            $this->request->sendTo(DEFAULT_CONTROLLER);
        }
        if (!$this->getRequest()->isValid()) {
            $this->request->error(400, 'The requested URL is malformed.');
        }
        $this->parseRequest();
    }

    /**
     * Parses the request by checking if the requested Controller and Action exist,
     * as well as passing necessary arguments to the functions.
     *
     * @since 0.0.9
     */
    private function parseRequest()
    {
        $request = $this->getRequest();
        $controller_name = $request->getController();
        $controller_file_path = __DIR__ . '/controllers/' . $controller_name . 'Controller.php';
        if (file_exists($controller_file_path)) {
            $controller_namespace = 'Apollo\\Controllers\\' . $controller_name . 'Controller';
            /** @var GenericController $controller */
            $controller = new $controller_namespace();
            $method = 'action' . $request->getAction();
            if (!$request->hasAction()) {
                $controller->index();
            } elseif (method_exists($controller, $method)) {
                $this->performAction($controller_namespace, $controller, $request, $method);
            } else {
                $controller->notFound();
            }
        } else {
            $this->request->error(404, 'Page not found! (Controller <b>' . $controller_name . '</b> not found)');
        }
    }

    /**
     * Calls the appropriate method in the appropriate Controller to carry out the appropriate action, specified by the URL
     * @param $controller_namespace
     * @param $controller
     * @param $request
     * @param $method
     */
    private function performAction($controller_namespace, $controller, $request, $method)
    {
        $arguments_expected = (new ReflectionMethod($controller_namespace, $method))->getNumberOfParameters();
        $arguments = [];
        $request_parameters = $request->getParameters();
        for ($i = 0; $i < $arguments_expected; $i++) {
            if ($i >= count($request_parameters)) break;
            $arguments[$i] = isset($request_parameters[$i]) ? $request_parameters[$i] : null;
        }
        call_user_func_array([$controller, $method], $arguments);
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