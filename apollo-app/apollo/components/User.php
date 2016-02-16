<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 */


namespace Apollo\Components;
use Apollo\Entities\UserEntity;


/**
 * Class User
 *
 * This class holds an instance of UserEntity to abstract all actions on the database. Additionally,
 * it holds functions related to authorisation and user permissions.
 *
 * @package Apollo\Components
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.1
 */
class User
{
    /**
     * An instance of UserEntity created if the user is logged in
     * @var UserEntity
     */
    private $entity;

    /**
     * Unique user ID
     * @var int
     */
    private $id;

    /**
     * User constructor.
     * Checks if the user is logged in
     */
    public function __construct()
    {
        $fingerprint = Session::get('fingerprint');
        $this->id = Session::get('user_id');
        if($fingerprint != null && $this->id != null) {
            /**
             * @var UserEntity $temp_entity
             */
            $temp_entity = DB::getEntityManager()->getRepository('\\Apollo\\Entities\\UserEntity')->find($this->id);
            if($temp_entity != null && $fingerprint == Session::getFingerprint(md5($temp_entity->getPassword()))) {
                $this->entity = $temp_entity;
            }
        }
    }

    /**
     * Function to check if the user is logged in
     *
     * @return bool
     */
    public function isGuest() {
        return $this->entity == null;
    }
}