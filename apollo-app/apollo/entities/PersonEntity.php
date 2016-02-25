<?php
/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Apollo\Entities;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * Class PersonEntity
 * @package Apollo\Entities
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.2
 * @Entity @Table("people")
 */
class PersonEntity
{
    /**
     * Unique Person id
     * @Id @Column(type="integer") @GeneratedValue
     * @var int
     */
    protected $id;

    /**
     * Name of the person
     * @Column(type="string")
     * @var string
     */
    protected $given_name;

    /**
     * Middle Name of the person
     * @Column(type="string")
     * @var string
     */
    protected $middle_name;

    /**
     * Last Name of the person
     * @Column(type="string")
     * @var string
     */
    protected $last_name;


    /**
     * id of the organization the person is part of
     * @ManyToOne(targetEntity="OrganisationEntity")
     * @var int
     */
    protected $organisation;

    /**
     * determines if person is shown or not
     * @Column(type="boolean")
     * @var bool
     */
    protected $is_hidden;

    public function __construct()
    {
        $this->is_hidden = false;
    }

    /**
     * @return int;
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getGivenName()
    {
        return $this->given_name;
    }

    /**
     * @param $given_name
     */
    public function setGivenName($given_name)
    {
        $this->given_name = $given_name;
    }

    /**
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middle_name;
    }

    /**
     * @param $middle_name
     */
    public function setMiddleName($middle_name)
    {
        $this->middle_name = $middle_name;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @param $last_name
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
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
     * @return bool
     */
    public function isHidden()
    {
        return $this->is_hidden;
    }

    /**
     * @param $is_hidden
     */
    public function setIsHidden($is_hidden)
    {
        $this->is_hidden = $is_hidden;
    }

}