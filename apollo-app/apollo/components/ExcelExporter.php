<?php
/**
 * @author Christoph Ulshoefer <christophsulshoefer@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/gpl-license.php MIT License
 */

namespace Apollo\Components;
use Apollo\Apollo;
use Apollo\Components\Record;
use Apollo\Components\Field;
use Apollo\Components\Person;
use Exception;
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
    public function getDataFromRecordIds($record_ids){
        $people = Record::getPeopleFromRecordIds($record_ids);

        $headers = array_merge(Field::getFieldNames(), ['record name']);
        $headers = array_merge($headers, ['activities']);
        $data = [];
        $i = 0;
        while(empty($people[$i]))
            $i++;
        for($data[] = $this->getPersonDataAsArray($people[$i]); $i < count($people); $i++){
            if(!empty($people[$i])){
                $temp = $this->getPersonDataAsArray($people[$i]);
                if(!empty($temp)){
                    $data = array_merge($data, [$temp]);
                }
            }
        }
        $this->sendXML('people', $headers, $data);
    }

    private function getPersonDataAsArray($person)
    {
        try {
            $data = [];
            foreach ($person->getRecords() as $record) {
                $temp = $this->getRecordDataAsArray($record);
                if (!empty($temp)) {
                    $data[] = $temp;
                }
            }
            return $data;
        } catch (Exception $e) {
            return null;
        }
    }

    private function getRecordDataAsArray($record)
    {
        if(!$record->getIsHidden()) {
            try {
                $essential = Record::getFormattedFields($record, true);
                $record_name = $record->getName();
                $non_essential = Record::getFormattedFields($record, false);
                $activityNames = Person::getActivityNames($record->getPerson());
                $formattedNames = Data::concatMultiple($activityNames);
                $strings = array_merge($record_name, Data::formattedDataArrayToString($essential));
                $strings = array_merge($strings, Data::formattedDataArrayToString($non_essential));
                $strings = array_merge($strings, [$formattedNames]);
                return $strings;
            } catch (Exception $e) {
                return null;
            }
        } else {
            return null;
        }
    }

    //private function getRecordInformation
    public function sendXML($fileName, $headers, $strings)
    {
        $excel = new SimpleExcel('XML');

        $excel->writer->setData(
            array
            (
                $headers,
                $strings
            )
        );                                                  // add some data to the writer
        $excel->writer->saveFile($fileName . '-' . date('Y-m-d'));
    }

}