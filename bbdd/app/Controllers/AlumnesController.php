<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use SIENSIS\KpaCrud\Libraries\KpaCrud;
use App\Models\TiquetModel;
use App\Models\EstatModel;

class AlumnesController extends BaseController
{
    public function registreAlumnes()
    {
        $codi_centre = session()->get('user_data')['codi_centre'];
        $role = session()->get('user_data')['role'];

        if ($role == "professor") {

        }

        $data['title'] = 'Tiquets SSTT';

        $crud = new KpaCrud();
        $crud->setConfig('onlyView');
        $crud->setConfig([
            "numerate" => false,
            "add_button" => false,
            "show_button" => false,
            "recycled_button" => false,
            "useSoftDeletes" => true,
            "multidelete" => false,
            "filterable" => false,
            "editable" => false,
            "removable" => false,
            "paging" => false,
            "numerate" => false,
            "sortable" => true,
            "exportXLS" => true,
            "print" => false
        ]);
        $crud->setTable('alumne');
        $crud->setPrimaryKey('correu_alumne');
        $crud->setColumns([
            'correu_alumne'
        ]);
        $crud->setColumnsInfo([
            'correu_alumne' => [
                'name' => lang('alumne.correu_alumne')
            ],
        ]);

        $data['output'] = $crud->render();
        $data['uri'] = $this->request->getPath();
        return view('registres' . DIRECTORY_SEPARATOR . 'registreAlumnes', $data);
    }

}
