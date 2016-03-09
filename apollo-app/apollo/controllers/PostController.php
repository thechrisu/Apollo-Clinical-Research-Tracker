<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */


namespace Apollo\Controllers;
use Apollo\Apollo;
use Apollo\Components\DB;
use Apollo\Components\Record;
use Apollo\Entities\RecordEntity;
use Doctrine\ORM\EntityRepository;


/**
 * Class PostController
 *
 * Deals with post request to the API
 *
 * @package Apollo\Controllers
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.1
 */
class PostController extends GenericController
{
    /**
     * Since an empty request to API is not valid, return 400 error
     *
     * @since 0.0.1
     */
    public function index()
    {
        Apollo::getInstance()->getRequest()->error(400, 'No action is specified.');
    }

    /**
     * Return 400 error on invalid action
     *
     * @since 0.0.1
     */
    public function notFound()
    {
        Apollo::getInstance()->getRequest()->error(400, 'Invalid action.');
    }

    /**
     *
     *
     * @since 0.0.1
     */
    public function actionRecord() {
        $em = DB::getEntityManager();
        $data = $this->parseRequest(['action' => null, 'id' => 0]);
        $action = strtolower($data['action']);
        if(!in_array($action, ['hide', 'update'])) {
            Apollo::getInstance()->getRequest()->error(400, 'Invalid action.');
        };
        if($data['id'] < 0) {
            Apollo::getInstance()->getRequest()->error(400, 'Invalid ID specified.');
        };
        $response['error'] = null;
        $em = DB::getEntityManager();
        if($action == 'hide') {
            /**
             * @var EntityRepository $record_repository
             */
            $record_repository = $em->getRepository(Record::getEntityNamespace());
            /**
             * @var RecordEntity $record
             */
            $record = $record_repository->findOneBy(['id' => $data['id'], 'is_hidden' => 0]);
            if($record != null) {
                $person = $record->getPerson();
                if($person->getOrganisation()->getId() == Apollo::getInstance()->getUser()->getOrganisationId()) {
                    $records = $person->getRecords();
                    $count = 0;
                    foreach($records as $current_record) {
                        if(!$current_record->isHidden()) {
                            $count++;
                        }
                    }
                    if($count > 1) {
                        $record->setIsHidden(true);
                        $em->flush();
                    } else {
                        $response['error'] = [
                            'id' => 1,
                            'description' => 'This is the only visible record for this person, hence cannot be hidden.'
                        ];
                    }
                } else {
                    $response['error'] = [
                        'id' => 1,
                        'description' => 'Record belongs to another organisation!'
                    ];
                }
            } else {
                $response['error'] = [
                    'id' => 1,
                    'description' => 'Selected record is either already hidden or does not exist'
                ];
            }
        }
        echo json_encode($response);
    }

    /**
     * Parses the request searching for specified keys. If a key is not defined in the POST request,
     * use the default value specified in the array.
     *
     * @param array $data
     * @return array
     * @since 0.0.1
     */
    public function parseRequest($data)
    {
        $parsedData = [];
        foreach ($data as $key => $default) {
            if (isset($_GET[$key])) {
                $parsedData[$key] = is_int($default) ? intval($_GET[$key]) : $_GET[$key];
            } else {
                $parsedData[$key] = $default;
            }
        }
        return $parsedData;
    }
}