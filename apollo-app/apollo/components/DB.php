<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 */


namespace Apollo\Components;
use Apollo\Helpers\StringHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Setup;


/**
 * Class DB
 *
 * @package Apollo\Components
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.1
 */
class DB
{
    /**
     * Variable holding Doctrine's entity manager, which will be the main contact point
     * with the database from other classes
     * @var EntityManager
     */
    private static $entityManager;

    /**
     * Function that returns an instance of the entity manager from the singleton class.
     * If a static instance does not exist yet, a new one is created.
     *
     * @return EntityManager
     * @throws ORMException
     * @since 0.0.1
     */
    public static function getEntityManager()
    {
        if(isset(self::$entityManager)) {
            return self::$entityManager;
        } else {
            $isDevMode = true;
            $config = Setup::createAnnotationMetadataConfiguration(array(APP_DIR . DOCTRINE_ENTITIES_PATH, $isDevMode));
            $conn = array(
                'driver'   => 'pdo_mysql',
                'host'     => DB_HOST,
                'dbname'   => DB_NAME,
                'user'     => DB_USER,
                'password' => DB_PASS
            );
            self::$entityManager = EntityManager::create($conn, $config);
            return self::$entityManager;
        }
    }
}