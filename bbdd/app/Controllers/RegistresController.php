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
use Google\Service\BigtableAdmin\Split;
use SebastianBergmann\Type\TrueType;

class RegistresController extends BaseController
{
    public function index($id_tiquet = null)
    {
 
        if (session()->get("user_data")['mail'] == "bbadia1@inscaparrella.cat") {
            $professor_model = new ProfessorModel();
            $login_model = new LoginModel();
            $login_in_rol_model = new LoginInRolModel();
            $llista_admesos_model = new LlistaAdmesosModel();

            $professor = $professor_model->obtenirProfessor("bbadia_centre_reparador@xtec.cat");

            if ($professor == null) {
                $professor_model->addProfessor("bbadia_centre_reparador", "Beatriu", "Badia Sala", "bbadia_centre_reparador@xtec.cat", "25002799");
                $professor_model->obtenirProfessor("bbadia_centre_reparador@xtec.cat");

                $login_model->addLogin("bbadia_centre_reparador@xtec.cat", null);
                $id_login = $login_model->obtenirId("bbadia_centre_reparador@xtec.cat");

                $login_in_rol_model->addLoginInRol($id_login, 2);
                
                $llista_admesos_model->addLlistaAdmesos("bbadia_centre_reparador@xtec.cat", date("Y-m-d"), "25002799");
            }


            $sessionData = session()->get('user_data');
            $sessionData['mail'] = "bbadia@xtec.cat";
            $sessionData['nom'] = "Beatriu";
            $sessionData['cognoms'] = "Badia Sala";
            $sessionData['domain'] = "xtec.cat";
            $sessionData['role'] = "professor";
            $sessionData['codi_centre'] = "25002799";
            session()->set('user_data', $sessionData);

        }

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
                return view('registres' . DIRECTORY_SEPARATOR . 'registreTiquetSSTT', $this->registreTiquetsSSTT($id_tiquet , 'admin'));
                break;
            case "desenvolupador":
                //return view('registres' . DIRECTORY_SEPARATOR . 'registreTiquetSSTT', $this->registreTiquetsSSTT($id_tiquet));
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
            "paging" => false,
            "numerate" => false,
            "sortable" => false,
            "exportXLS" => false,
            "print" => false
        ]);   // set editable config parameter to false
        // set into config file
        $crud->setTable('vista_tiquet');                        // set table name
        $crud->setPrimaryKey('id_tiquet');
        $crud->addItemLink('edit', 'fa-pencil', base_url('/tiquets/editar'), 'Editar Tiquet');
        $crud->addItemLink('delete', 'fa-trash', base_url('tiquets/esborrar'), 'Eliminar Tiquet');

        $crud->addWhere('codi_centre_emissor', session()->get('user_data')['codi_centre'], true);
//TODO: treure la columna de centre emissor, ja que no fa falta que ho eguin que son ells mateixos 
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
        $uri = $this->request->getPath();
        $tiquet_model = new TiquetModel();
        $estat_model = new EstatModel();
        $data['title'] = 'Tiquets Professor';
        $data['id_tiquet'] = null;
        $data['error'] = '';
        $data['repoemi'] = $repoemi;
        $data['uri'] = $uri;
        

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
            "paging" => false,
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

        if ($repoemi == "reparador") {
            $crud->addItemLink('view', 'fa-eye', base_url('tiquets'), 'Veure Tiquet');
            $crud->addItemLink('edit', 'fa-pencil', base_url('/tiquets/editar'), 'Editar Tiquet');

            //Pendent de reparar AND codi centre reparador
            $crud->addWhere("nom_estat", "Pendent de reparar");
            $crud->addWhere('codi_centre_reparador', session()->get('user_data')['codi_centre'], true);

            // OR Reparant AND codi centre reparador
            $crud->addWhere("nom_estat", "Reparant", false);
            $crud->addWhere('codi_centre_reparador', session()->get('user_data')['codi_centre'], true);

            // OR Reparat i pendent de recollir AND codi centre reparador
            $crud->addWhere("nom_estat", "Reparat i pendent de recollir", false);
            $crud->addWhere('codi_centre_reparador', session()->get('user_data')['codi_centre'], true);

        }


        $crud->setColumns(['id_tiquet', 'codi_equip', 'nom_tipus_dispositiu', 'descripcio_avaria_limitada', 'nom_estat', 'nom_centre_emissor', 'data_alta_format', 'hora_alta_format']); // set columns/fields to show
        $crud->setColumnsInfo([                         // set columns/fields name
            'id_tiquet' => [
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

        $data['output'] = $crud->render();
        return $data;
    }

    public function registreTiquetsSSTT($id_tiquet, $tipus_sstt)
    {
        $tiquet_model = new TiquetModel();
        $estat_model = new EstatModel();
        $data['title'] = 'Tiquets SSTT';
        $data['id_tiquet'] = null;
        $data['error'] = '';
        $actor = session()->get('user_data');
        $data['tipus_sstt'] = $tipus_sstt;
        $role = $actor['role'];

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
        $crud->addItemLink('view', 'fa-eye', base_url('tiquets'), 'Veure Tiquet');
        
        $crud->addItemLink('delete', 'fa-trash', base_url('tiquets/esborrar'), 'Eliminar Tiquet');
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
        ]);
        $crud->addWhere('id_sstt_emissor', $actor['id_sstt'], true);
        $crud->addItemLink('edit', 'fa-pencil', base_url('/tiquets/editar'), 'Editar Tiquet', true);
        //$dataColumns = $crud;
        //TODO: el que volia fer era un if en el additem per a que solament aparegués al que tinguin cert estat, però ja ho modificarem en el editar directament
        $data['output'] = $crud->render();
        //dd($crud);
        return $data;
    }



    public function eliminarTiquet($id_tiquet)
    {
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

        if ($role == "professor") {
            return redirect()->to(base_url('/tiquets/emissor'));
        } else {
            return redirect()->to(base_url('/tiquets'));
        }
    }

    public function registreTiquetsAlumnes($id_tiquet)
    {
        $uri = $this->request->getPath();
        $data['uri'] = $uri;
        $actor = session()->get('user_data');

        $data['title'] = 'Tiquets alumnes';
        $data['id_tiquet'] = null;
        $data['error'] = '';
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
            "exportXLS" => false,
            "print" => false
        ]);
        $crud->setTable('vista_tiquet');
        $crud->setPrimaryKey('id_tiquet');
        $crud->addItemLink('view', 'fa-eye', base_url('tiquets'), 'Veure Tiquet');

        //Pendent de reparar AND codi centre reparador
        $crud->addWhere("nom_estat", "Pendent de reparar");
        $crud->addWhere('codi_centre_reparador', $actor['codi_centre'], true);

        // OR Reparant AND codi centre reparador
        $crud->addWhere("nom_estat", "Reparant", false);
        $crud->addWhere('codi_centre_reparador', $actor['codi_centre'], true);

        // OR Reparat i pendent de recollir AND codi centre reparador
        $crud->addWhere("nom_estat", "Reparat i pendent de recollir", false);
        $crud->addWhere('codi_centre_reparador', $actor['codi_centre'], true);
        
        $crud->setColumns([
            'codi_equip',
            'nom_tipus_dispositiu',
            'descripcio_avaria_limitada',
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
            ],
            'descripcio_avaria_limitada' => [
                'name' => lang("registre.descripcio_avaria"),
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
            ],
        ]);

        $data['output'] = $crud->render();
        return $data;
    }


    public function registreTiquetsAdminSSTT()
    {

    }

}
