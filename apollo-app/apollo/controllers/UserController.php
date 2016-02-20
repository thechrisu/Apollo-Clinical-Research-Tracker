<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */


namespace Apollo\Controllers;

use Apollo\Apollo;
use Apollo\Components\DB;
use Apollo\Components\Session;
use Apollo\Components\View;
use Apollo\Entities\UserEntity;
use Doctrine\ORM\EntityRepository;


/**
 * Class UserController
 *
 * @package Apollo\Controllers
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.3
 */
class UserController extends GenericController
{
    /**
     * Default User action, simply redirects to sign in screen
     *
     * @since 0.0.1
     */
    public function index()
    {
        $query = Apollo::getInstance()->getRequest()->getQuery();
        Apollo::getInstance()->getRequest()->sendTo('user/sign-in/' . ($query ? '?' . $query : ''));
    }

    /**
     * Action to handle the sign in, both the view and the POST request
     *
     * @since 0.0.1
     */
    public function actionSignIn()
    {
        if (!Apollo::getInstance()->getUser()->isGuest()) {
            Apollo::getInstance()->getRequest()->sendToIndex();
        }

        $data = [
            'error' => null
        ];

        if (isset($_POST['email']) && isset($_POST['password'])) {
            /**
             * @var EntityRepository $user_repository
             */
            $user_repository = DB::getEntityManager()->getRepository('\\Apollo\\Entities\\UserEntity');
            /**
             * @var UserEntity $user
             */
            $user = $user_repository->findOneBy(['email' => strtolower($_POST['email'])]);
            if ($user != null) {
                if (password_verify($_POST['password'], $user->getPassword())) {
                    //TODO: Perhaps make this more secure?
                    Session::set('fingerprint', Session::getFingerprint(md5($user->getPassword())));
                    Session::set('user_id', $user->getId());
                    if (isset($_GET['return'])) {
                        Apollo::getInstance()->getRequest()->sendTo($_GET['return'], false);
                    } else {
                        Apollo::getInstance()->getRequest()->sendToIndex();
                    }
                } else {
                    $data = [
                        'error' => 'Invalid email/password combination.'
                    ];
                }
            } else {
                $data = [
                    'error' => 'Invalid email/password combination.'
                ];
            }
        }

        echo View::getView()->make('user.sign-in', ['data' => $data])->render();
    }

    /**
     * Destroys the session and redirects the user to the index page
     *
     * @since 0.0.2
     */
    public function actionSignOut()
    {
        Session::destroy();
        Apollo::getInstance()->getRequest()->sendToIndex();
    }

    /**
     * Deals with user settings
     *
     * @since 0.0.3
     */
    public function actionSettings() {
        $breadcrumbs = [
            ['User', null, false],
            ['Settings', null, true]
        ];
        View::render('user.settings', 'Settings', $breadcrumbs);
    }
}