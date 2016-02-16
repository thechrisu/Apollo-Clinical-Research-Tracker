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


class StringHelperTest extends PHPUnit_Framework_TestCase
{

    public function capitalizeProvider() {
        return [
            ['SOMEstring', 'Somestring'],
            ['1Test', '1test'],
            ['TESTER', 'Tester'],
            ['//Rome', '//rome']
        ];
    }

    /**
     * @dataProvider capitalizeProvider
     */
    public function testCapitalize($input, $output) {
        $result = StringHelper::capitalize($input) == $output;
        $this->assertTrue($result);
    }

    public function stripBeginningProvider() {
        return [
            ['SOMEstring', 'string', 'some', false],
            ['SOMEstring', 'SOMEstring', 'some', true],
            ['***[][]//\\\\string', 'string', '***[][]//\\\\', true],
            ['//SoMe\\\\stuff', 'stuff', '//some\\\\', false],
            ['//SoMe\\\\stuff', 'stuff', '//SoMe\\\\', true],
        ];
    }

    /**
     * @dataProvider stripBeginningProvider
     */
    public function testStripBeginning($input, $output, $replace, $case) {
            $result = StringHelper::stripBeginning($input, $replace, $case) == $output;
            $this->assertTrue($result);
    }

    public function stripEndProvider() {
        return [
            ['SOMEstring', 'SOME', 'string', false],
            ['SOMEstring', 'SOMEstring', 'some', true],
            ['string***[][]//\\\\', 'string', '***[][]//\\\\', true],
            ['stuff//SoMe\\\\', 'stuff', '//some\\\\', false],
            ['stuff//SoMe\\\\', 'stuff', '//SoMe\\\\', true],
            ['some/path/to/something/', 'some/path/to/something', '/', true],
            ['////', '///', '/', true],
            ['///', '//', '/', true],
        ];
    }

    /**
     * @dataProvider stripEndProvider
     */
    public function testStripEnd($input, $output, $replace, $case) {
        $result = StringHelper::stripEnd($input, $replace, $case) == $output;
        $this->assertTrue($result);
    }

}
