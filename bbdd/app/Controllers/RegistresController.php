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
        $crud->setConfig('onlyView');
        $crud->setColumnsInfo([
        'id' => ['name' => 'Code id'],
        'email' => [
            'name' => 'eMail', 
            'html_atts' => [
                'required',
                'placeholder="Introduce email address"'
            ], 
            'type' => KpaCrud::EMAIL_FIELD_TYPE
        ],
        'username' => [
            'name' => 'User name',
            'type' => KpaCrud::TEXTAREA_FIELD_TYPE,
            'html_atts' => [
                "required", 
                "placeholder=\"Introduce user name\""
            ],
        ],
        // 'active' => [
        //     'type' => KpaCrud::DROPDOWN_FIELD_TYPE,
        //     'options' => ['' => "Select option", 'User disabled', 'User active'],
        //     'html_atts' => [
        //         "required",
        //     ],
        //     'default'=>'1'
        // ],
        'active' => [
            'type' => KpaCrud::CHECKBOX_FIELD_TYPE,
            'html_atts' => [
                "required",
            ],
            'default'=>'1',
            'check_value' => '1',
            'uncheck_value' => '0'
        ],

        // 'force_pass_reset' => [
        //     'name' => 'Force reset password',
        //     'type' => KpaCrud::CHECKBOX_FIELD_TYPE,
        //     'default'=>'1',
        //     'check_value' => '1',
        //     'uncheck_value' => '0'
        // ],
        'force_pass_reset' => [
            'name' => 'Force reset password',
            'type' => KpaCrud::DROPDOWN_FIELD_TYPE,
            'options' => ['' => "Select option", 'Password doesn\'t change', 'Change password'],
            'html_atts' => [
                "required",
            ],
            'default'=>'0',
        ],

        'reset_expires' => [
            'type' => KpaCrud::DATE_FIELD_TYPE,
            'default'=> date('Y-m-d', strtotime(date("d-m-Y"). ' + 6 days'))
            
        ],
        'activate_hash' => ['type' => KpaCrud::INVISIBLE_FIELD_TYPE],
        'password_hash' => ['type' => KpaCrud::INVISIBLE_FIELD_TYPE],
        'reset_hash' => ['type' => KpaCrud::INVISIBLE_FIELD_TYPE],
        'reset_at' => ['type' => KpaCrud::INVISIBLE_FIELD_TYPE],
        'status' => ['type' => KpaCrud::INVISIBLE_FIELD_TYPE],
        'status_message' => ['type' => KpaCrud::INVISIBLE_FIELD_TYPE],

    ]);
    $data['output'] = $crud->render();
        return view('kpacrud/exemplecrud', $data);*/
    }

    public function registreTiquetsProfessor()
    {
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
        $crud->addItemFunction('mailing', 'fa-paper-plane', array($this, 'myCustomPage'), "Send mail");
        //$crud->addItemLink('view', 'fa-file-o', base_url('route/to/link'), 'Tooltip for icon button');   
        $crud->setColumns(['codi_equip', 'tipus_dispositiu__nom_tipus_dispositiu', 'descripcio_avaria', 'estat__nom_estat', 'centre__nom_centre', 'id_tiquet']); // set columns/fields to show
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
            'id_tiquet' => [
                'name' => 'Demo text field',
                'type' => KpaCrud::DROPDOWN_FIELD_TYPE,
                'options' => ["" => "Select option", "Disabled", "Active"],
                'html_atts' => [
                    "required",
                ]
            ],

        ]);

        //$crud->addWhere('blog.blog_id!="1"'); // show filtered data
        $data['output'] = $crud->render();          // renders view
        return view('registres/registreTiquetSSTT', $data);
    }


    public function myCustomPage($obj)
    {
        $this->request->getUri()->stripQuery('customf');
        $this->request->getUri()->addQuery('customf', 'mpost');

        $html = "<div class=\"container-lg p-4\">";
        $html .= "<form method='post' action='" . base_url($this->request->getPath()) . "?" . $this->request->getUri()->getQuery() . "'>";
        $html .= csrf_field()  . "<input type='hidden' name='test' value='ToSend'>";
        $html .= "<div class=\"bg-secondary p-2 text-white\">";
        $html .= "	<h1>View item</h1>";
        $html .= "</div>";
        $html .= "	<div style=\"margin-top:20px\" class=\"border bg-light\">";
        $html .=    "<select>";
        $html .=        "<option value='value1'>Value 1</option>";
        $html .=        "<option value='value2' selected>Value 2</option>";
        $html .= "<option value='value3'>Value 3</option>";
        $html .= "</select>";
        $html .= "	</div>";
        $html .= "<div class='pt-2'><input type='submit' value='Envia'></div></form>";
        $html .= "</div>";

        // You can load view info from view file and return to KpaCrud library
        // $html = view('view_route/view_name');

        return $html;
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
