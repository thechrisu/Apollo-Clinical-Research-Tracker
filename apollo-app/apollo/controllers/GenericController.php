<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */


namespace Apollo\Controllers;


/**
 * Class GenericController
 *
 * @package Apollo\Controllers
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.2
 */
abstract class GenericController
{
    /**
     * Default function that is called if no action is specified
     *
     * @since 0.0.2 Changed to abstract function since GenericController is no longer an interface
     * @since 0.0.1
     */
    abstract public function index();

    /**
     * Function that returns the names of actions that accept an arbitrary amount of arguments
     *
     * @return array
     */
    public function arbitraryArgumentsActions() {
        return [];
    }
}