<?php
/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 */

namespace Apollo\Entities;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use DateTime;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * Class ActivityEntity
 * @package Apollo\Entities
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
  * @version 0.0.1
 * @Entity @Table("activities")
 */
class ActivityEntity
{
    /**
     * Unique activity id
     * @Id @Column(type="integer") @GeneratedValue
     * @var int
     */
    protected $id;

    /**
     * Name of the activity
     * @Column(type="string")
     * @var string
     */
    protected $name;

    /**
     * Date that the activity starts
     * @Column(type="datetime")
     * @var DateTime
     */
    protected $start_date;

    /**
     * Date that the activity ends
     * @Column(type="datetime")
     * @var DateTime
     */
    protected $end_date;

    /**
     * Array with all of the people in the activity
     * @OneToMany(targetEntity="PersonEntity", mappedBy="person")
     * @var PersonEntity[]
     */
    protected $people;

    /**
     * Array with all the target groups
     * TODO: Add definition for type of target group
     */
    protected $target_groups;

    /**
     * Accompanying text for the target group
     * @Column(type="string")
     * @var string
     */
    protected $target_group_comment;

    /**
     * Organisation object
     * @ManyToOne(targetEntity="OrganisationEntity")
     * @var OrganisationEntity
     */
    protected $organisation;

    /**
     * Determines if activity is shown or not
     * @Column(type="boolean")
     * @var bool
     */
    protected $is_hidden = false;


    public function __construct()
    {
        $this->is_hidden = false;
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
     * @return DateTime
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * @param DateTime $start_date
     */
    public function setStartDate($start_date)
    {
        $this->start_date = $start_date;
    }

    /**
     * @return DateTime
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * @param DateTime $end_date
     */
    public function setEndDate($end_date)
    {
        $this->end_date = $end_date;
    }

    /**
     * @return OrganisationEntity
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }

    /**
     * @param OrganisationEntity $organisation
     */
    public function setOrganisation($organisation)
    {
        $this->organisation = $organisation;
    }

    /**
     * @return PersonEntity[]
     */
    public function getPeople()
    {
        return $this->people;
    }

    /**
     * @param PersonEntity $person
     */
    public function addPerson($person)
    {
        $this->people[] = $person;
    }

    /**
     * TODO: Add type for target group
     */
    public function getTargetGroups()
    {
        return $this->target_groups;
    }

    public function addTargetGroup($target_group)
    {
        $this->target_groups[] = $target_group;
    }

    /**
     * @return string
     */
    public function getTargetGroupComment()
    {
        return $this->target_group_comment;
    }

    /**
     * @param $target_group_comment
     */
    public function setTargetGroupComment($target_group_comment)
    {
        $this->target_group_comment = $target_group_comment;
    }

    /**
     * @return boolean
     */
    public function isHidden()
    {
        return $this->is_hidden;
    }

    /**
     * @param boolean $is_hidden
     */
    public function setIsHidden($is_hidden)
    {
        $this->is_hidden = $is_hidden;
    }


}