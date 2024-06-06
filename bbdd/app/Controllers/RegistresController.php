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
use App\Models\ProfessorModel;
use App\Models\LlistaAdmesosModel;
use App\Models\SSTTModel;
use App\Models\TipusDispositiuModel;
use DateTime;
use Google\Service\BigtableAdmin\Split;
use Google\Service\CloudSearch\PushItem;
use SebastianBergmann\Type\TrueType;

class RegistresController extends BaseController
{

    public function index($id_tiquet = null)
    {

        $role = session()->get('user_data')['role'];

        switch ($role) {
            case "alumne":
                return view('registres' . DIRECTORY_SEPARATOR . 'registreTiquetsAlumnes', $this->registreTiquetsAlumnes($id_tiquet));
                break;
            case "professor":
                return view('registres' . DIRECTORY_SEPARATOR . 'registreTiquetsProfessor', $this->registreTiquetsProfessor('reparador', $id_tiquet));
                break;
            case "centre_emissor":
                return view('registres' . DIRECTORY_SEPARATOR . 'registreTiquetsCentreEmissor', $this->registreTiquetsCentre($id_tiquet, 'emissor'));
                break;
            case "centre_reparador":
                return view('registres' . DIRECTORY_SEPARATOR . 'registreTiquetsCentreEmissor', $this->registreTiquetsCentre($id_tiquet, 'reparador'));
                break;
            case "sstt":
                return view('registres' . DIRECTORY_SEPARATOR . 'registreTiquetSSTT', $this->registreTiquetsSSTT($id_tiquet, 'sstt'));
                break;
            case "admin_sstt":
                return view('registres' . DIRECTORY_SEPARATOR . 'registreTiquetSSTT', $this->registreTiquetsSSTT($id_tiquet, 'admin'));
                break;
            case "desenvolupador":
                return view('registres' . DIRECTORY_SEPARATOR . 'registreTiquetSSTT', $this->registreTiquetsSSTT($id_tiquet, 'desenvolupador'));
                break;
            default:
                break;
        }
    }

    public function index2($id_tiquet = null)
    {
        $role = session()->get('user_data')['role'];
        if ($role == 'professor' || $role == 'centre_emissor') {
            return view('registres' . DIRECTORY_SEPARATOR . 'registreTiquetsProfessor', $this->registreTiquetsProfessor('emissor', $id_tiquet));
        } else {
            return redirect()->route('tiquets');
        }
    }

    public function registreTiquetsCentre($id_tiquet, $tipus_centre)
    {
        $tiquet_model = new TiquetModel();
        $estat_model = new EstatModel();
        $data['title'] = 'Tiquets Professor';
        $data['id_tiquet'] = null;
        $data['error'] = '';
        //TODO: quan estigui fet el formulari de canviar les dades de centre que el botó només el pugui veure el centre_reparador
        $data['tipus_centre'] = $tipus_centre;

        $actor = session()->get('user_data');
        $data['role'] = $actor['role'];

        if ($id_tiquet != null) {

            // Dades per a la gestió de rols
            $tiquet = $tiquet_model->getTiquetById($id_tiquet);
            $role = session()->get('user_data')['role'];

            $codi_centre = session()->get('user_data')['codi_centre'];
            $estat = $estat_model->obtenirEstatPerId($tiquet['id_estat']);

            if ((($role == "centre_emissor" || $role == "professor" || $role == "centre_reparador") && $estat == "Pendent de recollir" && $codi_centre == $tiquet['codi_centre_emissor']) || ($role == "sstt" && $estat == "Pendent de recollir") || $role == "admin_sstt" || $role == "desenvolupador") {
                //Preguntar a la bbdd quin tiquet es i retornar l'array del tiquet.
                $data['id_tiquet'] = $id_tiquet;
                session()->setFlashdata("tiquet", $tiquet_model->getTiquetById($id_tiquet));
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
            "paging" => true,
            "numerate" => false,
            "sortable" => false,
            "exportXLS" => false,
            "print" => false
        ]);   // set editable config parameter to false
        // set into config file
        $crud->setTable('vista_tiquet');                        // set table name
        $crud->setPrimaryKey('id_tiquet');
        $crud->hideHeadLink([
            'js-bootstrap',
            'css-bootstrap',
        ]);
        $crud->addItemLink('edit', 'fa-pencil', base_url('/tiquets/editar'), 'Editar Tiquet');
        $crud->addItemLink('delete', 'fa-trash', base_url('tiquets/esborrar'), 'Eliminar Tiquet');

        $crud->addWhere('codi_centre_emissor', session()->get('user_data')['codi_centre'], true);
        //TODO: treure la columna de centre emissor, ja que no fa falta que ho eguin que son ells mateixos 
        $crud->setColumns(['id_tiquet_limitat', 'codi_equip', 'nom_tipus_dispositiu', 'descripcio_avaria_limitada', 'nom_estat', 'nom_centre_emissor', 'data_alta_format', 'hora_alta_format']); // set columns/fields to show
        $crud->setColumnsInfo([                         // set columns/fields name
            'id_tiquet_limitat' => [
                'name' => lang("registre.id_tiquet"),
                'type' => KpaCrud::INVISIBLE_FIELD_TYPE,
            ],
            'codi_equip' => [
                'name' => lang("registre.codi_equip")
            ],
            'nom_tipus_dispositiu' => [
                'name' => lang("registre.tipus_dispositiu"),
                'type' => KpaCrud::DROPDOWN_FIELD_TYPE,
                'options' => [
                    "1" => "Pantalla",
                    "2" => "Ordenador",
                    "3" => "Projector",
                    "4" => "Movil",
                    "5" => "Tablet",
                    "6" => "Portatil",
                    "7" => "Servidor",
                    "8" => "Altaveu",
                    "9" => "Dispositius multimedia",
                    "10" => "Impressora",
                ],
                'html_atts' => [
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
        $session_filtre = session()->get('filtres');
        $data['session_filtre'] = $session_filtre;

        $uri = $this->request->getPath();
        $tiquet_model = new TiquetModel();
        $estat_model = new EstatModel();
        $data['title'] = 'Tiquets Professor';
        $data['id_tiquet'] = null;
        $data['error'] = '';
        $data['repoemi'] = $repoemi;
        $data['uri'] = $uri;

        $actor = session()->get('user_data');
        $data['role'] = $actor['role'];

        $model_centre = new CentreModel();
        $dades_centre = $model_centre->obtenirCentre($actor['codi_centre']);
        $actor['id_sstt'] = $dades_centre['id_sstt'];

        $data['tipus_dispositius'] = $this->selectorTipusDispositiu();
        $data['estats'] = $this->selectorEstat();
        $data['centre_emissor'] = $this->selectorCentreEmissor($data['role'], $actor);

        if ($id_tiquet != null) {

            // Dades per a la gestió de rols
            $tiquet = $tiquet_model->getTiquetById($id_tiquet);
            $role = session()->get('user_data')['role'];
            $codi_centre = session()->get('user_data')['codi_centre'];
            $estat = $estat_model->obtenirEstatPerId($tiquet['id_estat']);

            if ((($role == "centre_emissor" || $role == "professor" || $role == "centre_reparador") && $estat == "Pendent de recollir" && $codi_centre == $tiquet['codi_centre_emissor']) || ($role == "sstt" && $estat == "Pendent de recollir") || $role == "admin_sstt" || $role == "desenvolupador") {
                //Preguntar a la bbdd quin tiquet es i retornar l'array del tiquet.
                $data['id_tiquet'] = $id_tiquet;
                session()->setFlashdata("tiquet", $tiquet_model->getTiquetById($id_tiquet));
            } else {
                $data['error'] = 'registre.no_permisos_eliminar';
            }
        }
        $crud = new KpaCrud();                          // loads default configuration    
        $crud->setConfig('onlyView');                   // sets configuration to onlyView
        $crud->hideHeadLink([
            'js-bootstrap',
            'css-bootstrap',
        ]);
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
            "sortable" => true,
            "exportXLS" => false,
            "print" => false
        ]);   // set editable config parameter to false
        // set into config file
        $crud->setTable('vista_tiquet');                        // set table name
        $crud->setPrimaryKey('id_tiquet');

        if ($repoemi == "emissor") {

            $crud->addItemLink('edit', 'fa-pencil', base_url('/tiquets/editar'), 'Editar Tiquet');
            $crud->addItemLink('delete', 'fa-trash', base_url('tiquets/emissor/esborrar'), 'Eliminar Tiquet');

            $crud->addWhere('codi_centre_emissor', session()->get('user_data')['codi_centre']);
        }

        $crud->setColumns([
            'id_tiquet_limitat',
            'codi_equip',
            'nom_tipus_dispositiu',
            'descripcio_avaria_limitada',
            'nom_estat',
            'nom_centre_emissor',
            'data_alta_format',
            'hora_alta_format'
        ]); // set columns/fields to show
        $crud->setColumnsInfo([                         // set columns/fields name
            'id_tiquet_limitat' => [
                'name' => lang("registre.id_tiquet"),
                'type' => KpaCrud::INVISIBLE_FIELD_TYPE,
            ],
            'codi_equip' => [
                'name' => lang("registre.codi_equip")
            ],
            'nom_tipus_dispositiu' => [
                'name' => lang("registre.tipus_dispositiu"),
                'type' => KpaCrud::DROPDOWN_FIELD_TYPE,
                'options' => [
                    "1" => "Pantalla",
                    "2" => "Ordenador",
                    "3" => "Projector",
                    "4" => "Movil",
                    "5" => "Tablet",
                    "6" => "Portatil",
                    "7" => "Servidor",
                    "8" => "Altaveu",
                    "9" => "Dispositius multimedia",
                    "10" => "Impressora",
                ],
                'html_atts' => [
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


        if ($repoemi == "reparador") {
            $crud->addItemLink('view', 'fa-eye', base_url('tiquets'), 'Veure Tiquet');
            $crud->addItemLink('edit', 'fa-pencil', base_url('/tiquets/editar'), 'Editar Tiquet');

            if (isset($session_filtre['tipus_dispositiu'])) {
                $crud->addWhere('nom_tipus_dispositiu', $session_filtre['tipus_dispositiu'][0]);
            }
            if (isset($session_filtre['estat'])) {
                $model_estat = new EstatModel();
                $estat_escollit = $model_estat->obtenirEstatPerId($session_filtre['estat'][0]);
                
                $data['estat_escollit'] = $estat_escollit;
                $crud->addWhere('id_estat', $session_filtre['estat'][0]);
            }
            if (isset($session_filtre['nom_centre_emissor'])) {
                $model_centre = new CentreModel();
                $centre_emissor_escollit = $model_centre->obtenirCentre($session_filtre['nom_centre_emissor'][0]);
                $data['centre_emissor_escollit'] = $centre_emissor_escollit;
                $crud->addWhere('codi_centre_emissor', $session_filtre['nom_centre_emissor'][0], true);
            }
            if (isset($session_filtre['data_creacio'])) {
                $data_de_la_sessio = $session_filtre['data_creacio'][0];
                $data_nova = date('d-m-Y', strtotime($data_de_la_sessio));

                $crud->addWhere('data_alta_format', $data_nova);
            }
            $crud->addWhere("codi_centre_reparador='" . session()->get('user_data')['codi_centre'] . "' AND (nom_estat='Pendent de reparar' or nom_estat='Reparant' or nom_estat='Reparat i pendent de recollir')");
        }


        $data['output'] = $crud->render();
        return $data;
    }

    public function registreTiquetsSSTT($id_tiquet, $tipus_sstt)
    {

        $session_filtre = session()->get('filtres');
        $data['session_filtre'] = $session_filtre;
        $tiquet_model = new TiquetModel();
        $estat_model = new EstatModel();
        $data['title'] = 'Tiquets SSTT';
        $data['id_tiquet'] = null;
        $data['error'] = '';
        $actor = session()->get('user_data');
        $data['tipus_sstt'] = $tipus_sstt;
        $role = $actor['role'];
        $data['role'] = $role;

        $data['tipus_dispositius'] = $this->selectorTipusDispositiu();
        $data['estats'] = $this->selectorEstat();
        $data['centre_emissor'] = $this->selectorCentreEmissor($role, $actor);
        $data['centre_reparador'] = $this->selectorCentreReparador($role, $actor);

        if ($id_tiquet != null) {

            // Dades per a la gestió de rols
            $tiquet = $tiquet_model->getTiquetById($id_tiquet);
            $codi_centre = session()->get('user_data')['codi_centre'];

            $estat = $estat_model->obtenirEstatPerId($tiquet['id_estat']);
            if ((($role == "centre_emissor" || $role == "professor" || $role == "centre_reparador") && $estat == "Pendent de recollir" && $codi_centre == $tiquet['codi_centre_emissor']) || ($role == "sstt" && $estat == "Pendent de recollir") || $role == "admin_sstt" || $role == "desenvolupador") {
                //Preguntar a la bbdd quin tiquet es i retornar l'array del tiquet.
                $data['id_tiquet'] = $id_tiquet;
                session()->setFlashdata("tiquet", $tiquet_model->getTiquetById($id_tiquet));
            } else {
                $data['error'] = 'registre.no_permisos_eliminar';
            }
        }

        $crud = new KpaCrud();
        $crud->setConfig('onlyView');
        $crud->hideHeadLink([
            'js-bootstrap',
            'css-bootstrap',
        ]);
        $crud->setConfig([
            "numerate" => false,
            "add_button" => false,
            "show_button" => false,
            "recycled_button" => false,
            "useSoftDeletes" => true,
            "multidelete" => false,
            "filterable" => true,
            "editable" => false,
            "removable" => false,
            "paging" => true,
            "numerate" => false,
            "sortable" => true,
            "exportXLS" => true,
            "print" => false
        ]);
        $crud->setTable('vista_tiquet');
        $crud->setPrimaryKey('id_tiquet');
        $crud->addItemLink('view', 'fa-eye', base_url('tiquets'), 'Veure Tiquet');

        $crud->addItemLink('delete', 'fa-trash', base_url('tiquets/esborrar'), 'Eliminar Tiquet');
        $crud->setColumns([
            'id_tiquet_limitat',
            'codi_equip',
            'nom_tipus_dispositiu',
            'descripcio_avaria_limitada',
            'nom_centre_emissor',
            'nom_centre_reparador',
            'nom_estat',
            'data_alta_format',
            'hora_alta_format',
            'preu_total'
        ]);
        $crud->setColumnsInfo([
            'id_tiquet_limitat' => [
                'name' => lang("registre.id_tiquet"),
                'type' => KpaCrud::INVISIBLE_FIELD_TYPE,
            ],
            'codi_equip' => [
                'name' => lang("registre.codi_equip")
            ],
            'nom_tipus_dispositiu' => [
                'name' => lang("registre.tipus_dispositiu"),
                'type' => KpaCrud::DROPDOWN_FIELD_TYPE,
                'options' => [
                    "1" => "Pantalla",
                    "2" => "Ordenador",
                    "3" => "Projector",
                    "4" => "Movil",
                    "5" => "Tablet",
                    "6" => "Portatil",
                    "7" => "Servidor",
                    "8" => "Altaveu",
                    "9" => "Dispositius multimedia",
                    "10" => "Impressora",
                ],
                'html_atts' => [
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
                    "1" => "Pendent de recollir",
                    "2" => "Emmagatzemat a SSTT",
                    "3" => "Pendent de reparar",
                    "4" => "Reparant",
                    "5" => "Reparat i pendent de recollir",
                    "6" => "Pendent de retorn",
                    "7" => "Retornat",
                    "8" => "Rebutjat per SSTT",
                    "9" => "Desguassat",
                ],
                'html_atts' => [
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
            'preu_total' => [
                'name' => lang("registre.preu_total"),
            ],
        ]);

        if ($tipus_sstt !== 'desenvolupador') {
            $crud->addWhere("id_sstt_emissor='" . $actor['id_sstt'] . "' OR id_sstt_reparador='" . $actor['id_sstt'] . "' OR id_sstt='" . $actor['id_sstt'] . "'");
        }

        $crud->addItemLink('edit', 'fa-pencil', base_url('/tiquets/editar'), 'Editar Tiquet', true);


        if ($role == "sstt" || $role == "admin_sstt" || $role == "desenvolupador") {
            $crud->addItemLink('pdf', 'fa-file-pdf', base_url('/tiquets/pdf'), 'Tiquet PDF', true);
        }
        

        if (is_array($session_filtre)) {
            if (isset($session_filtre['tipus_dispositiu'])) {
                $crud->addWhere('nom_tipus_dispositiu', $session_filtre['tipus_dispositiu'][0], true);
            }
            if (isset($session_filtre['estat'])) {
                $model_estat = new EstatModel();
                $estat_escollit = $model_estat->obtenirEstatPerId($session_filtre['estat'][0]);
                
                $data['estat_escollit'] = $estat_escollit;
                $crud->addWhere('id_estat', $session_filtre['estat'][0], true);
            }
            if (isset($session_filtre['nom_centre_emissor'])) {
                $model_centre = new CentreModel();
                $centre_emissor_escollit = $model_centre->obtenirCentre($session_filtre['nom_centre_emissor'][0]);
                $data['centre_emissor_escollit'] = $centre_emissor_escollit;

                $crud->addWhere('codi_centre_emissor', $session_filtre['nom_centre_emissor'][0], true);
            }
            if (isset($session_filtre['nom_centre_reparador'])) {
                $model_centre = new CentreModel();
                $centre_reparador_escollit = $model_centre->obtenirCentre($session_filtre['nom_centre_reparador'][0]);
                $data['centre_reparador_escollit'] = $centre_reparador_escollit;

                $crud->addWhere('codi_centre_reparador', $session_filtre['nom_centre_reparador'][0], true);
            }
            if (isset($session_filtre['data_creacio'])) {

                //Ara funciona, pero el KpaCrud ha estat donant moltissims problemes.
                $data_de_la_sessio = $session_filtre['data_creacio'][0];
                $data_nova = date('d-m-Y', strtotime($data_de_la_sessio));
                $crud->addWhere('data_alta_format', $data_nova, true);
            }
        }

        $data['output'] = $crud->render();
        return $data;
    }



    public function eliminarTiquet($id_tiquet)
    {
        $tiquet_model = new TiquetModel();
        $estat_model = new EstatModel();

        // Dades per a la gestió de rols
        $tiquet = $tiquet_model->getTiquetById($id_tiquet);
        $role = session()->get('user_data')['role'];
        $data['role'] = $role;
        $codi_centre = session()->get('user_data')['codi_centre'];

        if ($tiquet != null) {
            $estat = $estat_model->obtenirEstatPerId($tiquet['id_estat']);

            // Gestió de rols
            if ((($role == "centre_emissor" || $role == "professor" || $role == "centre_reparador") && $estat == "Pendent de recollir" && $codi_centre == $tiquet['codi_centre_emissor']) || ($role == "sstt" && $estat == "Pendent de recollir") || $role == "admin_sstt" || $role == "desenvolupador") {
                $model_tiquet = new TiquetModel();
                $model_tiquet->deleteTiquetById($id_tiquet);
                $msg = lang('alertes.flash_data_delete_tiquet') . $tiquet['id_tiquet'];
                session()->setFlashdata("tiquetEliminat", $msg);
            }
        }

        if ($role == "professor") {
            return redirect()->to(base_url('/tiquets/emissor'));
        } else {
            return redirect()->to(base_url('/tiquets'));
        }
    }

    public function registreTiquetsAlumnes($id_tiquet)
    {
        $session_filtre = session()->get('filtres');
        $data['session_filtre'] = $session_filtre;

        $uri = $this->request->getPath();
        $data['uri'] = $uri;
        $actor = session()->get('user_data');
        $data['role'] = $actor['role'];
        $data['title'] = 'Tiquets alumnes';
        $data['id_tiquet'] = null;
        $data['error'] = '';

        $model_centre = new CentreModel();
        $dades_centre = $model_centre->obtenirCentre($actor['codi_centre']);
        $actor['id_sstt'] = $dades_centre['id_sstt'];

        $data['tipus_dispositius'] = $this->selectorTipusDispositiu();
        $data['estats'] = $this->selectorEstat();
        $data['centre_emissor'] = $this->selectorCentreEmissor($data['role'], $actor);

        $crud = new KpaCrud();
        $crud->setConfig('onlyView');
        $crud->hideHeadLink([
            'js-bootstrap',
            'css-bootstrap',
        ]);
        $crud->setConfig([
            "numerate" => false,
            "add_button" => false,
            "show_button" => false,
            "recycled_button" => false,
            "useSoftDeletes" => true,
            "multidelete" => false,
            "filterable" => true,
            "editable" => false,
            "removable" => false,
            "paging" => true,
            "numerate" => false,
            "sortable" => true,
            "exportXLS" => false,
            "print" => false
        ]);
        $crud->setTable('vista_tiquet');
        $crud->setPrimaryKey('id_tiquet');
        $crud->addItemLink('view', 'fa-eye', base_url('tiquets'), 'Veure Tiquet');

        if (isset($session_filtre['tipus_dispositiu'])) {
            $crud->addWhere('nom_tipus_dispositiu', $session_filtre['tipus_dispositiu'][0]);
        }
        if (isset($session_filtre['estat'])) {
            $model_estat = new EstatModel();
            $estat_escollit = $model_estat->obtenirEstatPerId($session_filtre['estat'][0]);
            
            $data['estat_escollit'] = $estat_escollit;
            $crud->addWhere('id_estat', $session_filtre['estat'][0]);
        }
        if (isset($session_filtre['nom_centre_emissor'])) {
            $model_centre = new CentreModel();
            $centre_emissor_escollit = $model_centre->obtenirCentre($session_filtre['nom_centre_emissor'][0]);
            $data['centre_emissor_escollit'] = $centre_emissor_escollit;
            $crud->addWhere('codi_centre_emissor', $session_filtre['nom_centre_emissor'][0], true);
        }
        if (isset($session_filtre['data_creacio'])) {
            $data_de_la_sessio = $session_filtre['data_creacio'][0];
            $data_nova = date('d-m-Y', strtotime($data_de_la_sessio));

            $crud->addWhere('data_alta_format', $data_nova);
        }

        $crud->addWhere("codi_centre_reparador='" . session()->get('user_data')['codi_centre'] . "' AND (nom_estat='Pendent de reparar' or nom_estat='Reparant' or nom_estat='Reparat i pendent de recollir')");

        $crud->setColumns([
            'id_tiquet_limitat',
            'codi_equip',
            'nom_tipus_dispositiu',
            'descripcio_avaria_limitada',
            'nom_estat',
            'nom_centre_emissor',
            'data_alta_format',
            'hora_alta_format'
        ]);
        $crud->setColumnsInfo([
            'id_tiquet_limitat' => [
                'name' => lang("registre.id_tiquet"),
                'type' => KpaCrud::INVISIBLE_FIELD_TYPE,
            ],
            'codi_equip' => [
                'name' => lang("registre.codi_equip")
            ],
            'nom_tipus_dispositiu' => [
                'name' => lang("registre.tipus_dispositiu"),
            ],
            'descripcio_avaria_limitada' => [
                'name' => lang("registre.descripcio_avaria"),
            ],
            'nom_estat' => [
                'name' => lang("registre.estat"),
            ],
            'nom_centre_emissor' => [
                'name' => lang("registre.centre"),
                'type' => KpaCrud::INVISIBLE_FIELD_TYPE,
            ],
            'data_alta_format' => [
                'name' => lang("registre.data_alta"),
                'type' => KpaCrud::DATETIME_FIELD_TYPE,
                'default' => '1-2-2022'
            ],
            'hora_alta_format' => [
                'name' => lang("registre.hora_alta"),
            ],
        ]);

        $data['output'] = $crud->render();
        return $data;
    }


    public function selectorTipusDispositiu()
    {
        $tipus_dispositius = new TipusDispositiuModel;
        $array_tipus_dispositius = $tipus_dispositius->getTipusDispositius();
        $array_tipus_dispositius_nom = [];
        $sessio_filtres = session()->get('filtres');

        $options_tipus_dispositius = "";
        $options_tipus_dispositius .= "<option value='' selected disabled>" . lang('registre.not_value_option_select_tipus_dispositiu') . "</option>";
        for ($i = 0; $i < sizeof($array_tipus_dispositius); $i++) {
            if (isset($sessio_filtres['tipus_dispositiu']) && $sessio_filtres['tipus_dispositiu'][0] == $array_tipus_dispositius[$i]['nom_tipus_dispositiu']) {
                $options_tipus_dispositius .= "<option value=\"" . $array_tipus_dispositius[$i]['nom_tipus_dispositiu'] . "\" selected>";
            } else {
                $options_tipus_dispositius .= "<option value=\"" . $array_tipus_dispositius[$i]['nom_tipus_dispositiu'] . "\">";
            }
            $options_tipus_dispositius .= $array_tipus_dispositius[$i]['nom_tipus_dispositiu'];
            $options_tipus_dispositius .= "</option>";
            $array_tipus_dispositius_nom[$i] = $array_tipus_dispositius[$i]['nom_tipus_dispositiu'];
        }

        return $options_tipus_dispositius;
    }

    public function selectorEstat()
    {
        $estats = new EstatModel();
        $array_estats = $estats->getEstats();
        $array_estats_nom = [];
        $sessio_filtres = session()->get('filtres');


        $options_estats = "";
        $options_estats .= "<option value='' selected disabled>" . lang('registre.not_value_option_select_estat') . "</option>";
        for ($i = 0; $i < sizeof($array_estats); $i++) {
            if (isset($sessio_filtres['estat']) && $sessio_filtres['estat'][0] == $array_estats[$i]['id_estat']) {
                $options_estats .= "<option value=\"" . $array_estats[$i]['id_estat'] . "\" selected>";
            } else {
                $options_estats .= "<option value=\"" . $array_estats[$i]['id_estat'] . "\">";
            }
            $options_estats .= $array_estats[$i]['nom_estat'];
            $options_estats .= "</option>";
            $array_estats_nom[$i] = $array_estats[$i]['nom_estat'];
        }

        return $options_estats;
    }

    public function selectorCentreEmissor($role, $actor)
    {
        $centre_model = new CentreModel();
        $array_centres = $centre_model->obtenirCentres();
        $options_tipus_dispositius_emissors = "";

        for ($i = 0; $i < sizeof($array_centres); $i++) {
            if ((($role == "sstt" || $role == "admin_sstt" || $role == 'professor' || $role == 'alumne' )  && $array_centres[$i]['id_sstt'] == $actor['id_sstt']) ) {
                $options_tipus_dispositius_emissors .= "<option value=\"" . $array_centres[$i]['codi_centre'] . " - " . $array_centres[$i]['nom_centre'] . "\">";
                $options_tipus_dispositius_emissors .= "</option>";
            } else if ($role == "desenvolupador") {
                $options_tipus_dispositius_emissors .= "<option value=\""  . $array_centres[$i]['codi_centre'] . " - " . $array_centres[$i]['nom_centre'] . "\">";
                $options_tipus_dispositius_emissors .= "</option>";
            }
        }
        return  $options_tipus_dispositius_emissors;
    }

    public function selectorCentreReparador($role, $actor)
    {
        $centre_model = new CentreModel();
        $array_centres = $centre_model->obtenirCentres();
        $options_tipus_dispositius_reparadors = "";

        for ($i = 0; $i < sizeof($array_centres); $i++) {
            if ($array_centres[$i]['taller'] == 1) {
                if (($role == "sstt" || $role == "admin_sstt") && $array_centres[$i]['id_sstt'] == $actor['id_sstt']) {
                    $options_tipus_dispositius_reparadors .= "<option value=\"" . $array_centres[$i]['codi_centre'] . " - " . $array_centres[$i]['nom_centre'] . "\">";
                    $options_tipus_dispositius_reparadors .= $array_centres[$i]['nom_centre'];
                    $options_tipus_dispositius_reparadors .= "</option>";
                } else if ($role == "desenvolupador") {
                    $options_tipus_dispositius_reparadors .= "<option value=\"" . $array_centres[$i]['codi_centre'] . " - " . $array_centres[$i]['nom_centre'] . "\">";
                    $options_tipus_dispositius_reparadors .= $array_centres[$i]['nom_centre'];
                    $options_tipus_dispositius_reparadors .= "</option>";
                }
            }
        }

        return $options_tipus_dispositius_reparadors;
    }

    public function filtrePost()
    {
        $centre_model = new CentreModel();

        $session = session();
        $sessio_filtres = $session->get('filtres');

        $eliminar = $this->request->getPost('submit_eliminar_filtres');

        if ($eliminar !== null) {
            $session->remove('filtres');
        } else {

            if ($sessio_filtres == null) {
                $filtres = [];
                $session->set('filtres', $filtres);
            }

            $dades = $this->request->getPost();

            if (isset($dades['selector_tipus_dispositiu'])) {
                $array_tipus_dispositiu = [];
                $tipus_dispositiu_seleccionat = $dades['selector_tipus_dispositiu'];
                array_push($array_tipus_dispositiu, $tipus_dispositiu_seleccionat);
                $session->push('filtres', ['tipus_dispositiu' => $array_tipus_dispositiu]);
            }
            if (isset($dades['selector_tipus_estat'])) {
                $array_estat = [];
                $tipus_estat_seleccionat = $dades['selector_tipus_estat'];
                array_push($array_estat, $tipus_estat_seleccionat);
                $session->push('filtres', ['estat' => $array_estat]);
            }
            if (isset($dades['nom_centre_emissor_list']) && $dades['nom_centre_emissor_list'] !== '') {

                $array_centre_emissor = [];
                $nom_centre_emissor = $dades['nom_centre_emissor_list'];
                $centre_emissor = trim(explode('-', (string) $nom_centre_emissor)[0]);

                // TODO Bea ficar alerta
                if ($centre_emissor != null && $centre_model->obtenirCentre($centre_emissor) == null) {
                    return redirect()->back()->withInput();
                }

                array_push($array_centre_emissor, $centre_emissor);
                $session->push('filtres', ['nom_centre_emissor' => $array_centre_emissor]);
            }
            if (isset($dades['nom_centre_reparador_list']) && $dades['nom_centre_reparador_list'] !== '') {

                $array_centre_reparador = [];
                $nom_centre_reparador = $dades['nom_centre_reparador_list'];
                $centre_reparador = trim(explode('-', (string) $nom_centre_reparador)[0]);

                // TODO Bea ficar alerta
                if ($centre_reparador != null && $centre_model->obtenirCentre($centre_reparador) == null) {
                    return redirect()->back()->withInput();
                }

                array_push($array_centre_reparador, $centre_reparador);
                $session->push('filtres', ['nom_centre_reparador' => $array_centre_reparador]);
            }
            if (isset($dades['data_creacio']) &&  $dades['data_creacio'] !== '') {
                $array_data_creacio = [];
                $data_creacio = $dades['data_creacio'];
                array_push($array_data_creacio, $data_creacio);
                $session->push('filtres', ['data_creacio' => $array_data_creacio]);
            }
        }
        return redirect()->back()->withInput();
    }

    public function eliminarFiltre()
    {
        $filtre_eliminar = $this->request->getPost();
        $filtre_session = session()->get('filtres');
        $eliminar = $this->request->getPost('submit_eliminar_filtres');

        if ($eliminar !== null) {
            session()->remove('filtres');
        }
        if ($filtre_eliminar['operacio'] === 'Dispositiu') {
            unset($filtre_session['tipus_dispositiu']);
            session()->set('filtres', $filtre_session);
        }
        if ($filtre_eliminar['operacio'] === 'Estat') {
            unset($filtre_session['estat']);
            session()->set('filtres', $filtre_session);
        }
        if ($filtre_eliminar['operacio'] == 'Centre_emissor') {
            unset($filtre_session['nom_centre_emissor']);
            session()->set('filtres', $filtre_session);
        }
        if ($filtre_eliminar['operacio'] == 'Centre_reparador') {
            unset($filtre_session['nom_centre_reparador']);
            session()->set('filtres', $filtre_session);
        }
        if ($filtre_eliminar['operacio'] == 'data_creacio') {
            unset($filtre_session['data_creacio']);
            session()->set('filtres', $filtre_session);
        }
        if (count($filtre_session) == 0) {
            session()->remove('filtres');
        }

        return redirect()->back()->withInput();
    }
}
