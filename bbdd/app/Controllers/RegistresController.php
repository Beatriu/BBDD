<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use SIENSIS\KpaCrud\Libraries\KpaCrud;

class RegistresController extends BaseController
{
    public function index()
    {
        $crud = new KpaCrud();                          // loads default configuration    
        $crud->setConfig('onlyView');                   // sets configuration to onlyView
        $crud->setConfig([
            "numerate" => true,
            "add_button" => true,
            "show_button" => true,
            "recycled_button" => true,
            "useSoftDeletes" => true,
            "multidelete" => true,
            "filterable" => false,
            "editable" => true,
            "removable" => true
        ]);   // set editable config parameter to false
        // set into config file
        $crud->hideHeadLink([
            'js-jquery', 
            'js-bootstrap',
            'js-datatables'  => 'https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js',
            'js-datatables-boot',
            'css-bootstrap', // => 'https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css',         
            'css-datatables-boot',
            'css-fontawesome'
        ]);
        $crud->setTable('backticket');                        // set table name
        $crud->setPrimaryKey('id_back');                     // set primary key
        $crud->setColumns(['id_back', 'tipus_alerta', 'data_backticket', 'informacio']); // set columns/fields to show
        $crud->setColumnsInfo([                         // set columns/fields name
            'id_back' => [
                'name' => 'id_back'
            ],
            'tipus_alerta' => [
                'name' => 'tipus_alerta',
            ],
            'data_backticket' => ['name' => 'data_backticket','type' => KpaCrud::DATE_FIELD_TYPE,
            'default'=> date('Y-m-d', strtotime(date("d-m-Y"). ' + 6 days'))],
            'informacio' => ['name' => 'informacio']
        ]);

        //$crud->addWhere('blog.blog_id!="1"'); // show filtered data
        $data['output'] = $crud->render();          // renders view
        return view('kpacrud/exemplecrud', $data);
    }
}
