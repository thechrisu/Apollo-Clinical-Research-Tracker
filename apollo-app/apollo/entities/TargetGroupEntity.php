<?php
/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 */

namespace Apollo\Entities;
use Apollo\Components\Activity;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class TargetGroupEntity
 * @package Apollo\Entities
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @version 0.0.1
 * @Entity @Table("targetgroups")
 */
class TargetGroupEntity
{
    /**
     * Unique activity id
     * @Id @Column(type="integer", name="id")
     * @GeneratedValue
     * @var int
     */
    protected $id;

    /**
     * Name of the target group
     * @Column(type="string")
     * @var string
     */
    protected $name = '';

    /**
     *
     * @OneToMany(targetEntity="ActivityEntity", mappedBy="target_group")
     * @var \Doctrine\Common\Collections\ArrayCollection|ActivityEntity[]
     */
    protected $activities;

    /**
     * Organisation object
     * @ManyToOne(targetEntity="OrganisationEntity")
     * @var OrganisationEntity
     */
    protected $organisation;

    /**
     * Determines if target group is shown or not
     * @Column(type="boolean")
     * @var bool
     */
    protected $is_hidden = false;

    /**
     * TargetGroupEntity constructor.
     */
    public function __construct()
    {
        $this->activities = new ArrayCollection();
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
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * @return ActivityEntity[]
     */
    public function getActivities()
    {
        $ret = [];
        foreach($this->activities as $activity){
            $ret[] = $activity;
        }
        return $ret;
    }

    /**
     * @param ActivityEntity $activity
     */
    public function addActivity(ActivityEntity $activity)
    {
        if(!$this->hasActivity($activity)) {
            $this->activities->add($activity);
        }
    }

    /**
     * @param ActivityEntity $activity
     */
    public function removeActivity(ActivityEntity $activity)
    {
        if($this->hasActivity($activity))
        {
            $this->activities->removeElement($activity);
        }
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
     * @param ActivityEntity $activity
     * @return bool
     */
    public function hasActivity(ActivityEntity $activity)
    {
        if(!$this->getActivities() || empty($this->getActivities()))
            return false;
        else
            return $this->activities->contains($activity);
    }
}