<?php
/**
 * Apollo application entry script file
 *
 * This file load the bootstrap for namespace autoloading and
 * creates an instance of the Apollo object and starts the application.
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 * @version 0.0.2
 */


require_once '../apollo/Bootstrap.php';
use Apollo\Apollo;
use Apollo\Controllers\ExceptionDelegationController;
use Apollo\Components\UserFriendlyException;

try {
    Apollo::getInstance()->start();
} catch (Exception $e) {
    ExceptionDelegationController::delegateException($e);
} catch (Error $e) {
    $friendlyE = new UserFriendlyException("A critical error occurred.", 0, $e);
    ExceptionDelegationController::delegateException($friendlyE);
}
