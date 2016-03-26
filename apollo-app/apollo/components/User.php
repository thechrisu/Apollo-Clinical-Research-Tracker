<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */


namespace Apollo\Components;

use Apollo\Entities\OrganisationEntity;
use Apollo\Entities\UserEntity;
use Apollo\Helpers\StringHelper;


/**
 * Class User
 *
 * This class holds an instance of UserEntity to abstract all actions on the database. Additionally,
 * it holds functions related to authorisation and user permissions.
 *
 * @package Apollo\Components
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.1.0
 */
class User extends DBComponent
{
    /**
     * Namespace of entity class
     * @var string
     */
    protected static $entityNamespace = 'Apollo\\Entities\\UserEntity';

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
     * @param int $id
     * @since 0.1.0 Now allows creating user object by id
     * @since 0.0.1
     */
    public function __construct($id = null)
    {
        if(!empty($id)) {
            /** @var UserEntity $entity */
            $entity = self::getRepository()->find($id);
            $this->id = $id;
            $this->entity = $entity;
        } else {
            $fingerprint = Session::get('fingerprint');
            $this->id = Session::get('user_id');
            if ($fingerprint != null && $this->id != null) {
                /**
                 * @var UserEntity $temp_entity
                 */
                $temp_entity = DB::getEntityManager()->getRepository('\\Apollo\\Entities\\UserEntity')->find($this->id);
                if ($temp_entity != null && $fingerprint == Session::getFingerprint(md5($temp_entity->getPassword()))) {
                    $this->entity = $temp_entity;
                }
            }
        }
    }

    /**
     * Function to check if the user is logged in
     *
     * @return bool
     * @since 0.0.1
     */
    public function isGuest()
    {
        return $this->entity == null;
    }

    /**
     * Checks whether the user is an admin or not
     *
     * @return bool
     * @since 0.0.3
     */
    public function isAdmin()
    {
        return $this->entity->isIsAdmin();
    }

    /**
     * Returns the entity of the user
     *
     * @return UserEntity
     * @since 0.0.8
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Returns the ID of the user
     *
     * @return int
     * @since 0.0.6 Now just returns the integer
     * @since 0.0.5
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the ID of the user as a string with leading zeros
     *
     * @return string
     * @since 0.0.6
     */
    public function getDisplayId()
    {
        return StringHelper::leadingZeros($this->id);
    }

    /**
     * Returns the name of the user
     *
     * @return string
     * @since 0.0.2
     */
    public function getName()
    {
        return $this->entity->getName();
    }

    /**
     * Returns the email of the user
     *
     * @return string
     * @since 0.0.7
     */
    public function getEmail()
    {
        return $this->entity->getEmail();
    }

    /**
     * Returns organisation entity
     *
     * @return OrganisationEntity
     * @since 0.0.9
     */
    public function getOrganisation()
    {
        return $this->entity->getOrganisation();
    }

    /**
     * Returns the ID of the organisation the user belongs to
     *
     * @return int
     * @since 0.0.7
     */
    public function getOrganisationId()
    {
        return $this->entity->getOrganisation()->getId();
    }

    /**
     * Returns the ID (with leading zeros) of the organisation the user belongs to
     *
     * @return string
     * @since 0.0.7
     */
    public function getOrganisationDisplayId()
    {
        return StringHelper::leadingZeros($this->entity->getOrganisation()->getId());
    }


    /**
     * Returns the name of the organisation the user belongs to
     *
     * @return string
     * @since 0.0.4
     */
    public function getOrganisationName()
    {
        return $this->entity->getOrganisation()->getName();
    }

    /**
     * Returns the timezone of the organisation the user belongs to
     *
     * @return string
     * @since 0.0.7
     */
    public function getOrganisationTimeZone()
    {
        return $this->entity->getOrganisation()->getTimezone();
    }
}