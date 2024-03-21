<?php
/**
* @author     Martin Høgh <mh@mapcentia.com>
* @copyright  2013-2024 MapCentia ApS
* @license    http://www.gnu.org/licenses/#AGPL  GNU AFFERO GENERAL PUBLIC LICENSE 3
*
*/

namespace app\api\v3;

use app\exceptions\GC2Exception;
use app\inc\Controller;
use app\inc\Input;
use app\inc\Model;
use app\inc\Route;


/**
* Class Grid
* @package app\api\v3
*/
class Foreign extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws GC2Exception
     */
    public function post_index(): array
    {
        $model = new Model();
        $body = Input::getBody();
        $arr = json_decode($body, true);
        $from = $arr['from'];
        $to = $arr['to'];
        $server = $arr['server'];
        $model->importForeignSchema($from, $to, $server);
        return ["code" => "201"];
    }

    /**
     * @throws GC2Exception
     */
    public function post_materialize(): array
    {
        $model = new Model();
        $body = Input::getBody();
        $arr = json_decode($body, true);
        $from = $arr['from'];
        $to = $arr['to'];
        $prefix = $arr['prefix'];
        $include = $arr['include'];
        $count = $model->materializeForeignTables($from, $to, $prefix, $include);
        return ["code" => "200", "count" => $count];
    }

    /**
     * @throws GC2Exception
     */
    public function delete_index(): array
    {
        $model = new Model();
        $body = Input::getBody();
        $arr = json_decode($body, true);
        $schemas = $arr['schemas'];
        $model->deleteForeignTables($schemas);
        return ["code" => "204"];
    }
}