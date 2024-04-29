<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use SIENSIS\KpaCrud\Libraries\KpaCrud;
use App\Models\CentreModel;
use App\Models\LoginInRolModel;
use App\Models\LoginModel;
use App\Models\RolModel;
use App\Models\TiquetModel;
use Google\Service\BigtableAdmin\Split;

class RegistresController extends BaseController
{
    protected $uri;
    public function __construct() {
        $this->uri = current_url(true);
        //Amb getQuery obtinc els parametres de la ruta.
        /*if(str_starts_with($this->uri->getQuery(), 'del=')){
            //dd($this->uri->getQuery());
            return view('registres' . DIRECTORY_SEPARATOR . 'registreTiquetSSTT', $this->registreTiquetsSSTT());
        }*/
    }
    public function index($checkPopUp = null)
    {
        //TODO: Fer que aquest controllador miri quin rol té i redireccioni a la funció amb taula que li pertoca veure a l'usuari.

        $role = session()->get('user_data')['role'];

        switch ($role) {
            case "alumne":
                break;
            case "professor":
                return view('registres' . DIRECTORY_SEPARATOR . 'registreTiquetsProfessor', $this->registreTiquetsProfessor());
                break;
            case "centre_emissor":
                break;
            case "centre_reparador":
                break;
            case "sstt":
                return view('registres' . DIRECTORY_SEPARATOR . 'registreTiquetSSTT', $this->registreTiquetsSSTT($checkPopUp));
                break;
            case "admin_sstt":
                break;
            case "desenvolupador":
                $this->registreTiquetsProfessor();
                break;
            default:
                $this->registreTiquetsProfessor();
                break;
        }
    }

    public function registreTiquetsProfessor()
    {
        $data['title'] = 'Tiquets Professor';
        $crud = new KpaCrud();                          // loads default configuration    
        $crud->setConfig('onlyView');                   // sets configuration to onlyView
        $crud->setConfig([
            "numerate" => false,
            "add_button" => false,
            "show_button" => true,
            "recycled_button" => false,
            "useSoftDeletes" => false,
            "multidelete" => false,
            "filterable" => true,
            "editable" => true,
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

    public function registreTiquetsSSTT($checkPopUp)
    {
        $tiquet_model = new TiquetModel();
        $data['title'] = 'Tiquets SSTT';
        $data['checkPopUp'] = $checkPopUp;
        if($checkPopUp !== null){
            //Preguntar a la bbdd quin tiquet es i retornar l'array del tiquet.
           session()->setFlashdata("tiquet",$tiquet_model->getTiquetById($checkPopUp));
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
            "editable" => true,
            "removable" => true,
            "paging" => false,
            "numerate" => false,
            "sortable" => true,
            "exportXLS" => true,
            "print" => false
        ]);
        $crud->setTable('vista_tiquet');
        $crud->setPrimaryKey('id_tiquet');
        $crud->addItemLink('delete', 'fa-trash', base_url('registreTiquet'), 'Eliminar Tiquet');
        $crud->addItemLink('view', 'fa-eye', base_url('vistaTiquet'), 'Veure detalls');
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
                'type' => KpaCrud::INVISIBLE_FIELD_TYPE,
            ],
            'nom_centre_reparador' => [
                'type' => KpaCrud::INVISIBLE_FIELD_TYPE
            ],
        ]);

        $data['output'] = $crud->render();
        return $data;
    }

    public function eliminarTiquet($tiquet){
        
        $model_tiquet = new TiquetModel();
        $model_tiquet->deleteTiquetById($tiquet);
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