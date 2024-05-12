<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CentreModel;
use App\Models\EstatModel;
use App\Models\TipusDispositiuModel;
use App\Models\TiquetModel;
use SIENSIS\KpaCrud\Libraries\KpaCrud;

class TiquetController extends BaseController
{
    public function viewTiquet($id_tiquet)
    {
        $tiquet_model = new TiquetModel();
        $centre_model = new CentreModel();
        $tipus_dispositiu_model = new TipusDispositiuModel();
        $estat_model = new EstatModel();

        $actor = session()->get('user_data');
        $role = $actor['role'];
        $data['role'] = $role;

        $tiquet_existent = $tiquet_model->getTiquetById($id_tiquet);

        if ($tiquet_existent != null) {

            $estat = $estat_model->obtenirEstatPerId($tiquet_existent['id_estat']);

            if ($role == "centre_emissor" || $role == "centre_reparador") {

                return redirect()->to(base_url('/registreTiquet'));
    
            } else if ($role == "professor" && $estat != "Pendent de reparar" && $estat != "Reparant" && $estat != "Reparat i pendent de recollir") {

                return redirect()->to(base_url('/registreTiquet'));

            } else {
    
                $data['title'] = "Veure Tiquet";
                $data['id_tiquet'] = $id_tiquet;
        
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
                $crud->setTable('vista_intervencio');
                $crud->setPrimaryKey('id_intervencio');
                
                $crud->setColumns([
                    'id_intervencio',
                    'nom_tipus_intervencio',
                    'descripcio_intervencio_limitada',
                    'correu_alumne',
                    'id_xtec'
                ]);
                $crud->setColumnsInfo([
                    'id_intervencio' => [
                        'name' => lang('intervencio.id_intervencio')
                    ],
                    'nom_tipus_intervencio' => [
                        'name' => lang('intervencio.nom_tipus_intervencio')
                    ],
                    'descripcio_intervencio_limitada' => [
                        'name' => lang('intervencio.descripcio_intervencio_limitada')
                    ],
                    'correu_alumne' => [
                        'name' => lang('alumne.correu_alumne')
                    ],
                    'id_xtec' => [
                        'name' => lang('intervencio.id_xtec')
                    ],
                ]);
                
        
                $tiquets_resultat = [];
                if ($role == "professor" || $role == "alumne") {
                    $estats_professor = $estat_model->getProfessorEstats();
                    $estats_consulta = [];
                    for ($i = 0; $i < sizeof($estats_professor); $i++) {
                        array_push($estats_consulta, $estats_professor[$i]['id_estat']);
                    }
                    
                    $tiquets = $tiquet_model->getTiquetByCodiCentreReparadorEstat($actor['codi_centre']);

                    for ($j = 0; $j < sizeof($tiquets); $j++) {
                        if (in_array($tiquets[$j]['id_estat'], $estats_consulta)) {
                            array_push($tiquets_resultat, $tiquets[$j]);
                        }
                    }

                    $crud->addItemLink('delete', 'fa-trash', base_url('vistaTiquet/esborrar'), 'Eliminar Tiquet');

                    $crud->addWhere('id_tiquet', $id_tiquet);
                    $crud->addWhere ("estat_tiquet","Pendent de reparar", true);

                    $crud->addWhere('id_tiquet', $id_tiquet, false);
                    $crud->addWhere ("estat_tiquet","Reparant", true);

                    $crud->addWhere('id_tiquet', $id_tiquet, false);
                    $crud->addWhere ("estat_tiquet","Reparat i pendent de recollir", true);
    
                } else if ($role == "sstt" || $role == "admin_sstt") {

                    if ($role == "admin_sstt") {
                        $crud->addItemLink('delete', 'fa-trash', base_url('vistaTiquet/esborrar'), 'Eliminar Tiquet');
                    }
    
                    $crud->addWhere('id_tiquet', $id_tiquet);

                    $tiquets = $tiquet_model->getTiquets();

                    for ($i = 0; $i < sizeof($tiquets); $i++) {
                        $id_sstt_tiquet =  $centre_model->obtenirCentre($tiquets[$i]['codi_centre_emissor'])['id_sstt'];
                        
                        if ($id_sstt_tiquet == $actor['id_sstt']) {
                            array_push($tiquets_resultat, $tiquets[$i]);
                        }
                    }

                } else if ($role == "desenvolupador") {
                    $crud->addWhere('id_tiquet', $id_tiquet);

                    $tiquets = $tiquet_model->getTiquets();

                    $crud->addItemLink('delete', 'fa-trash', base_url('vistaTiquet/esborrar'), 'Eliminar Tiquet');
                }
    
                
                $options_tiquets = "";
                for ($i = 0; $i < sizeof($tiquets_resultat); $i++) {
                    $nom_tipus_dispositiu = $tipus_dispositiu_model->getNomTipusDispositiu($tiquets_resultat[$i]['id_tipus_dispositiu'])['nom_tipus_dispositiu'];
                    $tiquets_resultat[$i]['nom_tipus_dispositiu'] = $nom_tipus_dispositiu;

                    $options_tiquets .= "<option value=\"" . $tiquets_resultat[$i]['id_tiquet'] . " // " . $tiquets_resultat[$i]['nom_tipus_dispositiu'] . " // "  . $tiquets_resultat[$i]['codi_equip'] . "\">";
                    $options_tiquets .= $tiquets_resultat[$i]['codi_equip'] . " // " . $tiquets_resultat[$i]['nom_tipus_dispositiu'];
                    $options_tiquets .= "</option>";
                }
    
                $data['options_tiquets'] = $options_tiquets;
    
                $data['output'] = $crud->render();
                return view('tiquet' . DIRECTORY_SEPARATOR . 'vistaTiquet', $data);
    
            }

        } else {

            return redirect()->to('registreTiquet');

        }


    }

    public function viewTiquet_post() {
        $input = $this->request->getPost('tiquet_seleccionat');

        $id_tiquet = trim(explode('//', (string) $input)[0]);

        return redirect()->to(base_url('/vistaTiquet/' . $id_tiquet));
    }

    public function createTiquet_post()
    {
        $data['title'] = "login";

        $csv = $this->request->getFiles();

        $uuid_library = new \App\Libraries\UUID;


        if ($csv['csv_tiquet'] == null) {
            $validationRules = [
                'sNomContacteCentre' => [
                    'rules'  => 'required',
                    'errors' => [
                        'required' => lang('general_lang.sNomContacteCentre_required'),
                    ],
                ],            
                'sCorreuContacteCentre' => [
                    'rules'  => 'required',
                    'errors' => [
                        'required' => lang('general_lang.sCorreuContacteCentre_required'),
                    ],
                ],
            ];
        }

        if ($csv['csv_tiquet'] != null || $this->validate($validationRules)) { 
            $tiquet_model = new TiquetModel;

            $nom_persona_contacte_centre = $this->request->getPost('sNomContacteCentre');
            $correu_persona_contacte_centre = $this->request->getPost('sCorreuContacteCentre');
            $data_alta = date("Y-m-d H:i:s");
            $centre_emissor = session()->get('user_data')['codi_centre'];
            $role = session()->get('user_data')['role'];
            
            //$imagefile = $this->request->getFiles();    és el csv
           
            
            if ($csv['csv_tiquet'] != "") {

                $codi_professor = "1";

                // Carreguem el fitxer a writable/uploads
                if ($csv) {
                    if ($this->request->getFiles()) {
                        $file = $csv['csv_tiquet'];
                        if ($file->isValid() && !$file->hasMoved()) {

                            $newName = $file->getClientName();

                            $ruta = WRITEPATH . "uploads" . DIRECTORY_SEPARATOR . $centre_emissor;
                            if (!is_dir($ruta)) {
                                mkdir($ruta, 0755);
                            }

                            $ruta = WRITEPATH . "uploads" . DIRECTORY_SEPARATOR . $centre_emissor . DIRECTORY_SEPARATOR . $codi_professor;
                            if (!is_dir($ruta)) {
                                mkdir($ruta, 0755);
                            }

                            $file->move($ruta, $newName);

                            $ruta = $ruta . DIRECTORY_SEPARATOR . $newName;

                            // Llegim el fitxer i afegim dades a la base de dades
                            $csvFile = fopen($ruta, "r"); // read file from /writable/uploads folder.

                            $firstline = true;
                    
                            while (($csv_data = fgetcsv($csvFile, 2000, ";")) !== FALSE) {
                                if (!$firstline) {
                                    $model = new \App\Models\TiquetModel;

                                    $uuid = $uuid_library->v4();
                                    if ($role == "professor" || $role == "centre_emissor" || $role == "centre_reparador") {
                                        $model->addTiquet($uuid,$csv_data[1], $csv_data[2], $csv_data[3], $csv_data[4], $data_alta, null, $csv_data[7], $csv_data[8], $centre_emissor, null);
                                    } else if ($role == "sstt" || $role == "admin_sstt" || $role == "desenvolupador") {
                                        $model->addTiquet($uuid,$csv_data[0], $csv_data[1], $csv_data[2], $csv_data[3], $data_alta, null, $csv_data[4], $csv_data[5], $csv_data[6], $csv_data[7]);
                                    }

                                }
                                $firstline = false;
                            }
                    
                            fclose($csvFile);
                        }
                    }
                }

            } else {
                $num_tiquets = $this->request->getPost('num_tiquets');
                // Validem camps            
                for ($i = 1; $i <= intval($num_tiquets); $i++) {
                    $codi_equip = $this->request->getPost('equipment_code_' . $i);
                    $tipus = $this->request->getPost('type_' . $i);
                    $problem = $this->request->getPost('problem_' . $i);

                    if ($codi_equip == "" || $tipus == "" || $problem == "") {
                        return redirect()->back()->withInput();
                    }
                }

                if ($centre_emissor == "no_codi"){
                    if ($role != "professor" && $role != "centre_emissor") {                    
                        $centre_emissor = $this->request->getPost('centre_emissor');
                        $centre_reparador = $this->request->getPost('centre_reparador');
                        $centre_emissor = trim(explode('-', (string) $centre_emissor)[0]);
                        $centre_reparador = trim(explode('-', (string) $centre_reparador)[0]);
                    } 
    
                    if ($centre_emissor == "") {
                        $centre_emissor = null;
                    }
    
                    if ($centre_reparador == "") {
                        $centre_reparador = null;
                    }
                }

                for ($i = 1; $i <= $num_tiquets; $i++) {
                    $codi_equip = $this->request->getPost('equipment_code_' . $i);
                    $tipus = $this->request->getPost('type_' . $i);
                    $problem = $this->request->getPost('problem_' . $i);

                    $uuid = $uuid_library->v4();
                    if ($role == "professor" || $role == "centre_emissor") {
                        $tiquet_model->addTiquet($uuid,$codi_equip, $problem, $nom_persona_contacte_centre, $correu_persona_contacte_centre, $data_alta, null, $tipus, 1, $centre_emissor, null);
                    } else {
                        $tiquet_model->addTiquet($uuid,$codi_equip, $problem, $nom_persona_contacte_centre, $correu_persona_contacte_centre, $data_alta, null, $tipus, 1, $centre_emissor, $centre_reparador);
                    }
                    
                }
            }


        }
        
        if ($role == "professor") {
            return redirect()->to(base_url('/registreTiquet/emissor'));
        } else {
            return redirect()->to(base_url('/registreTiquet'));
        }
    }

    /**
     * Funció que ens dirigeix cap al formulari per crear un tiquet
     *
     * @author Blai Burgués Vicente
     */
    public function createTiquet() 
    {
        $role = session()->get('user_data')['role'];
        $data['role'] = $role;

        if ($role == "alumne") {
            return redirect()->to(base_url('/registreTiquet'));
        }

        $tipus_dispositius = new TipusDispositiuModel;
        $array_tipus_dispositius = $tipus_dispositius->getTipusDispositius();
        $array_tipus_dispositius_nom = [];

        $options_tipus_dispositius = "";
        for ($i = 0; $i < sizeof($array_tipus_dispositius); $i++) {
            $options_tipus_dispositius .= "<option value=" . ($i+1) . ">";
            $options_tipus_dispositius .= $array_tipus_dispositius[$i]['nom_tipus_dispositiu'];
            $options_tipus_dispositius .= "</option>";
            $array_tipus_dispositius_nom[$i] = $array_tipus_dispositius[$i]['nom_tipus_dispositiu'];
        }

        $data['tipus_dispositius'] = $options_tipus_dispositius;
        $data['json_tipus_dispositius'] = json_encode($array_tipus_dispositius_nom);


        if(session()->get('user_data')['role'] == "desenvolupador" || session()->get('user_data')['role'] == "admin_sstt" || session()->get('user_data')['role'] == "sstt") {
            $centre_model = new CentreModel();
            $array_centres = $centre_model->obtenirCentres();
            $options_tipus_dispositius_emissors = "";
            $options_tipus_dispositius_reparadors = "";
            for ($i = 0; $i < sizeof($array_centres); $i++) {
                $options_tipus_dispositius_emissors .= "<option value='" . $array_centres[$i]['codi_centre'] . " - " . $array_centres[$i]['nom_centre'] . "'>";
                $options_tipus_dispositius_emissors .= $array_centres[$i]['nom_centre'];
                $options_tipus_dispositius_emissors .= "</option>";
    
                if ($array_centres[$i]['taller'] == 1) {
                    $options_tipus_dispositius_reparadors .= "<option value='" . $array_centres[$i]['codi_centre'] . " - " . $array_centres[$i]['nom_centre'] . "'>";
                    $options_tipus_dispositius_reparadors .= $array_centres[$i]['nom_centre'];
                    $options_tipus_dispositius_reparadors .= "</option>";
                }
            }
            $data['centres_emissors'] = $options_tipus_dispositius_emissors;
            $data['centres_reparadors'] = $options_tipus_dispositius_reparadors;
        }


        // TREURE AIXÒ
        //dd(session()->get('user_data'));
        $codi_centre = session()->get('user_data')['codi_centre'];

        if ($codi_centre != "no_codi") {
            $centre = new CentreModel;
            $data['nom_persona_contacte_centre'] = $centre->obtenirNomResponsable($codi_centre);
            $data['correu_persona_contacte_centre'] = $centre->obtenirCorreuResponsable($codi_centre);
        } else {
            $data['nom_persona_contacte_centre'] = null;
            $data['correu_persona_contacte_centre'] = null;
        }

        $data['title'] = lang('general_lang.formulari_tiquet');
        return view('formularis' . DIRECTORY_SEPARATOR .'formulariTiquet', $data);
    }

        /**
     * Funció que ens dirigeix cap al formulari per crear un tiquet
     *
     * @author Blai Burgués Vicente
     */
    public function descarregar($arxiu) 
    {
        $role = session()->get('user_data')['role'];

        if ($arxiu == "exemple_afegir_tiquet") {

            if ($role == "professor" || $role == "centre_emissor" || $role == "centre_reparador") {
                $file = new \CodeIgniter\Files\File(WRITEPATH . "uploads" . DIRECTORY_SEPARATOR . "csv" . DIRECTORY_SEPARATOR . "exemple_afegir_tiquet_professorat.csv"); // Definim el nom de l'arxiu amb ruta
                $file_name = "exemple_afegir_tiquet_professorat.csv";
            } else if ($role == "sstt" || $role == "admin_sstt" || $role == "desenvolupador") {
                $file = new \CodeIgniter\Files\File(WRITEPATH . "uploads" . DIRECTORY_SEPARATOR . "csv" . DIRECTORY_SEPARATOR . "exemple_afegir_tiquet_sstt.csv"); // Definim el nom de l'arxiu amb ruta
                $file_name = "exemple_afegir_tiquet_sstt.csv";
            }
            
            // En cas que no es tracti d'un fitxer llencem que no s'ha trobat
            if (!$file->isFile()){
                throw new \CodeIgniter\Exceptions\PageNotFoundException("El fitxer CSV d'exemple per afegir tiquets no ha estat trobat!");
            }

            // Llegim l'arxiu i donem resposta
            $filedata = new \SplFileObject($file->getPathname(), "r");
            $data1 = $filedata->fread($filedata->getSize());
            //return $this->response->setContentType($file->getMimeType())->setBody($data1);
            return $this->response->download($file_name, "\xEF\xBB\xBF" . $data1, $file->getMimeType());

        }
    }

    
    /**
     * Funció que ens dirigeix cap al formulari per editar un tiquet
     *
     * @author Blai Burgués Vicente
     */
    public function editarTiquet($id_tiquet) 
    {
        $data['title'] = "Editar Tiquet";

        $tiquet_model = new TiquetModel();
        $centre_model = new CentreModel();
        $tipus_dispositius = new TipusDispositiuModel;
        $estat_model = new EstatModel();

        $role = session()->get('user_data')['role'];
        $data['role'] = $role;

        if ($role == "alumne") {
            return redirect()->to(base_url('/registreTiquet'));
        }

        $data['tiquet'] = $tiquet_model->getTiquetById($id_tiquet);

        $array_tipus_dispositius = $tipus_dispositius->getTipusDispositius();
        $array_tipus_dispositius_nom = [];

        $options_tipus_dispositius = "";
        for ($i = 0; $i < sizeof($array_tipus_dispositius); $i++) {
            if (($i+1) != $data['tiquet']['id_tipus_dispositiu']) {
                $options_tipus_dispositius .= "<option value=" . ($i+1) . ">";
            } else {
                $options_tipus_dispositius .= "<option value=" . ($i+1) . " selected>";
            }
            $options_tipus_dispositius .= $array_tipus_dispositius[$i]['nom_tipus_dispositiu'];
            $options_tipus_dispositius .= "</option>";
            $array_tipus_dispositius_nom[$i] = $array_tipus_dispositius[$i]['nom_tipus_dispositiu'];
        }

        $data['tipus_dispositius'] = $options_tipus_dispositius;
        $data['json_tipus_dispositius'] = json_encode($array_tipus_dispositius_nom);

        $array_centres = $centre_model->obtenirCentres();
        $options_tipus_dispositius_emissors = "";
        $options_tipus_dispositius_reparadors = "";
        $data['centre_emissor_selected'] = null;
        $data['centre_reparador_selected'] = null;
        for ($i = 0; $i < sizeof($array_centres); $i++) {

            if ($data['tiquet']['codi_centre_emissor'] != null && $data['tiquet']['codi_centre_emissor'] == $array_centres[$i]['codi_centre']) {
                $data['centre_emissor_selected'] = $array_centres[$i]['codi_centre'] . " - " . $array_centres[$i]['nom_centre'];
                $options_tipus_dispositius_emissors .= "<option value='" . $array_centres[$i]['codi_centre'] . " - " . $array_centres[$i]['nom_centre'] . "' selected>";
            } else {
                $options_tipus_dispositius_emissors .= "<option value='" . $array_centres[$i]['codi_centre'] . " - " . $array_centres[$i]['nom_centre'] . "'>";
            }

            $options_tipus_dispositius_emissors .= $array_centres[$i]['nom_centre'];
            $options_tipus_dispositius_emissors .= "</option>";

            if ($array_centres[$i]['taller'] == 1) {
                if ($data['tiquet']['codi_centre_reparador'] != null && $data['tiquet']['codi_centre_reparador'] == $array_centres[$i]['codi_centre']) {
                    $data['centre_reparador_selected'] = $array_centres[$i]['codi_centre'] . " - " . $array_centres[$i]['nom_centre'];
                    $options_tipus_dispositius_reparadors .= "<option value='" . $array_centres[$i]['codi_centre'] . " - " . $array_centres[$i]['nom_centre'] . "' selected>";
                } else {
                    $options_tipus_dispositius_reparadors .= "<option value='" . $array_centres[$i]['codi_centre'] . " - " . $array_centres[$i]['nom_centre'] . "'>";
                }

                $options_tipus_dispositius_reparadors .= $array_centres[$i]['nom_centre'];
                $options_tipus_dispositius_reparadors .= "</option>";
            }
        }
        $data['centres_emissors'] = $options_tipus_dispositius_emissors;
        $data['centres_reparadors'] = $options_tipus_dispositius_reparadors;

        session()->setFlashdata('id_tiquet', $id_tiquet);

        $estat = $estat_model->obtenirEstatPerId($data['tiquet']['id_estat']);
        $codi_centre = session()->get('user_data')['codi_centre'];
        $codi_centre_emissor = $data['tiquet']['codi_centre_emissor'];
        $codi_centre_reparador = $data['tiquet']['codi_centre_reparador'];
        
        // Controlem si la informació pot ser editable
        $data['informacio_editable'] = "";
        $data['informacio_required'] = "required";
        session()->setFlashdata('required', true);
        if (($role == "centre_emissor" || $role == "professor" || $role == "centre_reparador") && ($estat != "Pendent de recollir" || $codi_centre != $codi_centre_emissor)) {
            $data['informacio_editable'] = "disabled";
            $data['informacio_required'] = "";
            session()->setFlashdata('required', false);
        }
        if ($role == "sstt" && $estat != "Pendent de recollir" && $estat != "Emmagatzemat a SSTT" && $estat != "Assignat i pendent de reparar") {
            $data['informacio_editable'] = "disabled";
            $data['informacio_required'] = "";
            session()->setFlashdata('required', false);
        }


        // Dades per controlar l'estat en cas del professor es pugui editar i els estats que es poden editar
        $array_estat_professor = $estat_model->getProfessorEstats();+
        $estat_reparacio = false;
        for ($j = 0; $j < sizeof($array_estat_professor); $j++) {
            if ($data['tiquet']['id_estat'] == $array_estat_professor[$j]['id_estat']) {
                $estat_reparacio = true;
            }
        }

        // Controlem si l'estat és editable
        $data['estat_ediatble'] = "";
        if ($role == "centre_emissor" || $role == "centre_reparador" || ($role == "professor" && $codi_centre != $codi_centre_reparador) || ($role == "professor" && !$estat_reparacio && $codi_centre == $codi_centre_reparador)) {
            $data['estat_ediatble'] = "disabled";
        }


        if ($role == "professor" && $estat_reparacio && $codi_centre == $codi_centre_reparador) {

            $array_estat = $estat_model->getEstats();
            $options_estat = "";
            for ($i = 0; $i < sizeof($array_estat_professor); $i++) {
                
                if ($array_estat_professor[$i]['id_estat'] != $data['tiquet']['id_estat']) {
                    $options_estat .= "<option value='" . $array_estat_professor[$i]['id_estat'] . "'>";
                } else {
                    $options_estat .= "<option value='" . $array_estat_professor[$i]['id_estat'] . "' selected>";
                }
    
                $options_estat .= $array_estat_professor[$i]['nom_estat'];
                $options_estat .= "</option>";

            }

            $data['estats'] = $options_estat;
            
        } else {
            
            if ($role == "professor" && $estat == "Pendent de recollir") {
                $data['estat_ediatble'] = "";
                $options_estat = "<option value='" . 1 . "' selected>Pendent de recollir</option>";
            } else {
                $array_estat = $estat_model->getEstats();
                $options_estat = "";
                for ($i = 0; $i < sizeof($array_estat); $i++) {
                    
                    if (($i+1) != $data['tiquet']['id_estat']) {
                        $options_estat .= "<option value='" . ($i+1) . "'>";
                    } else {
                        $options_estat .= "<option value='" . ($i+1) . "' selected>";
                    }
        
                    $options_estat .= $array_estat[$i]['nom_estat'];
                    $options_estat .= "</option>";
                }
            }

            $data['estats'] = $options_estat;
        }


        
        return view('formularis' . DIRECTORY_SEPARATOR .'formulariEditarTiquet', $data);
    }

    /**
     * Funció per editar un tiquet
     *
     * @author Blai Burgués Vicente
     */
    public function editarTiquet_post() 
    {
        $tiquet_model = new TiquetModel();
        $estat_model = new EstatModel();
        $role = session()->get('user_data')['role'];

        $required = session()->getFlashdata('required');

        if ($required) {
            $validationRules = [
                'sNomContacteCentre' => [
                    'rules'  => 'required|max_length[64]',
                    'errors' => [
                        'required' => lang('general_lang.sNomContacteCentre_required'),
                        'max_length' => lang('general_lang.errors_validation.sNomContacteCentre_max'),
                    ],
                ],            
                'sCorreuContacteCentre' => [
                    'rules'  => 'required|max_length[32]',
                    'errors' => [
                        'required' => lang('general_lang.sCorreuContacteCentre_required'),
                        'max_length' => lang('general_lang.errors_validation.sCorreuContacteCentre_max'),
                    ],
                ],
                'equipment_code' => [
                    'rules' => 'required|max_length[32]',
                    'errors' => [
                        'required' => lang('general_lang.errors_validation.equipment_code_required'),
                        'max_length' => lang('general_lang.errors_validation.equipment_code_max'),
                    ],
                ],
                'problem' => [
                    'rules' => 'required|max_length[512]',
                    'errors' => [
                        'required' => lang('general_lang.errors_validation.problem_required'),
                        'max_length' => lang('general_lang.errors_validation.problem_max'),
                    ],
                ],
            ];
        } else {
            $validationRules = [
                'sNomContacteCentre' => [
                    'rules'  => 'max_length[64]',
                    'errors' => [
                        'max_length' => lang('general_lang.errors_validation.sNomContacteCentre_max'),
                    ],
                ],            
                'sCorreuContacteCentre' => [
                    'rules'  => 'max_length[32]',
                    'errors' => [
                        'max_length' => lang('general_lang.errors_validation.sCorreuContacteCentre_max'),
                    ],
                ],
                'equipment_code' => [
                    'rules' => 'max_length[32]',
                    'errors' => [
                        'max_length' => lang('general_lang.errors_validation.equipment_code_max'),
                    ],
                ],
                'problem' => [
                    'rules' => 'max_length[512]',
                    'errors' => [
                        'max_length' => lang('general_lang.errors_validation.problem_max'),
                    ],
                ],
            ];
        }


        if ($this->validate($validationRules)) {
            $error = true;

            if ($role == "professor" || $role == "centre_reparador" || $role == "centre_emissor") {

                $tiquet = $tiquet_model->getTiquetById(session()->getFlashdata('id_tiquet'));
                $estat_origen = $estat_model->obtenirEstatPerId($tiquet['id_estat']);
                $codi_centre = session()->get('user_data')['codi_centre'];
                $codi_centre_emissor = $tiquet['codi_centre_emissor'];
                $codi_centre_reparador = $tiquet['codi_centre_reparador'];
            

                $nom_contacte_centre = $this->request->getPost('sNomContacteCentre');
                $correu_contacte_centre = $this->request->getPost('sCorreuContacteCentre');
                $codi_equip = $this->request->getPost('equipment_code');
                $tipus_dispositiu = $this->request->getPost('type');
                $descripcio_avaria = $this->request->getPost('problem');
                $estat_desti = $this->request->getPost('estat');
                $estat_desti = $estat_model->obtenirEstatPerId($estat_desti);
    
                $data = [
                    "nom_persona_contacte_centre" => $nom_contacte_centre,
                    "correu_persona_contacte_centre" => $correu_contacte_centre,
                    "codi_equip" => $codi_equip,
                    "id_tipus_dispositiu" => $tipus_dispositiu,
                    "descripcio_avaria" => $descripcio_avaria
                ];

                // Controlem que es puguin modificar les dades
                if (($role == "centre_emissor" || $role == "professor" || $role == "centre_reparador") && $estat_origen == "Pendent de recollir" && $codi_centre == $codi_centre_emissor && $estat_desti == "Pendent de recollir") {
                    $tiquet_model->updateTiquet(session()->getFlashdata('id_tiquet'), $data);
                    $error = false;
                }


                // Controlem que es pugui modificar l'estat
                
                $data = [
                    "id_estat" => $estat_desti,
                ];

                $array_estat_professor = $estat_model->getProfessorEstats();
                $estat_reparacio_origen = false;
                $estat_reparacio_desti = false;
                for ($j = 0; $j < sizeof($array_estat_professor); $j++) {
                    if ($tiquet['id_estat'] == $array_estat_professor[$j]['id_estat']) {
                        $estat_reparacio_origen = true;
                    }
                    if ($estat_desti == $array_estat_professor[$j]['id_estat']) {
                        $estat_reparacio_desti = true;
                    }
                }
            
                if ($role == "professor" && $estat_reparacio_origen && $estat_reparacio_desti && $codi_centre == $codi_centre_reparador) {
                    $tiquet_model->updateTiquet(session()->getFlashdata('id_tiquet'), $data);
                    $error = false;
                } 

            } else if ($role == "sstt" || $role == "admin_sstt" || $role == "desenvolupador") {

                $tiquet = $tiquet_model->getTiquetById(session()->getFlashdata('id_tiquet'));
                $estat_origen = $estat_model->obtenirEstatPerId($tiquet['id_estat']);
                $codi_centre = session()->get('user_data')['codi_centre'];
                $codi_centre_emissor = $tiquet['codi_centre_emissor'];
                $codi_centre_reparador = $tiquet['codi_centre_reparador'];

                $nom_contacte_centre = $this->request->getPost('sNomContacteCentre');
                $correu_contacte_centre = $this->request->getPost('sCorreuContacteCentre');
                $centre_emissor = $this->request->getPost('centre_emissor');
                $centre_reparador = $this->request->getPost('centre_reparador');
                $codi_equip = $this->request->getPost('equipment_code');
                $tipus_dispositiu = $this->request->getPost('type');
                $estat_desti = $this->request->getPost('estat');
                $descripcio_avaria = $this->request->getPost('problem');
    
                $centre_emissor = trim(explode('-', (string) $centre_emissor)[0]);
                $centre_reparador = trim(explode('-', (string) $centre_reparador)[0]);

                $data = [
                    "nom_persona_contacte_centre" => $nom_contacte_centre,
                    "correu_persona_contacte_centre" => $correu_contacte_centre,
                    "codi_equip" => $codi_equip,
                    "id_tipus_dispositiu" => $tipus_dispositiu,
                    "id_estat" => $estat_desti,
                    "descripcio_avaria" => $descripcio_avaria
                ];

                if ($centre_emissor != "") {
                    $data['codi_centre_emissor'] = $centre_emissor;
                }

                if ($centre_reparador != "") {
                    $data['codi_centre_reparador'] = $centre_reparador;
                }

                // Controlem que es puguin modificar les dades
                if ($role == "sstt" && ($estat_origen == "Pendent de recollir" || $estat_origen == "Emmagatzemat a SSTT" || $estat_origen == "Assignat i pendent de reparar")) {
                    $tiquet_model->updateTiquet(session()->getFlashdata('id_tiquet'), $data);
                    $error = false;
                } else if ($role == "sstt") { // Controlem el cas en què sstt no pot editar infromació, però sempre estat
                    $data = [
                        "id_estat" => $estat_desti,
                    ];
                    $tiquet_model->updateTiquet(session()->getFlashdata('id_tiquet'), $data);
                    $error = false;
                }

                if ($role == "admin_sstt" || $role == "desenvolupador") {
                    $tiquet_model->updateTiquet(session()->getFlashdata('id_tiquet'), $data);
                    $error = false;
                } 


            }

            
        } else {
            return redirect()->back()->withInput();
        }

        if ($error) {
            return redirect()->to(base_url('/editarTiquet/' . session()->getFlashdata('id_tiquet')));
        } else {
            if ($role == "professor") {
                return redirect()->to(base_url('/registreTiquet/emissor'));
            } else {
                return redirect()->to(base_url('/registreTiquet'));
            }
        }
        
    }
}
