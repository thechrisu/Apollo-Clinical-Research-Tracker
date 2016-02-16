<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 */


namespace Apollo\Components;

use Illuminate\View\Factory;
use Philo\Blade\Blade;

/**
 * Class View
 *
 * @package Apollo\Components
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.1
 */
class View
{
    /**
     * Variable holding an instance of Blade for templating
     * @var Blade
     */
    private static $blade;

    /**
     * Function that returns an instance of the Blade object from the singleton class.
     * If a static instance does not exist yet, a new one is created.
     *
     * @return Blade
     * @since 0.0.1
     */
    public static function getBlade()
    {
        if (isset(self::$blade)) {
            return self::$blade;
        } else {
            $views = APP_DIR . BLADE_VIEWS_PATH;
            $cache = APP_DIR . BLADE_CACHE_PATH;
            self::$blade = new Blade($views, $cache);
            return self::$blade;
        }
    }

    /**
     * Function that returns a View object, a shorthand to avoid using the getBlade()->... syntax
     *
     * @return Factory
     * @since 0.0.1
     */
    public static function getView()
    {
        return self::getBlade()->view();
    }
}