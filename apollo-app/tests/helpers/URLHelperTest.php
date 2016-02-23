<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 * @version 0.0.2
 */


namespace Apollo\Tests\Helpers;
use Apollo\Helpers\URLHelper;
use PHPUnit_Framework_TestCase;


class URLHelperTest extends PHPUnit_Framework_TestCase
{


    public function splitProvider() {
        return [
            ['', [], null],
            ['/tester/test', ['tester', 'test'], null],
            [BASE_URL . '/', ['', ''], null],
            [BASE_URL . 'tester/test', ['tester', 'test'], null],
            ['/tester/test', ['tester', 'test'], 'random-domain'],
            [BASE_URL . '/tester/test', array_merge(explode('/', BASE_URL), ['tester', 'test']), 'random-domain'],
            ['http://timbo.kz/Some/Very/Long/URL/', ['Some', 'Very', 'Long', 'URL'], 'http://timbo.kz/'],
            ['/Some/Very/Long/URL/', ['Some', 'Very', 'Long', 'URL'], null],
        ];
    }

    /**
     * @dataProvider splitProvider
     */
    public function testSplit($input, $output, $base) {
        if($base != null) {
            $result = URLHelper::split($input, $base) == $output;
        } else {
            $result = URLHelper::split($input) == $output;
        }
        $this->assertTrue($result);
    }

    public function stripBaseProvider() {
        return [
            ['/tester/test', 'tester/test', null],
            [BASE_URL . 'tester/test', 'tester/test', null],
            ['/tester/test', 'tester/test', 'random-domain'],
            [BASE_URL . '/tester/test', BASE_URL . '/tester/test', 'random-domain'],
            ['http://timbo.kz/Some/Very/Long/URL/', 'Some/Very/Long/URL/', 'http://timbo.kz/'],
            ['/Some/Very/Long/URL/', 'Some/Very/Long/URL/', null],
        ];
    }

    /**
     * @dataProvider stripBaseProvider
     */
    public function testStripBase($input, $output, $base) {
            if($base != null) {
                $result = URLHelper::stripBase($input, $base) == $output;
            } else {
                $result = URLHelper::stripBase($input) == $output;
            }
            $this->assertTrue($result);
    }

}
