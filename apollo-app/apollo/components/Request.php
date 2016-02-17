<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */


namespace Apollo\Components;

use Apollo\Helpers\StringHelper;
use Apollo\Helpers\URLHelper;


/**
 * Class Request
 * @package Apollo\Components
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.5
 */
class Request
{

    /**
     * String containing the full current url being accessed, including host name
     * @var string
     */
    private $url;

    /**
     * Same as $url but without base url and slash "/" in the beginning
     * @var string
     */
    private $stripped_url;

    /**
     * Query part of the url without the leading question mark "?"
     * @var string
     */
    private $query;

    /**
     * Array storing the parts of the URL with the base
     * @var array
     */
    private $url_parts;

    /**
     * Name of the controller that is being accessed
     * @var string
     */
    private $controller;

    /**
     * Name of the action within said controller
     * @var string
     */
    private $action;

    /**
     * Array of parameters in the request
     * @var array
     */
    private $parameters = [];

    /**
     * Determines whether the request is valid
     * @var bool
     */
    private $valid = true;

    /**
     * Determines whether the request is accessing the index page
     * @var bool
     */
    private $index = false;

    /**
     * Request constructor.
     *
     * Parses the URL producing a request. $url parameter must only be used for debugging purposes.
     * Only alphanumeric characters and dash "-" are accepted as valid values.
     *
     * @param string $url
     * @since 0.0.4 Now properly converts lisp-case to PascalCase
     * @since 0.0.3 Added query support
     * @since 0.0.2 Added $url parameter
     * @since 0.0.1
     */
    public function __construct($url = null)
    {
        $this->url = $url ?: "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $this->stripped_url = URLHelper::stripBase($this->url);
        $query = explode('?', $this->url);
        $this->query = count($query) > 1 ? $query[1] : null;
        $this->url_parts = URLHelper::split($query[0]);
        //TODO: Might wanna refactor this code . . .
        for($i = 0; $i < count($this->url_parts); $i++) {
            $url_part = $this->url_parts[$i];
            switch($i) {
                case 0:
                    if(preg_match('/^[A-Za-z0-9\-]+$/', $url_part) === 1) {
                        $this->controller = StringHelper::lispCaseToPascalCase($url_part);
                    } else {
                        $this->valid = false;
                        break 2;
                    }
                    break;
                case 1:
                    if(preg_match('/^[A-Za-z0-9\-]+$/', $url_part) === 1) {
                        $this->action = StringHelper::lispCaseToPascalCase($url_part);
                    } else {
                        $this->valid = false;
                        break 2;
                    }
                    break;
                default:
                    if(preg_match('/^[A-Za-z0-9\-]+$/', $url_part) === 1) {
                        array_push($this->parameters, $url_part);
                    } else {
                        $this->valid = false;
                        break 2;
                    }
                    break;
            }
        }
        if(empty($this->controller) && $this->valid) {
            $this->index = true;
        }
    }

    /**
     * Redirects the user to specified url within the app. If $trailing_slash is set to false
     * the trailing slash will not be added, but existing trailing slashes won't be removed
     *
     * @param string $url
     * @param bool $trailing_slash
     * @since 0.0.2
     */
    public function sendTo($url, $trailing_slash = true)
    {
        $url = URLHelper::stripBase($url);
        if ($trailing_slash) {
            $url = StringHelper::stripEnd($url, '/');
            $url .= '/';
        }
        header('Location: ' . BASE_URL . $url);
        die();
    }

    /**
     * Sends the request to the index page
     *
     * @since 0.0.2
     */
    public function sendToIndex()
    {
        $this->sendTo('', false);
    }

    public function error($status_code, $message)
    {
        http_response_code($status_code);
        echo View::getView()->make('error', ['status_cide' => $status_code, 'message' => $message])->render();
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getStrippedUrl()
    {
        return $this->stripped_url;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return array
     */
    public function getUrlParts()
    {
        return $this->url_parts;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return boolean
     */
    public function isValid()
    {
        return $this->valid;
    }

    /**
     * @return boolean
     */
    public function isIndex()
    {
        return $this->index;
    }

    /**
     * @return boolean
     */
    public function hasAction() {
        return !empty($this->action);
    }

}