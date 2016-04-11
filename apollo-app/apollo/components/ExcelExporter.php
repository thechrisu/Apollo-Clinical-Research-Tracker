<?php
/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 */

namespace Apollo\Components;
use Apollo\Components\Record;
use Apollo\Components\Field;
use Apollo\Components\Person;
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
    //private function getRecordInformation
    public function getTestFile()
    {
        $excel = new SimpleExcel('XML');                    // instantiate new object (will automatically construct the parser & writer type as XML)
        $record = Record::getRepository()->find(1);
        $essential = Record::getFormattedFields($record, true);
        $non_essential = Record::getFormattedFields($record, false);
        $activityNames = Person::getActivityNames($record->getPerson());
        $formattedNames = Data::concatMultiple($activityNames);
        $strings = array_merge(Data::formattedDataArrayToString($essential), Data::formattedDataArrayToString($non_essential));
        $strings = array_merge($strings, [$formattedNames]);
        $headers = array_merge(Field::getFieldNames(), ['activities']);

        $excel->writer->setData(
            array
            (
                array_merge(Field::getFieldNames(), ['activities']),
                $strings,
            )
        );                                                  // add some data to the writer
        $excel->writer->saveFile('people-' . date('Y-m-d'));
    }

}