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

    protected $is_default;
}