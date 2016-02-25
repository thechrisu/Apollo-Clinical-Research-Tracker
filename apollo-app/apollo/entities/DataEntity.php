<?php
/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Apollo\Entities;
use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * Class DataEntity
 * @package Apollo\Entities
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @Entity @Table("data")
 * @version 0.0.4
 */
class DataEntity
{
    /**
     * @Id @Column(type="integer") @GeneratedValue
     * @var int
     */
    protected $id;

    /**
     * @ManyToOne(targetEntity="RecordEntity", inversedBy="data")
     * @var int
     */
    protected $record;

    /**
     * @ManyToOne(targetEntity="FieldEntity")
     * @var int
     */
    protected $field;

    /**
     * @Column(type="integer")
     * @var int
     */
    protected $int;

    /**
     * @Column(type="string")
     * @var string
     */
    protected $varchar;

    /**
     * @Column(type="datetime")
     * @var DateTime
     */
    protected $date_time;

    /**
     * @Column(type="text")
     * @var string
     */
    protected $long_text;

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
     * @Column(type="datetime")
     * @var DateTime
     */
    protected $last_update;

    /**
     * @ManyToOne(targetEntity="UserEntity")
     * @var UserEntity
     */
    protected $updated_by;

    /**
     * @Column(type="boolean")
     * @var bool
     */
    protected $is_default;

    /**
     * DataEntity constructor.
     */
    public function __construct()
    {
        $this->is_default = false;
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
    public function getRecord()
    {
        return $this->record;
    }

    /**
     * @param int $record
     */
    public function setRecord($record)
    {
        $this->record = $record;
    }

    /**
     * @return int
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param int $field
     */
    public function setField($field)
    {
        $this->field = $field;
    }

    /**
     * @return int
     */
    public function getInt()
    {
        return $this->int;
    }

    /**
     * @param int $int
     */
    public function setInt($int)
    {
        $this->int = $int;
    }

    /**
     * @return string
     */
    public function getVarchar()
    {
        return $this->varchar;
    }

    /**
     * @param string $varchar
     */
    public function setVarchar($varchar)
    {
        $this->varchar = $varchar;
    }

    /**
     * @return DateTime
     */
    public function getDateTime()
    {
        return $this->date_time;
    }

    /**
     * @param DateTime $date_time
     */
    public function setDateTime($date_time)
    {
        $this->date_time = $date_time;
    }

    /**
     * @return string
     */
    public function getLongText()
    {
        return $this->long_text;
    }

    /**
     * @param string $long_text
     */
    public function setLongText($long_text)
    {
        $this->long_text = $long_text;
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
     * @return DateTime
     */
    public function getLastUpdate()
    {
        return $this->last_update;
    }

    /**
     * @param DateTime $last_update
     */
    public function setLastUpdate($last_update)
    {
        $this->last_update = $last_update;
    }

    /**
     * @return int
     */
    public function getUpdatedBy()
    {
        return $this->updated_by;
    }

    /**
     * @param int $updated_by
     */
    public function setUpdatedBy($updated_by)
    {
        $this->updated_by = $updated_by;
    }

    /**
     * @return boolean
     */
    public function isDefault()
    {
        return $this->is_default;
    }

    /**
     * @param boolean $is_default
     */
    public function setIsDefault($is_default)
    {
        $this->is_default = $is_default;
    }


}