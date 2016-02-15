<?php
/**
 * Created on 15/02/2016 at 21:23
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 * @version 0.0.1
 */


namespace Apollo\Tests\Helpers;
use Apollo\Helpers\URLHelper;
use PHPUnit_Framework_TestCase;


class URLHelperTests extends PHPUnit_Framework_TestCase
{

    public function stripBaseProvider() {
        return [
            ['/tester/test', 'tester/test', null],
            [BASE_URL . 'tester/test', 'tester/test', null],
            ['/tester/test', 'tester/test', 'random-domain'],
            [BASE_URL . '/tester/test', BASE_URL . '/tester/test', 'random-domain'],
            ['http://timbo.kz/Some/Very/Long/URL/', 'Some/Very/Long/URL/', null],
            ['/Some/Very/Long/URL/', 'Some/Very/Long/URL/', null],
        ];
    }

    /**
     * @dataProvider stripBaseProvider
     */
    public function testStripBase($input, $output, $base) {
            $result = false;
            if($base != null) {
                $result = URLHelper::stripBase($input, $base) == $output;
            } else {
                $result = URLHelper::stripBase($input) == $output;
            }
            $this->assertTrue($result);
    }

}
