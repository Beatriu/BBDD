<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use SIENSIS\KpaCrud\Libraries\KpaCrud;
use App\Models\CentreModel;
use App\Models\LoginInRolModel;
use App\Models\LoginModel;
use App\Models\RolModel;
use Google\Service\BigtableAdmin\Split;

class RegistresController extends BaseController
{
    protected $uri;
    public function __construct() {
        $this->uri = current_url(true);
        //Amb getQuery obtinc els parametres de la ruta.
        if(str_starts_with($this->uri->getQuery(), 'del=')){
            //dd($this->uri->getQuery());
            return view('registres' . DIRECTORY_SEPARATOR . 'registreTiquetSSTT', $this->registreTiquetsSSTT());
        }
    }
    public function index()
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
                return view('registres' . DIRECTORY_SEPARATOR . 'registreTiquetSSTT', $this->registreTiquetsSSTT());
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
        $crud->setTable('tiquet');                        // set table name
        $crud->setPrimaryKey('id_tiquet');
        //$crud->setRelation('id_tipus_dispositiu', 'tipus_dispositiu', 'id_tipus_dispositiu', 'nom_tipus_dispositiu');
       // $crud->setRelation('id_estat', 'estat', 'id_estat', 'nom_estat');                       // set primary key
        //$crud->setRelation('codi_centre_emissor', 'centre', 'codi_centre', 'nom_centre');
       //
        //$crud->addItemLink('view', 'fa-file-o', base_url('route/to/link'), 'Tooltip for icon button');   
        $crud->setColumns(['codi_equip', 'tipus_dispositiu_nom_tipus_dispositiu', 'descripcio_avaria', 'estatnom_estat', 'centre_nom_centre', 'data_alta']); // set columns/fields to show
        $crud->setColumnsInfo([                         // set columns/fields name
            'codi_equip' => [
                'name' => lang("registre.codi_equip")
            ],
            'tipus_dispositiu__nom_tipus_dispositiu' => [
                'name' => lang("registre.tipus_dispositiu"),
            ],
            'descripcio_avaria' => [
                'name' => lang("registre.descripcio_avaria"),
            ],
            'estat__nom_estat' => [
                'name' => lang("registre.estat"),
            ],
            'centre__nom_centre' => [
                'name' => lang("registre.centre"),
            ],
            'data_alta' => [
                'name' => lang("registre.data_alta"),
                'type' => KpaCrud::DATETIME_FIELD_TYPE,
                'default' => '1-2-2022'
            ],

        ]);
        //$crud->addWhere('blog.blog_id!="1"'); // show filtered data
        $data['output'] = $crud->render();          // renders view
        return $data;
    }


    public function myCustomPage($obj)
    {
        //$obj es un array de tots els camps del tiquet seleccionat.
        dd($obj);
        //$this->request->getUri()->stripQuery('customf');
        //$this->request->getUri()->addQuery('customf', 'mpost');

        $centre_model = new CentreModel();
        $array_centres = $centre_model->obtenirCentresReparadors();
        $options_centre = "";
        for ($i = 0; $i < sizeof($array_centres); $i++) {
            $options_centre .= "<option value=" . $array_centres[$i]['codi_centre'] . ">";
            $options_centre .= $array_centres[$i]['nom_centre'];
            $options_centre .= "</option>";
        }
        $centres =  $options_centre;
        //$html = "<div class=\"container-lg p-4\">";
        $html = "<p>HOLA</p>";
       /* $html .= "<form method='post' action='" . base_url($this->request->getPath()) . "?" . $this->request->getUri()->getQuery() . "'>";
        $html .= csrf_field()  . "<input type='hidden' name='test' value='ToSend'>";
        $html .= "<div class=\"bg-secondary p-2 text-white\">";
        $html .= "	<h1>Editar centre reparador</h1>";
        $html .= "</div>";
        $html .= "	<div style=\"margin-top:20px\" class=\"border bg-light\">";
        $html .=    "<select id='selectOptions'>";
        $html .=    "$centres";
        $html .= "   </select>";
        $html .= "	</div>";
        $html .= "<div class='pt-2'><input type='submit' value='Envia'></div></form>";*/
        //$html .= "</div>";

        // You can load view info from view file and return to KpaCrud library
        // $html = view('view_route/view_name');

        return $html;
    }

    public function registreTiquetsSSTT()
    {
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
            "editable" => true,
            "removable" => false,
            "paging" => false,
            "numerate" => false,
            "sortable" => true,
            "exportXLS" => true,
            "print" => false
        ]);
        $crud->setTable('vista_tiquet');
        $crud->setPrimaryKey('id_tiquet');
        $crud->addItemFunction('eliminar', 'fa-trash-can', array($this, 'myCustomPage'), "Eliminar Tiquet");
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
            'codi_equip' => [
                'name' => lang("registre.codi_equip")
            ],
           'nom_tipus_dispositiu' => [
                'name' => lang("registre.tipus_dispositiu"),
                'type' => KpaCrud::DROPDOWN_FIELD_TYPE,
                'options' => [
                    "Pantalla"=>"1",
                    "Ordenador"=>"2",
                    "Projector"=>"3",
                    "Movil"=>"4",
                    "Tablet"=>"5",
                    "Portatil"=>"6",
                    "Servidor"=>"7",
                    "Altaveu"=>"8",
                    "Dispositius multimedia"=>"9",
                    "Impressora"=>"10",
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
                    "Pendent de recollir" =>"1",
                    "Emmagatzemat a SSTT"=>"2",
                    "Pendent de reparar"=>"3",
                    "Reparant"=>"4",
                    "Reparat i pendent de recollir"=>"5",
                    "Pendent de retorn"=>"6",
                    "Retornat"=>"7",
                    "Rebutjat per SSTT"=>"8",
                    "Desguassat"=>"9",
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
            ]
        ]);

        $data['output'] = $crud->render();
        return $data;
    }

    public function deleteTiquet(){
        //dd($this->uri->getQuery());
        $this->index();
    }

    public function editTiquet(){

    }

    public function opcions(){
        switch($this->uri->getQuery()){
            case str_starts_with($this->uri->getQuery(), 'del='):
                dd("eliminar");
                $this->deleteTiquet();
                break;
            case str_starts_with($this->uri->getQuery(), 'edit='): 
                $this->editTiquet();
                 break;
        }
    }

    public function registreTiquetsEmissor()
    {
        
    }

    public function registreTiquetsAdmin()
    {
    }
}