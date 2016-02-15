<?php
/**
 * Created on 15/02/2016 at 17:12
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 * @version 0.0.1
 */


namespace Apollo\Components;
use Apollo\Helpers\URLHelper;


/**
 * Class Request
 * @package Apollo\Components
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @since 0.0.1
 */
class Request
{

    /**
     * String containing the full current url being accessed, including host name
     * @var string
     */
    public $url;

    /**
     * Same as $url but without base url and slash "/" in the beginning
     * @var string
     */
    public $stripped_url;

    /**
     * Array storing the parts of the URL with the base
     * @var array
     */
    public $url_parts;

    /**
     * Request constructor.
     *
     * Parses the URL
     *
     * @since 0.0.1
     */
    public function __construct()
    {
        $this->url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $this->stripped_url = URLHelper::stripBase($this->url);
        $this->url_parts = URLHelper::split($this->stripped_url);
    }

}