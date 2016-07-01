<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */


namespace Apollo\Components;
use Apollo\Helpers\GlobalWebManager;
use Apollo\Helpers\StringHelper;
use Doctrine\DBAL\Driver\PDOException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Setup;
use Exception;
use Symfony\Component\Finder\Glob;


/**
 * Class DB
 *
 * @package Apollo\Components
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.2
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
     * @since 0.0.2 Fixed a typo in the directory name
     * @since 0.0.1
     */
    public static function getEntityManager()
    {
        if (isset(self::$entityManager)) {
            return self::$entityManager;
        } else {
            $config = Setup::createAnnotationMetadataConfiguration([APP_DIR . DOCTRINE_ENTITIES_PATH], IS_DEVMODE);
            $conn = array(
                'driver' => 'pdo_mysql',
                'host' => DB_HOST,
                'dbname' => DB_NAME,
                'user' => DB_USER,
                'password' => DB_PASS
            );
            self::$entityManager = EntityManager::create($conn, $config);
            return self::$entityManager;
        }
    }
}