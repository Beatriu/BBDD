<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use SIENSIS\KpaCrud\Libraries\KpaCrud;

class RegistresController extends BaseController
{
    public function index()
    {

        //TODO: Fer que aquest controllador miri quin rol té i redireccioni a la funció amb taula que li pertoca veure a l'usuari.
        /*$data['title'] = 'Kpacrud';
        $crud = new KpaCrud();                          // loads default configuration    
        $crud->setConfig('onlyView');                   // sets configuration to onlyView
        $crud->setConfig([
            "numerate" => false,
            "add_button" => false,
            "show_button" => false,
            "recycled_button" => false,
            "useSoftDeletes" => false,
            "multidelete" => false,
            "filterable" => false,
            "editable" => false,
            "removable" => false,
            "paging" => false,
            "numerate" => false,
            "sortable" => false,
            "exportXLS" => false,
            "print" => false
        ]);   // set editable config parameter to false
        // set into config file
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
        return view('kpacrud/exemplecrud', $data);*/
    }

    public function registreTiquetsProfessor()
    {
        $data['locale'] = session()->language;
        $data['title'] = 'Tiquets SSTT';
        $crud = new KpaCrud();                          // loads default configuration    
        $crud->setConfig('onlyView');                   // sets configuration to onlyView
        $crud->setConfig([
            "numerate" => false,
            "add_button" => false,
            "show_button" => true,
            "recycled_button" => false,
            "useSoftDeletes" => false,
            "multidelete" => false,
            "filterable" => false,
            "editable" => true,
            "removable" => false,
            "paging" => false,
            "numerate" => false,
            "sortable" => false,
            "exportXLS" => false,
            "print" => false
        ]);   // set editable config parameter to false
        // set into config file
        $crud->setTable('tiquet');                        // set table name
        $crud->setPrimaryKey('id_tiquet');
        $crud->setRelation('id_tipus_dispositiu', 'tipus_dispositiu', 'id_tipus_dispositiu', 'nom_tipus_dispositiu');
        $crud->setRelation('id_estat', 'estat', 'id_estat', 'nom_estat');                       // set primary key
        $crud->setRelation('codi_centre_emissor', 'centre', 'codi_centre', 'nom_centre');   
        $crud->setColumns(['codi_equip', 'tipus_dispositiu__nom_tipus_dispositiu','descripcio_avaria', 'estat__nom_estat', 'centre__nom_centre']); // set columns/fields to show
        $crud->setColumnsInfo([                         // set columns/fields name
            'codi_equip' => [
                'name' => 'Codi del equip'
            ],
            'tipus_dispositiu__nom_tipus_dispositiu' => [
                'name' => 'Tipus de dispositiu',
            ],
            'descripcio_avaria' => [
                'name' => 'Descripció',
            ],     
            'estat__nom_estat' => [
                'name' => 'Estat',
            ],
            'centre__nom_centre' => [
                'name' => 'Centre',
            ],
        ]);

        //$crud->addWhere('blog.blog_id!="1"'); // show filtered data
        $data['output'] = $crud->render();          // renders view
        return view('registres/registreTiquetSSTT', $data);
    }

    public function registreTiquetsSSTT()
    {

    }

    public function registreTiquetsEmissor()
    {

    }

    public function registreTiquetsAdmin()
    {

    }
}
