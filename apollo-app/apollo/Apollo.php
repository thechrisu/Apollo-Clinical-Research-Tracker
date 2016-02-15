<?php
/**
 * Main Apollo application class file
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 * @version 0.0.1
 */


namespace Apollo;
use Apollo\Components\Request;


/**
 * Class Apollo
 *
 * Main Apollo class responsible for creating the request object and directing it to
 * the appropriate controller.
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @since 0.0.1
 */
class Apollo
{

    /**
     * Object containing the request information
     *
     * @var Request
     */
    private $request;

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
    }

    /**
     * Initialises the application by parsing the request
     * @access public
     * @since 0.0.1
     */
    public function start()
    {


    }

}