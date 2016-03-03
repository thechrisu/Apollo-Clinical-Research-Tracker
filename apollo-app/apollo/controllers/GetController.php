<?php
/**
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @copyright 2016
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */


namespace Apollo\Controllers;

use Apollo\Apollo;
use Apollo\Components\DB;
use Apollo\Components\Person;


/**
 * Class GetController
 *
 * Deals with get request to the API
 *
 * @package Apollo\Controllers
 * @author Timur Kuzhagaliyev <tim.kuzh@gmail.com>
 * @version 0.0.1
 */
class GetController extends GenericController
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
     * Returns the records
     *
     * @since 0.0.1
     */
    public function actionRecords()
    {
        $em = DB::getEntityManager();
        $data = $this->parseRequest(['page' => 1, 'sort' => 1, 'search' => null]);
        $page = $data['page'] > 0 ? $data['page'] : 1;
        $sort = '';
        switch ($data['sort']) {
            case 2:
                $sort = '';
                break;
        }

        $peopleRepo = $em->getRepository(Person::getEntityNamespace());
        $peopleQB = $peopleRepo->createQueryBuilder('p');
        $peopleQB->leftJoin('p.records', 'r');
        $peopleQB->where('p.organisation = ' . Apollo::getInstance()->getUser()->getOrganisationId());
        $peopleQB->where('p.is_hidden = 1');
        $peopleQuery = $peopleQB->getQuery();
        echo '<pre>';
        var_dump($peopleQuery->getResult());
    }

    /**
     * Parses the request searching for specified keys. If a key is not defined in the GET request,
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