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

    public function downloadAllRecords(){
        $records = Record::getRepository()->findBy(['is_hidden' => false]);
        $ids = [];
        foreach($records as $record){
            $ids[] = $record->getId();
        }
        $this->getDataFromRecordIds($ids);
    }

    public function getDataFromRecordIds($record_ids){
        $people = Record::getPeopleFromRecordIds($record_ids);
        $headers = array_merge(Field::getFieldNames(), ['activities']);
        $data = [];
        $i = 0;
        while(empty($people[$i]) && $i < count($people))
            $i++;
        if($i < count($people)) {
            for ($data = $this->getPersonDataAsArray($people[$i++]); $i < count($people); $i++) {
                if (!empty($people[$i])) {
                    $temp = $this->getPersonDataAsArray($people[$i]);
                    if (!empty($temp)) {
                        $data = array_merge($data, $temp);
                    }
                }
            }
        }
        $this->sendXML('people', $headers, $data, SORT_NATURAL);
    }

    private function getPersonDataAsArray($person)
    {
        try {
            $data = [];
            $records = $person->getRecords();
            foreach ($records as $record) {
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
        if(!$record->isHidden()) {
            try {
                $essential = Record::getFormattedFields($record, true);
                /*foreach($essential as $item)
                    echo $item['value'];*/
                $non_essential = Record::getFormattedFields($record, false);
                $activityNames = Person::getActivityNames($record->getPerson());
                $formattedNames = Data::concatMultiple($activityNames);
                $strings = Data::formattedDataArrayToString($essential);
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
        $data = array_merge([$headers], $strings);

        $excel->writer->setData($data);
        $excel->writer->saveFile($fileName . '-' . date('Y-m-d'));
    }

}