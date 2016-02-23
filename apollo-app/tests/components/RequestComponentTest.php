<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 * @version 0.0.1
 */


namespace Apollo\Tests\Components;
use Apollo\Components\Request;
use PHPUnit_Framework_TestCase;


class RequestComponentTest extends PHPUnit_Framework_TestCase
{


    public function constructorProvider() {
        return [
            [BASE_URL, null, null, [], true, true, null],
            [BASE_URL . '?test=test', null, null, [], true, true, 'test=test'],
            [BASE_URL . '/?test=test', null, null, [], false, false, 'test=test'],
            [BASE_URL . '123', '123', null, [], true, false, null],
            [BASE_URL . '123&', null, null, [], false, false, null],
            [BASE_URL . '///', null, null, [], false, false, null],
            [BASE_URL . 'asd//', 'Asd', null, [], false, false, null],
            [BASE_URL . 'asd/asd/', 'Asd', 'Asd', [], true, false, null],
            [BASE_URL . 'asd/asd/Test/123/', 'Asd', 'Asd', ['Test', '123'], true, false, null],
            [BASE_URL . 'asd/asd/Te-st/123/', 'Asd', 'Asd', ['Te-st', '123'], true, false, null],
            [BASE_URL . 'asd/asd/Test//', 'Asd', 'Asd', ['Test'], false, false, null],
            [BASE_URL . 'asd/asd/Test///', 'Asd', 'Asd', ['Test'], false, false, null],
            [BASE_URL . 'asd/asd/Test/Test2/123/', 'Asd', 'Asd', ['Test', 'Test2', '123'], true, false, null],
            [BASE_URL . 'asd/asd/Test/Test2/1$23/', 'Asd', 'Asd', ['Test', 'Test2'], false, false, null],
            [BASE_URL . 'asd/asd/Test/Test2/123/?test=test', 'Asd', 'Asd', ['Test', 'Test2', '123'], true, false, 'test=test'],
            [BASE_URL . 'asset/img/test-image.jpg', 'Asset', 'Img', ['test-image.jpg'], true, false, null]
        ];
    }

    /**
     * @dataProvider constructorProvider
     */
    public function testController($url, $controller, $action, $parameters, $valid, $index, $query) {
        $request = new Request($url);
        $result = $request->getController() == $controller;
        $this->assertTrue($result);
    }

    /**
     * @dataProvider constructorProvider
     */
    public function testAction($url, $controller, $action, $parameters, $valid, $index, $query) {
        $request = new Request($url);
        $result = $request->getAction() == $action;
        $this->assertTrue($result);
    }

    /**
     * @dataProvider constructorProvider
     */
    public function testParameters($url, $controller, $action, $parameters, $valid, $index, $query) {
        $request = new Request($url);
        $result = $request->getParameters() == $parameters;
        $this->assertTrue($result);
    }

    /**
     * @dataProvider constructorProvider
     */
    public function testIsValid($url, $controller, $action, $parameters, $valid, $index, $query) {
        $request = new Request($url);
        $result = $request->isValid() == $valid;
        $this->assertTrue($result);
    }

    /**
     * @dataProvider constructorProvider
     */
    public function testIsIndex($url, $controller, $action, $parameters, $valid, $index, $query) {
        $request = new Request($url);
        $result = $request->isIndex() == $index;
        $this->assertTrue($result);
    }

    /**
     * @dataProvider constructorProvider
     */
    public function testQuery($url, $controller, $action, $parameters, $valid, $index, $query) {
        $request = new Request($url);
        $result = $request->getQuery() == $query;
        $this->assertTrue($result);
    }

}
