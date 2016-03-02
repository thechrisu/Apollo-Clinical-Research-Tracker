<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license https://opensource.org/licenses/mit-license.php MIT License
 */


namespace Apollo\Entities;
use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;


/**
 * Class RecordEntity
 *
 * @package Apollo\Entities
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.3
 * @Entity @Table("records")
 */
class RecordEntity
{
    /**
     * Unique ID of the record
     * @Id @Column(type="integer") @GeneratedValue
     * @var int
     */
    protected $id;

    /**
     * The person the record belongs to
     * @ManyToOne(targetEntity="PersonEntity", inversedBy="records")
     * @var PersonEntity
     */
    protected $person;

    /**
     * @Column(type="datetime")
     * @var DateTime
     */
    protected $start_date;

    /**
     * @Column(type="datetime")
     * @var DateTime
     */
    protected $end_date;

    /**
     * @OneToMany(targetEntity="DataEntity", mappedBy="record")
     * @var DataEntity[]
     */
    protected $data = null;

    /**
     * @Column(type="datetime")
     * @var DateTime
     */
    protected $created_on;

    /**
     * @ManyToOne(targetEntity="UserEntity")
     * @var UserEntity
     */
    protected $created_by;

    /**
     * @Column(type="datetime")
     * @var DateTime
     */
    protected $updated_on;

    /**
     * @ManyToOne(targetEntity="UserEntity")
     * @var UserEntity
     */
    protected $updated_by;

    /**
     * Determines if record is shown or not
     * @Column(type="boolean")
     * @var bool
     */
    protected $is_hidden = false;

    /**
     * RecordEntity constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return PersonEntity
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @param PersonEntity $person
     */
    public function setPerson($person)
    {
        $this->person = $person;
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
     * @return DataEntity[]
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param DataEntity[] $data
     */
    public function addData($data)
    {
        $this->data[] = $data;
    }

    /**
     * @return DateTime
     */
    public function getCreatedOn()
    {
        return $this->created_on;
    }

    /**
     * @param DateTime $created_on
     */
    public function setCreatedOn($created_on)
    {
        $this->created_on = $created_on;
    }

    /**
     * @return UserEntity
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }

    /**
     * @param UserEntity $created_by
     */
    public function setCreatedBy($created_by)
    {
        $this->created_by = $created_by;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedOn()
    {
        return $this->updated_on;
    }

    /**
     * @param DateTime $updated_on
     */
    public function setUpdatedOn($updated_on)
    {
        $this->updated_on = $updated_on;
    }

    /**
     * @return UserEntity
     */
    public function getUpdatedBy()
    {
        return $this->updated_by;
    }

    /**
     * @param UserEntity $updated_by
     */
    public function setUpdatedBy($updated_by)
    {
        $this->updated_by = $updated_by;
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