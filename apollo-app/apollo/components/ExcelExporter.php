<?php
/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 */

namespace Apollo\Components;
use Apollo\Components\Person;
use Apollo\Components\Field;
use SimpleExcel\SimpleExcel;
use SimpleExcel\Writer\BaseWriter;
use SimpleExcel\Writer\XLSXWriter;


/**
 * Class ExcelExporter
 * considered plugins
 * https://phpexcel.codeplex.com/wikipage?title=Features&referringTitle=Requirements
 * https://github.com/nuovo/spreadsheet-reader
 * http://www.the-art-of-web.com/php/dataexport/
 * @package Apollo\Components
 * @version 0.0.1
 *
 */
class ExcelExporter
{
    public function getTestFile()
    {
        $excel = new SimpleExcel('XML');                    // instantiate new object (will automatically construct the parser & writer type as XML)

        $excel->writer->setData(
            array
            (
                $this->getFieldNames(),
                array('1',   'Kab. Bogor',       '1'    ),
                array('2',   'Kab. Cianjur',     '1'    ),
                array('3',   'Kab. Sukabumi',    '1'    ),
                array('4',   'Kab. Tasikmalaya', '2'    )
            )
        );                                                  // add some data to the writer
        $excel->writer->saveFile('people-' . date('Y-m-d'));
    }

    private function getFieldNames()
    {
        $fields = Field::getRepository()->findBy(['is_hidden' => '0']);
        $names = [];
        foreach($fields as $field)
            $names[] = $field->getName();
        return $names;
    }
}