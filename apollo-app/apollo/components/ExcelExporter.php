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
use Apollo\Entities\RecordEntity;
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
 * @version 0.0.2
 * @todo Use more database queries for this to speed up process
 */
class ExcelExporter
{


    /**
     * Wrapper function to simply send all records as XML
     * Potentially, a second version could be created which just takes a bunch of record ids (if one would
     * e.g. export the result of a search
     */
    public function downloadAllRecords()
    {
        $records = Record::getRepository()->findBy(['is_hidden' => false]);
        $this->getDataFromRecords($records);
    }

    /**
     * @param RecordEntity[] $records
     */
    public function getDataFromRecords($records)
    {
        $person_names = ['First name', 'Middle name', 'Last name'];
        $field_names = Field::getFieldNames();
        $headers = array_merge($person_names, $field_names);
        $headers = array_merge($headers, ['activities']);
        $recordData = [];
        foreach ($records as $record) {
            if(!empty($record)) {
                $row = $this->getRecordDataAsArray($record);
                if (!empty($row)) {
                    $recordData[] = $row;
                }
            }
        }
        $this->sendXML('people', $headers, $recordData, SORT_NATURAL);
    }

    /**
     * Given a record, this returns the record as a "row"
     * @param RecordEntity $record
     * @return array|null
     */
    private function getRecordDataAsArray($record)
    {
        try {
            $name = Person::getNameAsArray($record->getPerson());
            $essential = Record::getFormattedFields($record, true);
            $non_essential = Record::getFormattedFields($record, false);
            $activityNames = Person::getActivityNames($record->getPerson());
            $formattedNames = Data::concatMultiple($activityNames);
            $strings = $name;
            $strings = array_merge($strings, Data::formattedDataArrayToString($essential));
            $strings = array_merge($strings, Data::formattedDataArrayToString($non_essential));
            $strings = array_merge($strings, [$formattedNames]);
            return $strings;
        } catch (Exception $e) {
            return null;
        }
    }

    public function sendXML($fileName, $headers, $strings)
    {
        $excel = new SimpleExcel('XML');
        $data = array_merge([$headers], $strings);

        $excel->writer->setData($data);
        $excel->writer->saveFile($fileName . '-' . date('Y-m-d'));
    }

}