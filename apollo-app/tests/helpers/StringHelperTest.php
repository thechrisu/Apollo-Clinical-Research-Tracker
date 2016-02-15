<?php
/**
 * Created on 15/02/2016 at 21:23
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 * @version 0.0.1
 */


namespace Apollo\Tests\Helpers;
use Apollo\Helpers\StringHelper;
use PHPUnit_Framework_TestCase;


class StringHelperTests extends PHPUnit_Framework_TestCase
{

    public function removeBeginningProvider() {
        return [
            ['SOMEstring', 'string', 'some', false],
            ['SOMEstring', 'SOMEstring', 'some', true],
            ['***[][]//\\\\string', 'string', '***[][]//\\\\', true],
            ['//SoMe\\\\stuff', 'stuff', '//some\\\\', false],
            ['//SoMe\\\\stuff', 'stuff', '//SoMe\\\\', true],
        ];
    }

    /**
     * @dataProvider removeBeginningProvider
     */
    public function testRemoveBeginning($input, $output, $replace, $case) {
            $result = StringHelper::replaceBeginning($input, $replace, $case) == $output;
            $this->assertTrue($result);
    }

}
