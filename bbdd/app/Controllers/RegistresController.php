<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use SIENSIS\KpaCrud\Libraries\KpaCrud;
use App\Models\CentreModel;
use App\Models\LoginInRolModel;
use App\Models\LoginModel;
use App\Models\RolModel;

class RegistresController extends BaseController
{
    public function index()
    {
        $login_model = new LoginModel();
        $login_in_rol_model = new LoginInRolModel();
        $rol_model = new RolModel();
        //TODO: Fer que aquest controllador miri quin rol té i redireccioni a la funció amb taula que li pertoca veure a l'usuari.


        /*$id_login = $login_model->obtenirId(session()->get('user_data')['mail']);
        $id_role = $login_in_rol_model->obtenirRol($id_login);
        $role = $rol_model->obtenirRol($id_role);

        $session_data = session()->get('user_data');
        $session_data['role'] = $role;
        session()->set('user_data', $session_data);*/

        $role = session()->get('user_data')['role'];

        switch ($role) {
            case "alumne":
                //
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
        $crud->setRelation('id_tipus_dispositiu', 'tipus_dispositiu', 'id_tipus_dispositiu', 'nom_tipus_dispositiu');
        $crud->setRelation('id_estat', 'estat', 'id_estat', 'nom_estat');                       // set primary key
        $crud->setRelation('codi_centre_emissor', 'centre', 'codi_centre', 'nom_centre');
        //$crud->addItemFunction('changeState', 'fa-arrow-up-wide-short', array($this, 'myCustomPage'), "Canviar estat");
        //$crud->addItemLink('view', 'fa-file-o', base_url('route/to/link'), 'Tooltip for icon button');   
        $crud->setColumns(['codi_equip', 'tipus_dispositiu__nom_tipus_dispositiu', 'descripcio_avaria', 'estat__nom_estat', 'centre__nom_centre', 'data_alta']); // set columns/fields to show
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

        $this->request->getUri()->stripQuery('customf');
        $this->request->getUri()->addQuery('customf', 'mpost');

        $centre_model = new CentreModel();
        $array_centres = $centre_model->obtenirCentresReparadors();
        $options_centre = "";
        for ($i = 0; $i < sizeof($array_centres); $i++) {
            $options_centre .= "<option value=" . $array_centres[$i]['codi_centre'] . ">";
            $options_centre .= $array_centres[$i]['nom_centre'];
            $options_centre .= "</option>";
        }
        $centres =  $options_centre;
        $html = "<div class=\"container-lg p-4\">";
        $html .= "<form method='post' action='" . base_url($this->request->getPath()) . "?" . $this->request->getUri()->getQuery() . "'>";
        $html .= csrf_field()  . "<input type='hidden' name='test' value='ToSend'>";
        $html .= "<div class=\"bg-secondary p-2 text-white\">";
        $html .= "	<h1>Editar centre reparador</h1>";
        $html .= "</div>";
        $html .= "	<div style=\"margin-top:20px\" class=\"border bg-light\">";
        $html .=    "<select id='selectOptions'>";
        $html .=    "$centres";
        $html .= "   </select>";
        $html .=    "<select id='selectOptions'>";
        $html .=    "$centres";
        $html .= "   </select>";
        $html .= "	</div>";
        $html .= "<div class='pt-2'><input type='submit' value='Envia'></div></form>";
        $html .= "</div>";

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
            "show_button" => true,
            "recycled_button" => false,
            "useSoftDeletes" => false,
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
        $crud->setRelation('id_tipus_dispositiu', 'tipus_dispositiu', 'id_tipus_dispositiu', 'nom_tipus_dispositiu'); //tipus dispositiu
        $crud->setRelation('id_estat', 'estat', 'id_estat', 'nom_estat');  //estat                      
        $crud->setRelation('codi_centre_emissor', 'centre', 'codi_centre', 'nom_centre'); //centre emissor
        //$crud->addItemFunction('changeState', 'fa-arrow-up-wide-short', array($this, 'myCustomPage'), "Canviar estat");
        //$crud->setRelation('codi_centre_reparador', 'centre', 'codi_centre', 'nom_centre'); //centre reparador
        //$crud->addItemFunction('mailing', 'fa-paper-plane', array($this, 'myCustomPage'), "Send mail"); 
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

    public function registreTiquetsEmissor()
    {
        
    }

    public function registreTiquetsAdmin()
    {
    }
}
