<?php
/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 */

namespace Apollo\Components;
use Exception;


/**
 * Class GlobalWebManager
 *
 * @package Apollo\Components
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @version 0.0.1
 */
class GlobalWebManager
{
    /**
     * @param Exception $e
     * @since 0.0.1
     */
    public static function printExceptionToUser(Exception $e) {
        self::printHappyException($e);
        if(IS_DEVMODE){
            self::printHelpMessage($e);
        } else if(method_exists($e, "isUserFriendly")) {
            $friendlyE = new UserFriendlyException($e->getMessage(), 0, $e);
            self::printHalfHeartedExplanation($friendlyE);
        } else {
            $friendlyE = new UserFriendlyException("to be honest, we're not quite sure...", 0, $e);
            self::printHalfHeartedExplanation($friendlyE);
        }
    }

    /**
     * @since 0.0.1
     */
    private function printHappyException(Exception $e) {
        echo ("<h1>something went wrong</h1> 
But fear not, help is (hopefully) on the way (that is, if you called for help...)
Tell the help people, that you get an \"exception\"");
    }

    /**
     * @since 0.0.1
     */
    private function printHalfHeartedExplanation(UserFriendlyException $e) {
        echo("<br>Roughly speaking, this went wrong: <br>");
        echo($e->getMessage());
    }

    /**
     * @param Exception $e
     * @since 0.0.1
     */
    private function printHelpMessage(Exception $e) {
        echo("<br><br>Here is stuff to make the people happy that resolve the problem:<br><br> ");
        echo($e->getTraceAsString());
    }
}