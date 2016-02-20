<?php
/**
 * Interface for Doctrine command line, accessed using "vendor/bin/doctrine"
 *
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.1
 */

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Apollo\Components\DB;

require_once 'vendor/autoload.php';


return ConsoleRunner::createHelperSet(DB::getEntityManager());
