<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */


namespace Apollo\Components;
use Doctrine\ORM\EntityRepository;


/**
 * Class Field
 *
 * @package Apollo\Components
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.1
 */
abstract class DBComponent
{
    /**
     * Namespace of entity class
     * @var string
     */
    protected static $entityNamespace;

    /**
     * @return EntityRepository
     */
    public static function getRepository() {
        return DB::getEntityManager()->getRepository(self::getEntityNamespace());
    }

    /**
     * @return string
     */
    public static function getEntityNamespace()
    {
        return self::$entityNamespace;
    }
}