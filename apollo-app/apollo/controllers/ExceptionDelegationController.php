<?php
/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 */

namespace Apollo\Controllers;
use Apollo\Components\BrowserExceptionPrinter;
use Exception;


/**
 * Class ExceptionDelegationController
 *
 * Redirects exceptions
 * @package Apollo\Controllers
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @version 0.0.1
 * TODO add email notifications of exceptions (?) --> EmailExceptionPrinter
 */
class ExceptionDelegationController
{
    /**
     * Function to reroute the exception to where it has to be dealt with.
     * Intentionally left in, although not much code, since in a more advanced, high-demand application, 
     * more sophisticated exception redirection is necessary
     * @param Exception $e
     */
    public static function delegateException(Exception $e){
        $browserPrinter = new BrowserExceptionPrinter();
        $browserPrinter->printHappyExceptionToUser($e);
    }
}