<?php
/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 */

namespace Apollo\Entities;

/**
 * Class DataEntity
 * @package Apollo\Entities
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @version 0.0.1
 */
class DataEntity
{
    /**
     * sets a unique id for a record
     * @Id @Column(type="integer") @GeneratedValue
     * @var int
     */
    protected $id;

    /**
     * the person the record belongs to
     * @ManyToOne(type="PersonIdentity")
     * @var int
     */
    protected $user;

    /**
     * @ManyToOne(type="OrganisationEntity")
     * @var int
     */
    protected $organisation;

    /**
     * @ManyToOne(type="FieldEntity")
     * @var int
     */
    protected $field;

    /**
     * stores things of type int
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
     * @OneToMany(targetEntity="UserEntity")
     * @var int
     */
    protected $updated_by;

    /**
     * @Column(type="boolean")
     * @var bool
     */
    protected $is_default;

    /**
     * DataEntity constructor.
     * @param $user
     * @param mixed $organisation
     * @param $updated_by
     * @param $field
     * @param $is_default
     */
    public function __construct($user, $organisation, $updated_by, $field, $is_default)
    {
        $this->user = $user;
        $this->organisation = $organisation;
        $this->field = $field;
        $this->is_default = $is_default;
        $this->recordChange($updated_by);
    }

    /**
     * @param $person
     */
    private function recordChange($person)
    {
        $this->last_update = DateTime(now);
        $this->updated_by = $person;
    }

    /**
     * @return int
     */
    public function getFieldType()
    {
        return $this->field;
    }

    /**
     * @return int
     */
    public function getInt()
    {
        return $this->int;
    }

    /**
     * @param $int
     * @param $updater
     */
    public function setInt($int, $updater)
    {
        $this->int = $int;
        $this->recordChange($updater);
    }

    /**
     * @return string
     */
    public function getVarchar()
    {
        return $this->varchar;
    }

    /**
     * @param $varchar
     * @param $updater
     */
    public function setVarchar($varchar, $updater)
    {
        $this->varchar = $varchar;
        $this->recordChange($updater);
    }

    /**
     * @return DateTime
     */
    public function getDateTime()
    {
        return $this->date_time;
    }

    /**
     * @param $date_time
     * @param $updater
     */
    public function setDateTime($date_time, $updater)
    {
        $this->date_time = $date_time;
        $this->recordChange($updater);
    }

    /**
     * @return string
     */
    public function getLongText()
    {
        return $this->long_text;
    }

    /**
     * @param $longText
     * @param $updater
     */
    public function setLongText($longText, $updater)
    {
        $this->long_text = $longText;
        $this->recordChange($updater);
    }

    /**
     * @return DateTime
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * @param $start_date
     * @param $updater
     */
    public function setStartDate($start_date, $updater)
    {
        $this->start_date = $start_date;
        $this->recordChange($updater);
    }

    /**
     * @return DateTime
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * @param $end_date
     * @param $updater
     */
    public function setEndDate($end_date, $updater)
    {
        $this->end_date = $end_date;
        $this->recordChange($updater);
    }

    /**
     * @return DateTime
     */
    public function getLatestUpdate()
    {
        return $this->last_update;
    }

    public function getLatestUpdater()
    {
        return $this->updated_by;
    }

}