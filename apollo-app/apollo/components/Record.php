<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */


namespace Apollo\Components;


/**
 * Class Record
 *
 * @package Apollo\Components
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.1
 */
class Record
{
    /**
     * Namespace of entity class
     * @var string
     */
    private static $entityNamespace = 'Apollo\\Entities\\RecordEntity';

    /**
     * @return string
     */
    public static function getEntityNamespace()
    {
        return self::$entityNamespace;
    }
}