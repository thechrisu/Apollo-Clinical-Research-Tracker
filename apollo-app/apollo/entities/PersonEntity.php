<?php
/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 */

namespace Apollo\Entities;

/**
 * Class OrganisationEntity
 *
 * @package Apollo\Entities
 * @author Christoph Ulshoefer <christoph.ulshoefer@gmail.com>
 * @version 0.0.2
 * @Entity @Table(name="people")
 */
class PersonEntity
{
    /**
     * Unique person id
     * @var int
     * @Id @Column(type="integer") @GeneratedValue
     */
    protected $id;

    /**
     * given name
     * @var string
     * @column(type="string")
     */
    protected $given_name;

    /**
     * middle name
     * @var string
     * @column(type="string")
     */
    protected $middle_name;

    /**
     * last name
     * @var string
     * @column(type="string")
     */
    protected $last_name;

    /**
     * person is always part of one organisation
     * @var int
     * @ManyToOne(targetEntity="OrganisationEntity")
     */
    protected $organization_id;

    /**
     * person may not be shown in table, in this case it is hidden
     * @var bool
     * @column(type="boolean")
     */
    protected $hidden;

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
     * @return int
     */
    public function getOrganizationId()
    {
        return $this->organization_id;
    }

    /**
     * @return string
     */
    public function getGivenName()
    {
        return $this->given_name;
    }

    /**
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middle_name;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return $this->hidden;
    }

    public function hide()
    {
        $this->hidden = true;
    }

    public function show()
    {
        $this->hidden = false;
    }

    /**
     * @param $organization_id
     */
    public function setOrganizationId($organization_id)
    {
        $this->organization_id = $organization_id;
    }

    /**
     * @param $given_name
     */
    public function setGivenName($given_name)
    {
        $this->given_name = $given_name;
    }

    /**
     * @param $middle_name
     */
    public function setMiddleName($middle_name)
    {
        $this->middle_name = $middle_name;
    }

    /**
     * @param $last_name
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
    }

}
