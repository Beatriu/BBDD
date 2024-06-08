<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use SIENSIS\KpaCrud\Libraries\KpaCrud;

class CentresController extends BaseController
{
    public function registreCentres()
    {
        $role = session()->get('user_data')['role'];
        $actor = session()->get('user_data');
        $data['role'] = $role;
        $data['title'] = lang('registre.titol_centres');
        $crud = new KpaCrud();                          // loads default configuration    
        $crud->setConfig('onlyView');                   // sets configuration to onlyView
        $crud->setConfig([
            "numerate" => false,
            "add_button" => false,
            "show_button" => false,
            "recycled_button" => false,
            "useSoftDeletes" => false,
            "multidelete" => false,
            "filterable" => true,
            "editable" => false,
            "removable" => false,
            "paging" => true,
            "numerate" => false,
            "sortable" => false,
            "exportXLS" => false,
            "print" => false
        ]);   // set editable config parameter to false
        // set into config file
        $crud->setTable('vista_centres');                        // set table name
        $crud->setPrimaryKey('codi_centre');
        $crud->hideHeadLink([
            'js-bootstrap',
            'css-bootstrap',
        ]);
        $crud->addItemLink('edit', 'fa-pencil', base_url('/tiquets/editar'), 'Editar Tiquet');
        $crud->addItemLink('delete', 'fa-trash', base_url('tiquets/esborrar'), 'Eliminar Tiquet');

        $crud->setColumns([
            'codi_centre',
            'nom_centre',
            'actiu',
            'taller',
            'telefon_centre',
            'correu_persona_contacte_centre',
            'nom_poblacio',
            'nom_comarca',
            'Preu_total',
            'Tiquets_del_centre'
        ]);
        $crud->setColumnsInfo([
            'codi_centre' => [
                'name' => lang("centre.codi_centre")
            ],
            'nom_centre' => [
                'name' => lang("centre.nom_centre")
            ],
            'actiu' => [
                'name' => lang("centre.actiu")
            ],
            'taller' => [
                'name' => lang("centre.taller")
            ],
            'telefon_centre' => [
                'name' => lang("centre.telefon_centre")
            ],
            'correu_persona_contacte_centre' => [
                'name' => lang("centre.nom_correu_persona_contacte_centre")
            ],
            'nom_poblacio' => [
                'name' => lang("centre.nom_poblacio")
            ],
            'nom_comarca' => [
                'name' => lang("centre.nom_comarca")
            ],
            'Preu_total' => [
                'name' => lang("centre.preu_total")
            ],
            'Tiquets_del_centre' => [
                'name' => lang("centre.Tiquets_del_centre")
            ],

        ]);

        if($role == 'sstt' || $role == 'admin_sstt'){
            $crud->addWhere('id_sstt', $actor['id_sstt']); 
        }
        
        $data['output'] = $crud->render();

        return view('registres' . DIRECTORY_SEPARATOR . 'registreCentres', $data);
    }
}
