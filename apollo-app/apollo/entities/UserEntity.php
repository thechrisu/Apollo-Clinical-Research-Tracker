<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 */


namespace Apollo\Entities;
use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;


/**
 * Class UserEntity
 *
 * @package Apollo\Entities
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.2
 * @Entity @Table(name="users")
 */
class UserEntity
{
    /**
     * Unique user ID
     * @var int
     * @Id @Column(type="integer") @GeneratedValue
     */
    protected $id;

    /**
     * Name of the user
     * @var string
     * @Column(type="string")
     */
    protected $name;

    /**
     * Unique user email
     * @var string
     * @Column(type="string")
     */
    protected $email;

    /**
     * User password hash
     * @var string
     * @Column(type="string")
     */
    protected $password;

    /**
     * ID of the organisation that user belongs to
     * @var int
     * @Column(type="integer")
     */
    protected $org_id;
    //TODO Tim: Fix the ManyToOne Doctrine mapping with organisations

    /**
     * Boolean indicating if the user is an admin
     * @var bool
     * @Column(type="boolean")
     */
    protected $is_admin;

    /**
     * Date that the user has registered on
     * @var DateTime
     * @Column(type="datetime")
     */
    protected $registered_on;

    public function __construct()
    {
        $this->is_admin = false;
        $this->registered_on = new DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return int
     */
    public function getOrgId()
    {
        return $this->org_id;
    }

    /**
     * @param int $org_id
     */
    public function setOrgId($org_id)
    {
        $this->org_id = $org_id;
    }

    /**
     * @return boolean
     */
    public function isIsAdmin()
    {
        return $this->is_admin;
    }

    /**
     * @param boolean $is_admin
     */
    public function setIsAdmin($is_admin)
    {
        $this->is_admin = $is_admin;
    }

    /**
     * @return DateTime
     */
    public function getRegisteredOn()
    {
        return $this->registered_on;
    }

    /**
     * @param DateTime $registered_on
     */
    public function setRegisteredOn($registered_on)
    {
        $this->registered_on = $registered_on;
    }

}