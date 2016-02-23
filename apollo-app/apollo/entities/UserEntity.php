<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
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
 * @version 0.0.3
 * @Entity @Table(name="users")
 */
class UserEntity
{
    /**
     * Unique user ID
     * @Id @Column(type="integer") @GeneratedValue
     * @var int
     */
    protected $id;

    /**
     * Name of the user
     * @Column(type="string")
     * @var string
     */
    protected $name;

    /**
     * Unique user email
     * @Column(type="string")
     * @var string
     */
    protected $email;

    /**
     * User password hash
     * @Column(type="string")
     * @var string
     */
    protected $password;

    /**
     * ID of the organisation that user belongs to
     * @ManyToOne(targetEntity="OrganisationEntity")
     * @var int
     */
    protected $organisation;

    /**
     * Boolean indicating if the user is an admin
     * @Column(type="boolean")
     * @var bool
     */
    protected $is_admin;

    /**
     * Date that the user has registered on
     * @Column(type="datetime")
     * @var DateTime
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
     * @return mixed
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }

    /**
     * @param mixed $organisation
     */
    public function setOrganisation($organisation)
    {
        $this->organisation = $organisation;
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