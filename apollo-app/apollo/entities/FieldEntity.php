<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license https://opensource.org/licenses/mit-license.php MIT License
 */


namespace Apollo\Entities;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;


/**
 * Class FieldEntity
 *
 * @package Apollo\Entities
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.1
 * @Entity @Table("fields")
 */
class FieldEntity
{
    /**
     * Unique ID of the field
     * @Id @Column(type="integer") @GeneratedValue
     * @var int
     */
    protected $id;

    /**
     * @ManyToOne(targetEntity="OrganisationEntity")
     * @var int
     */
    protected $organisation;

    /**
     * @Column(type="string")
     * @var string
     */
    protected $name;

    /**
     * @Column(type="integer")
     * @var int
     */
    protected $type;

    /**
     * Determines if the field has default values
     * @Column(type="boolean")
     * @var bool
     */
    protected $has_default;

    /**
     * @OneToMany(targetEntity="DefaultEntity", mappedBy="field")
     * @var DefaultEntity[]
     */
    protected $defaults = null;

    /**
     * Determines whether the field allows a string value as well as a default value
     * @Column(type="boolean")
     * @var bool
     */
    protected $allow_other;

    /**
     * Determines whether the field allows default values
     * @Column(type="boolean")
     * @var bool
     */
    protected $is_multiple;

    /**
     * Determines if record is shown or not
     * @Column(type="boolean")
     * @var bool
     */
    protected $is_hidden;

    /**
     * FieldEntity constructor.
     */
    public function __construct()
    {
        $this->has_default = false;
        $this->allow_other = false;
        $this->is_multiple = false;
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
     * @return int
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }

    /**
     * @param int $organisation
     */
    public function setOrganisation($organisation)
    {
        $this->organisation = $organisation;
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
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return boolean
     */
    public function hasDefault()
    {
        return $this->has_default;
    }

    /**
     * @param boolean $has_default
     */
    public function setHasDefault($has_default)
    {
        $this->has_default = $has_default;
    }

    /**
     * @return DefaultEntity[]
     */
    public function getDefaults()
    {
        return $this->defaults;
    }

    /**
     * @param DefaultEntity $default
     */
    public function addDefault($default)
    {
        $this->defaults[] = $default;
    }

    /**
     * @return boolean
     */
    public function isAllowOther()
    {
        return $this->allow_other;
    }

    /**
     * @param boolean $allow_other
     */
    public function setAllowOther($allow_other)
    {
        $this->allow_other = $allow_other;
    }

    /**
     * @return boolean
     */
    public function isMultiple()
    {
        return $this->is_multiple;
    }

    /**
     * @param boolean $is_multiple
     */
    public function setIsMultiple($is_multiple)
    {
        $this->is_multiple = $is_multiple;
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