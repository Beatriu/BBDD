<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use SIENSIS\KpaCrud\Libraries\KpaCrud;
use App\Models\CentreModel;
use App\Models\EstatModel;
use App\Models\LoginInRolModel;
use App\Models\LoginModel;
use App\Models\RolModel;
use App\Models\TiquetModel;
use Google\Service\BigtableAdmin\Split;

class RegistresController extends BaseController
{
    public function index($id_tiquet = null)
    {
        //TODO: Fer que aquest controllador miri quin rol té i redireccioni a la funció amb taula que li pertoca veure a l'usuari.

        $role = session()->get('user_data')['role'];

        switch ($role) {
            case "alumne":
                break;
            case "professor":
                return view('registres' . DIRECTORY_SEPARATOR . 'registreTiquetsProfessor', $this->registreTiquetsProfessor('emissor', $id_tiquet));
                break;
            case "centre_emissor":
                return view('registres' . DIRECTORY_SEPARATOR . 'registreTiquetsCentreEmissor', $this->registreTiquetsCentreEmissor($id_tiquet));
                break;
            case "centre_reparador":
                break;
            case "sstt":
                return view('registres' . DIRECTORY_SEPARATOR . 'registreTiquetSSTT', $this->registreTiquetsSSTT($id_tiquet));
                break;
            case "admin_sstt":
                break;
            case "desenvolupador":
                break;
            default:
                break;
        }
    }

    public function index2($id_tiquet = null) {
        return view('registres' . DIRECTORY_SEPARATOR . 'registreTiquetsProfessor', $this->registreTiquetsProfessor('reparador', $id_tiquet));
    }

    public function registreTiquetsCentreEmissor($id_tiquet)
    {
        $tiquet_model = new TiquetModel();
        $estat_model = new EstatModel();
        $data['title'] = 'Tiquets Professor';
        $data['id_tiquet'] = null;
        $data['error'] = '';

        if($id_tiquet != null){

            // Dades per a la gestió de rols
            $tiquet = $tiquet_model->getTiquetById($id_tiquet);
            $role = session()->get('user_data')['role'];
            $codi_centre = session()->get('user_data')['codi_centre'];
            $estat = $estat_model->obtenirEstatPerId($tiquet['id_estat']);

            if ((($role == "centre_emissor" || $role == "professor" || $role == "centre_reparador") && $estat == "Pendent de recollir" && $codi_centre == $tiquet['codi_centre_emissor']) || ($role == "sstt" && $estat == "Pendent de recollir") || $role == "admin_sstt" || $role == "desenvolupador") {
                //Preguntar a la bbdd quin tiquet es i retornar l'array del tiquet.
                $data['id_tiquet'] = $id_tiquet;
                session()->setFlashdata("tiquet",$tiquet_model->getTiquetById($id_tiquet));
            } else {
                $data['error'] = 'registre.no_permisos_eliminar';
            }

        } 
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
            "paging" => false,
            "numerate" => false,
            "sortable" => false,
            "exportXLS" => false,
            "print" => false
        ]);   // set editable config parameter to false
        // set into config file
        $crud->setTable('vista_tiquet');                        // set table name
        $crud->setPrimaryKey('id_tiquet'); 
        $crud->addItemLink('edit', 'fa-pencil', base_url('editarTiquet'), 'Editar Tiquet');
        $crud->addItemLink('delete', 'fa-trash', base_url('registreTiquet/esborrar'), 'Eliminar Tiquet');

        $crud->setColumns(['codi_equip', 'nom_tipus_dispositiu', 'descripcio_avaria_limitada', 'nom_estat', 'nom_centre_emissor', 'data_alta_format', 'hora_alta_format']); // set columns/fields to show
        $crud->setColumnsInfo([                         // set columns/fields name
            'id_tiquet' => [
                'type' => KpaCrud::INVISIBLE_FIELD_TYPE,
            ],
            'codi_equip' => [
                'name' => lang("registre.codi_equip")
            ],
            'nom_tipus_dispositiu' => [
                'name' => lang("registre.tipus_dispositiu"),
                'type' => KpaCrud::DROPDOWN_FIELD_TYPE,
                'options' => [
                    "1"=>"Pantalla",
                    "2"=>"Ordenador",
                    "3"=>"Projector",
                    "4"=>"Movil",
                    "5"=>"Tablet",
                    "6"=>"Portatil",
                    "7"=>"Servidor",
                    "8"=>"Altaveu",
                    "9"=>"Dispositius multimedia",
                    "10"=>"Impressora",
                ],
                'html_atts'=>[
                    "required",
                ]
            ],
            'descripcio_avaria' => [
                'type' => KpaCrud::INVISIBLE_FIELD_TYPE,
            ],
            'descripcio_avaria_limitada' => [
                'name' => lang("registre.descripcio_avaria"),
            ],
            'nom_estat' => [
                'name' => lang("registre.estat"),
                'type' => KpaCrud::INVISIBLE_FIELD_TYPE
            ],
            'codi_centre_emissor' => [
                'type' => KpaCrud::INVISIBLE_FIELD_TYPE,
            ],
            'nom_centre_emissor' => [
                'name' => lang("registre.centre"),
                'type' => KpaCrud::INVISIBLE_FIELD_TYPE,
            ],
            'data_alta_format' => [
                'name' => lang("registre.data_alta"),
                'type' => KpaCrud::DATETIME_FIELD_TYPE,
                'type' => KpaCrud::INVISIBLE_FIELD_TYPE,
                'default' => '1-2-2022'
            ],
            'hora_alta_format' => [
                'name' => lang("registre.hora_alta"),
                'type' => KpaCrud::INVISIBLE_FIELD_TYPE,
            ],
            'data_ultima_modificacio_format' => [
                'type' => KpaCrud::INVISIBLE_FIELD_TYPE,
            ],
            'hora_ultima_modificacio_format' => [
                'type' => KpaCrud::INVISIBLE_FIELD_TYPE,
            ],
            'nom_centre_reparador' => [
                'type' => KpaCrud::INVISIBLE_FIELD_TYPE
            ],
            'codi_centre_reparador' => [
                'type' => KpaCrud::INVISIBLE_FIELD_TYPE
            ]

        ]);
        //$crud->addWhere('blog.blog_id!="1"'); // show filtered data
        $data['output'] = $crud->render();          // renders view
        return $data;
    }

    public function registreTiquetsProfessor($repoemi, $id_tiquet)
    {
        $tiquet_model = new TiquetModel();
        $estat_model = new EstatModel();
        $data['title'] = 'Tiquets Professor';
        $data['id_tiquet'] = null;
        $data['error'] = '';

        if($id_tiquet != null){

            // Dades per a la gestió de rols
            $tiquet = $tiquet_model->getTiquetById($id_tiquet);
            $role = session()->get('user_data')['role'];
            $codi_centre = session()->get('user_data')['codi_centre'];
            $estat = $estat_model->obtenirEstatPerId($tiquet['id_estat']);

            if ((($role == "centre_emissor" || $role == "professor" || $role == "centre_reparador") && $estat == "Pendent de recollir" && $codi_centre == $tiquet['codi_centre_emissor']) || ($role == "sstt" && $estat == "Pendent de recollir") || $role == "admin_sstt" || $role == "desenvolupador") {
                //Preguntar a la bbdd quin tiquet es i retornar l'array del tiquet.
                $data['id_tiquet'] = $id_tiquet;
                session()->setFlashdata("tiquet",$tiquet_model->getTiquetById($id_tiquet));
            } else {
                $data['error'] = 'registre.no_permisos_eliminar';
            }

        } 
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
            "paging" => false,
            "numerate" => false,
            "sortable" => false,
            "exportXLS" => false,
            "print" => false
        ]);   // set editable config parameter to false
        // set into config file
        $crud->setTable('vista_tiquet');                        // set table name
        $crud->setPrimaryKey('id_tiquet'); 
        $crud->addItemLink('edit', 'fa-pencil', base_url('editarTiquet'), 'Editar Tiquet');
        

        if ($repoemi == "emissor") {
            $crud->addItemLink('delete', 'fa-trash', base_url('registreTiquet/esborrar'), 'Eliminar Tiquet');
        }

        if ($repoemi == "reparador") {
            $crud->addWhere('codi_centre_reparador', session()->get('user_data')['codi_centre']);
        } else if ($repoemi == "emissor") {
            $crud->addWhere('codi_centre_emissor', session()->get('user_data')['codi_centre']);
        }
        

        $crud->setColumns(['codi_equip', 'nom_tipus_dispositiu', 'descripcio_avaria_limitada', 'nom_estat', 'nom_centre_emissor', 'data_alta_format', 'hora_alta_format']); // set columns/fields to show
        $crud->setColumnsInfo([                         // set columns/fields name
            'id_tiquet' => [
                'type' => KpaCrud::INVISIBLE_FIELD_TYPE,
            ],
            'codi_equip' => [
                'name' => lang("registre.codi_equip")
            ],
            'nom_tipus_dispositiu' => [
                'name' => lang("registre.tipus_dispositiu"),
                'type' => KpaCrud::DROPDOWN_FIELD_TYPE,
                'options' => [
                    "1"=>"Pantalla",
                    "2"=>"Ordenador",
                    "3"=>"Projector",
                    "4"=>"Movil",
                    "5"=>"Tablet",
                    "6"=>"Portatil",
                    "7"=>"Servidor",
                    "8"=>"Altaveu",
                    "9"=>"Dispositius multimedia",
                    "10"=>"Impressora",
                ],
                'html_atts'=>[
                    "required",
                ]
            ],
            'descripcio_avaria' => [
                'type' => KpaCrud::INVISIBLE_FIELD_TYPE,
            ],
            'descripcio_avaria_limitada' => [
                'name' => lang("registre.descripcio_avaria"),
            ],
            'nom_estat' => [
                'name' => lang("registre.estat"),
                'type' => KpaCrud::INVISIBLE_FIELD_TYPE
            ],
            'codi_centre_emissor' => [
                'type' => KpaCrud::INVISIBLE_FIELD_TYPE,
            ],
            'nom_centre_emissor' => [
                'name' => lang("registre.centre"),
                'type' => KpaCrud::INVISIBLE_FIELD_TYPE,
            ],
            'data_alta_format' => [
                'name' => lang("registre.data_alta"),
                'type' => KpaCrud::DATETIME_FIELD_TYPE,
                'type' => KpaCrud::INVISIBLE_FIELD_TYPE,
                'default' => '1-2-2022'
            ],
            'hora_alta_format' => [
                'name' => lang("registre.hora_alta"),
                'type' => KpaCrud::INVISIBLE_FIELD_TYPE,
            ],
            'data_ultima_modificacio_format' => [
                'type' => KpaCrud::INVISIBLE_FIELD_TYPE,
            ],
            'hora_ultima_modificacio_format' => [
                'type' => KpaCrud::INVISIBLE_FIELD_TYPE,
            ],
            'nom_centre_reparador' => [
                'type' => KpaCrud::INVISIBLE_FIELD_TYPE
            ],
            'codi_centre_reparador' => [
                'type' => KpaCrud::INVISIBLE_FIELD_TYPE
            ]

        ]);
        //$crud->addWhere('blog.blog_id!="1"'); // show filtered data
        $data['output'] = $crud->render();          // renders view
        return $data;
    }

    public function registreTiquetsSSTT($id_tiquet)
    {
        $tiquet_model = new TiquetModel();
        $estat_model = new EstatModel();
        $data['title'] = 'Tiquets SSTT';
        $data['id_tiquet'] = null;
        $data['error'] = '';

        if($id_tiquet != null){

            // Dades per a la gestió de rols
            $tiquet = $tiquet_model->getTiquetById($id_tiquet);
            $role = session()->get('user_data')['role'];
            $codi_centre = session()->get('user_data')['codi_centre'];
            $estat = $estat_model->obtenirEstatPerId($tiquet['id_estat']);

            if ((($role == "centre_emissor" || $role == "professor" || $role == "centre_reparador") && $estat == "Pendent de recollir" && $codi_centre == $tiquet['codi_centre_emissor']) || ($role == "sstt" && $estat == "Pendent de recollir") || $role == "admin_sstt" || $role == "desenvolupador") {
                //Preguntar a la bbdd quin tiquet es i retornar l'array del tiquet.
                $data['id_tiquet'] = $id_tiquet;
                session()->setFlashdata("tiquet",$tiquet_model->getTiquetById($id_tiquet));
            } else {
                $data['error'] = 'registre.no_permisos_eliminar';
            }

        } 

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
        $crud->setTable('vista_tiquet');
        $crud->setPrimaryKey('id_tiquet');
        $crud->addItemLink('edit', 'fa-pencil', base_url('editarTiquet'), 'Editar Tiquet');
        $crud->addItemLink('delete', 'fa-trash', base_url('registreTiquet/esborrar'), 'Eliminar Tiquet');
        //$crud->addItemLink('view', 'fa-eye', base_url('vistaTiquet'), 'Veure detalls');
        $crud->setColumns([
            'codi_equip',
            'nom_tipus_dispositiu',
            'descripcio_avaria_limitada',
            'nom_centre_emissor',
            'nom_centre_reparador',
            'nom_estat',
            'data_alta_format',
            'hora_alta_format'
        ]);
        $crud->setColumnsInfo([
            'id_tiquet' => [
                'type' => KpaCrud::INVISIBLE_FIELD_TYPE,
            ],
            'codi_equip' => [
                'name' => lang("registre.codi_equip")
            ],
           'nom_tipus_dispositiu' => [
                'name' => lang("registre.tipus_dispositiu"),
                'type' => KpaCrud::DROPDOWN_FIELD_TYPE,
                'options' => [
                    "1"=>"Pantalla",
                    "2"=>"Ordenador",
                    "3"=>"Projector",
                    "4"=>"Movil",
                    "5"=>"Tablet",
                    "6"=>"Portatil",
                    "7"=>"Servidor",
                    "8"=>"Altaveu",
                    "9"=>"Dispositius multimedia",
                    "10"=>"Impressora",
                ],
                'html_atts'=>[
                    "required",
                ]
            ],
            'descripcio_avaria_limitada' => [
                'name' => lang("registre.descripcio_avaria"),
            ],
            'nom_centre_emissor' => [
                'name' => lang("registre.centre"),
            ],
            'nom_centre_reparador' => [
                'name' => lang("registre.centre_reparador"),
            ],
            'nom_estat' => [
                'name' => lang("registre.estat"),
                'type' => KpaCrud::DROPDOWN_FIELD_TYPE,
                'options' => [
                    "1"=>"Pendent de recollir",
                    "2"=>"Emmagatzemat a SSTT",
                    "3"=>"Pendent de reparar",
                    "4"=>"Reparant",
                    "5"=>"Reparat i pendent de recollir",
                    "6"=>"Pendent de retorn",
                    "7"=>"Retornat",
                    "8"=>"Rebutjat per SSTT",
                    "9"=>"Desguassat",
                ],
                'html_atts'=>[
                    "required",
                ]
            ],
            'data_alta_format' => [
                'name' => lang("registre.data_alta"),
                'type' => KpaCrud::DATETIME_FIELD_TYPE,
                'default' => '1-2-2022'
            ],
            'hora_alta_format' => [
                'name' => lang("registre.hora_alta"),
            ],
            'nom_centre_emissor' => [
                'name' => lang("registre.nom_centre_emissor"),
                'type' => KpaCrud::INVISIBLE_FIELD_TYPE,
            ],
            'nom_centre_reparador' => [
                'name' => lang("registre.nom_centre_reparador"),
                'type' => KpaCrud::INVISIBLE_FIELD_TYPE
            ],
        ]);

        $data['output'] = $crud->render();
        return $data;
    }

    public function eliminarTiquet($id_tiquet){

        $tiquet_model = new TiquetModel();
        $estat_model = new EstatModel();

        // Dades per a la gestió de rols
        $tiquet = $tiquet_model->getTiquetById($id_tiquet);
        $role = session()->get('user_data')['role'];
        $codi_centre = session()->get('user_data')['codi_centre'];
        $estat = $estat_model->obtenirEstatPerId($tiquet['id_estat']);

        // Gestió de rols
        if ((($role == "centre_emissor" || $role == "professor" || $role == "centre_reparador") && $estat == "Pendent de recollir" && $codi_centre == $tiquet['codi_centre_emissor']) || ($role == "sstt" && $estat == "Pendent de recollir") || $role == "admin_sstt" || $role == "desenvolupador") {
            $model_tiquet = new TiquetModel();
            $model_tiquet->deleteTiquetById($id_tiquet);
        }

        return redirect()->to(base_url('/registreTiquet'));
    }

    public function editTiquet(){

    }


    public function registreTiquetsEmissor()
    {
        
    }

    public function registreTiquetsAdmin()
    {
    }
}