<?php
use Apollo\Components\Request;

/**
 * Created on 15/02/2016 at 17:02
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 * @version 0.0.1
 */

class Apollo
{

    private $request;

    public function start() {

        $this->request = new Request();

    }

}