<?php
/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 */

namespace Apollo\Entities;
use Apollo\components\TargetGroup;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinColumns;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\Common\Collections\ArrayCollection;
use DateTime;

/**
 * Class ActivityEntity
 * @package Apollo\Entities
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @version 0.0.5
 * @Entity @Table("activities")
 */
class ActivityEntity
{
    /**
     * Unique activity id
     * @Id @Column(type="integer", name="id")
     * @GeneratedValue(strategy="AUTO")
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
     * @var \Doctrine\Common\Collections\ArrayCollection|PersonEntity[]
     * @ManyToMany(targetEntity="PersonEntity", mappedBy="activities")
     */
    protected $people;

    /**
     * The target group for the activity
     * @ManyToOne(targetEntity="TargetGroupEntity", inversedBy="activities")
     * @var TargetGroupEntity
     */
    protected $target_group;

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
        $this->people = new ArrayCollection();
        $this->is_hidden = false;
        $this->setTargetGroup(TargetGroup::getMin());
        $this->target_group_comment = '';
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
        $ret = [];
        foreach($this->people as $person){
            $ret[] = $person;
        }
        return $ret;
    }

    /**
     * @param PersonEntity $person
     */
    public function addPerson($person)
    {
        if(!$this->hasPerson($person)) {
            $this->people->add($person);
            $person->addActivity($this);
        }
    }

    /**
     * @param PersonEntity $person
     */
    public function removePerson(PersonEntity $person)
    {
        if($this->hasPerson($person))
        {
           $this->people->removeElement($person);
            $person->removeActivity($this);
        }
    }

    /**
     * @param PersonEntity[] $people
     */
    public function addPeople($people)
    {
        if($people && !empty($people) && (is_array($people) || is_object($people))) {
            foreach($people as $person)
                    $this->addPerson($person);
        }
    }

    /**
     * @param $people
     */
    public function removePeople($people)
    {
        if($people && !empty($people) && (is_array($people) || is_object($people))) {
            foreach ($people as $person)
                $this->removePerson($person);
        }
    }

    /**
     * @return mixed
     */
    public function getTargetGroup()
    {
        return $this->target_group;
    }

    /**
     * @param $target_group
     */
    public function setTargetGroup($target_group)
    {
        $this->target_group = $target_group;
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

    /**
     * @param $person
     * @return bool
     */
    public function hasPerson(PersonEntity $person)
    {
        if(!$this->getPeople() || empty($this->getPeople()))
            return false;
        else
            return $this->people->contains($person);
    }
}