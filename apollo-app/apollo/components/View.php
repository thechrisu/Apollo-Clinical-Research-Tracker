<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */


namespace Apollo\Components;

use Illuminate\View\Factory;
use Philo\Blade\Blade;

/**
 * Class View
 * @package Apollo\Components
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @see https://github.com/PhiloNL/Laravel-Blade
 * @version 0.0.3
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

    /**
     * Shorthand for rendering a page with an optional title, breadcrumbs and parameters
     *
     * @param string $page
     * @param string $title
     * @param array $breadcrumbs
     * @param array $parameters
     * @since 0.0.3 Finally made use of the getView() method
     * @since 0.0.2
     */
    public static function render($page, $title = null, $breadcrumbs = null, $parameters = [])
    {
        $parameters['title'] = $title;
        $parameters['breadcrumbs'] = $breadcrumbs;
        echo self::getView()->make($page, $parameters)->render();
    }
}