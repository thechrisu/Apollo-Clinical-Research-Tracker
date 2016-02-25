<?php
/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 */

namespace Apollo\Entities;

/**
 * Class PersonEntity
 * @package Apollo\Entities
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @version 0.03
 * @Entity @Table("people")
 */
class PersonEntity
{
    /**
     * Unique Person id
     * @var int
     * @Id @Column(type="integer") @GeneratedValue
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
     * @var int
     * @ManyToOne(targetEntity="OrganisationEntity")
     */
    protected $organization_id;

    /**
     * determines if person is shown or not
     * @var bool
     * @Column(type="boolean")
     */
    protected $isHidden;

    public function __construct()
    {
        $this->hidden = false;
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
     * @return bool
     */
    public function isHidden()
    {
        return $this->isHidden;
    }

    /**
     * @param $isHidden
     */
    public function setIsHidden($isHidden)
    {
        $this->isHidden = $isHidden;
    }

}