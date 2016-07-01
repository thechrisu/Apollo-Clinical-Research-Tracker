<?php
/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 */

namespace Apollo\Components;


use Exception;

/**
 * Class UserFriendlyException
 *
 * @package Apollo\Components
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @version 0.0.1
 * Just an empty class that we check against when displaying an exception to the user.
 */
class UserFriendlyException extends Exception
{
    public function isUserFriendly() {
        return true;
    }
}