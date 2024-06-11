<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CentreModel;
use App\Models\EstatModel;
use App\Models\IntervencioModel;
use App\Models\InventariModel;
use App\Models\SSTTModel;
use App\Models\TipusDispositiuModel;
use App\Models\TipusIntervencioModel;
use App\Models\TipusInventariModel;
use App\Models\TiquetModel;
use Google\Service\BackupforGKE\Backup;
use SIENSIS\KpaCrud\Libraries\KpaCrud;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;


class TiquetController extends BaseController
{

    protected $helpers = ['form'];

    public function viewTiquet($id_tiquet, $id_intervencio = null)
    {

        $tiquet_model = new TiquetModel();
        $centre_model = new CentreModel();
        $tipus_dispositiu_model = new TipusDispositiuModel();
        $estat_model = new EstatModel();
        $intervencio_model = new IntervencioModel();
        $tipus_intervencio_model = new TipusIntervencioModel();

        $actor = session()->get('user_data');
        $role = $actor['role'];
        $data['role'] = $role;

        $tiquet_existent = $tiquet_model->getTiquetById($id_tiquet);
        $data['tiquet'] = $tiquet_existent;
        $data['tipus_dispositiu'] = $tipus_dispositiu_model->getNomTipusDispositiu($tiquet_existent['id_tipus_dispositiu'])['nom_tipus_dispositiu'];
        $data['estat_editable'] = "";

        if ($tiquet_existent != null) {

            $estat = $estat_model->obtenirEstatPerId($tiquet_existent['id_estat']);

            if ($role == "centre_emissor" || $role == "centre_reparador") {

                return redirect()->to(base_url('/tiquets'));
            } else if ($role == "professor" && $estat != "Pendent de reparar" && $estat != "Reparant" && $estat != "Reparat i pendent de recollir") {

                return redirect()->to(base_url('/tiquets'));
            } else {

                $data['title'] = "Veure Tiquet";
                $data['id_tiquet'] = $id_tiquet;
                $data['id_intervencio'] = session()->getFlashdata('id_intervencio');

                if ($data['id_intervencio'] != null) {
                    $data['id_intervencio'] = $data['id_intervencio']['id_intervencio'];
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
                    "filterable" => false,
                    "editable" => false,
                    "removable" => false,
                    "paging" => true,
                    "numerate" => false,
                    "sortable" => true,
                    "exportXLS" => false,
                    "print" => false
                ]);
                $crud->setTable('vista_intervencio');
                $crud->setPrimaryKey('id_intervencio');

                $crud->setColumns([
                    'id_intervencio_limitat',
                    'nom_tipus_intervencio',
                    'descripcio_intervencio_limitada',
                    'correu_alumne',
                    'id_xtec',
                ]);
                $crud->setColumnsInfo([
                    'id_intervencio_limitat' => [
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

                if ($id_intervencio != null) {
                    $intervencio = $intervencio_model->obtenirIntervencioPerId($id_intervencio);
                    if ($intervencio != null) {
                        $crud->addWhere('id_intervencio', $id_intervencio);
                    }
                }

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

                    $crud->addItemLink('view', 'fa-eye', base_url('tiquets/' . $id_tiquet . '/intervencio'), 'Veure Intervenció');

                    if ($estat == 'Pendent de reparar' || $estat == 'Reparant') {
                        $crud->addItemLink('edit', 'fa-pencil', base_url('editar/intervencio/' . $id_tiquet), 'Editar Intervenció');
                        //$crud->addItemLink('assignar', 'fa-screwdriver-wrench', base_url('tiquets/' . $id_tiquet . '/assignar'), 'Assignar Inventari');
                        $crud->addItemLink('delete', 'fa-trash', base_url('tiquets/' . $id_tiquet . '/esborrar'), 'Eliminar Intervenció');
                    }

                    $crud->addWhere("id_tiquet='" . $id_tiquet . "' AND (estat_tiquet='Pendent de reparar' or estat_tiquet='Reparant' or estat_tiquet='Reparat i pendent de recollir')");
                } else if ($role == "sstt" || $role == "admin_sstt") {

                    $crud->addItemLink('view', 'fa-eye', base_url('tiquets/' . $id_tiquet . '/intervencio'), 'Veure Intervenció');

                    if ($role == "admin_sstt") {
                        $crud->addItemLink('edit', 'fa-pencil', base_url('editar/intervencio/' . $id_tiquet), 'Editar Intervenció');
                        //$crud->addItemLink('assignar', 'fa-screwdriver-wrench', base_url('tiquets/' . $id_tiquet . '/assignar'), 'Assignar Inventari');
                        $crud->addItemLink('delete', 'fa-trash', base_url('tiquets/' . $id_tiquet . '/esborrar'), 'Eliminar Intervenció');
                    }

                    $crud->addWhere('id_tiquet', $id_tiquet);

                    $tiquets = $tiquet_model->getTiquets();

                    for ($i = 0; $i < sizeof($tiquets); $i++) {
                        if ($tiquets[$i]['codi_centre_emissor'] != null) {
                            $id_sstt_tiquet =  $centre_model->obtenirCentre($tiquets[$i]['codi_centre_emissor'])['id_sstt'];
                        } else if ($tiquets[$i]['codi_centre_reparador']) {
                            $id_sstt_tiquet =  $centre_model->obtenirCentre($tiquets[$i]['codi_centre_emissor'])['id_sstt'];
                        } else {
                            $id_sstt_tiquet = $tiquets[$i]['id_sstt'];
                        }
                        

                        if ($id_sstt_tiquet == $actor['id_sstt']) {
                            array_push($tiquets_resultat, $tiquets[$i]);
                        }
                    }
                } else if ($role == "desenvolupador") {
                    $crud->addWhere('id_tiquet', $id_tiquet);

                    $tiquets = $tiquet_model->getTiquets();

                    $crud->addItemLink('view', 'fa-eye', base_url('tiquets/' . $id_tiquet . '/intervencio'), 'Veure Intervenció');
                    $crud->addItemLink('edit', 'fa-pencil', base_url('editar/intervencio/' . $id_tiquet), 'Editar Intervenció');
                    //$crud->addItemLink('assignar', 'fa-screwdriver-wrench', base_url('tiquets/' . $id_tiquet . '/assignar'), 'Assignar Inventari');
                    $crud->addItemLink('delete', 'fa-trash', base_url('tiquets/' . $id_tiquet . '/esborrar'), 'Eliminar Intervenció');
                }


                $options_tiquets = "";
                for ($i = 0; $i < sizeof($tiquets_resultat); $i++) {
                    $nom_tipus_dispositiu = $tipus_dispositiu_model->getNomTipusDispositiu($tiquets_resultat[$i]['id_tipus_dispositiu'])['nom_tipus_dispositiu'];
                    $tiquets_resultat[$i]['nom_tipus_dispositiu'] = $nom_tipus_dispositiu;

                    $options_tiquets .= "<option value=\"" . explode("-", $tiquets_resultat[$i]['id_tiquet'])[4] . " // " . $tiquets_resultat[$i]['nom_tipus_dispositiu'] . " // "  . $tiquets_resultat[$i]['codi_equip'] . " // "  . $tiquets_resultat[$i]['id_tiquet'] . "\">";
                    $options_tiquets .= $tiquets_resultat[$i]['codi_equip'] . " // " . $tiquets_resultat[$i]['nom_tipus_dispositiu'];
                    $options_tiquets .= "</option>";
                }

                $data['options_tiquets'] = $options_tiquets;



                if ($role == "alumne") {

                    session()->setFlashdata('id_tiquet_alumne', $tiquet_existent['id_tiquet']);

                    $id_pendent_recollir = $estat_model->obtenirIdPerEstat("Pendent de recollir");
                    $id_assignat_pendent_recollir = $estat_model->obtenirIdPerEstat("Assignat i pendent de recollir");
                    $id_emmagatzemat_sstt = $estat_model->obtenirIdPerEstat("Emmagatzemat a SSTT");
                    $id_assignat_emmagatzemat_sstt = $estat_model->obtenirIdPerEstat("Assignat i emmagatzemat a SSTT");
                    $id_pendent_reparar = $estat_model->obtenirIdPerEstat("Pendent de reparar");
                    $id_reparant = $estat_model->obtenirIdPerEstat("Reparant");
                    $id_reparat_pendent_recollir = $estat_model->obtenirIdPerEstat("Reparat i pendent de recollir");
                    $id_pendent_retorn  = $estat_model->obtenirIdPerEstat("Pendent de retorn");
                    $id_retornat = $estat_model->obtenirIdPerEstat("Retornat");
                    $id_rebutjat_per_sstt = $estat_model->obtenirIdPerEstat("Rebutjat per SSTT");
                    $id_desguassat  = $estat_model->obtenirIdPerEstat("Desguassat");

                    $estat_tiquet = $estat_model->obtenirEstatPerId($tiquet_existent['id_estat']);


                    if ($estat_tiquet == "Pendent de reparar") {

                        $options_estat = "<option value='" . $id_pendent_reparar . "' selected>Pendent de reparar</option>
                                            <option value='" . $id_reparant . "'>Reparant</option>";
                    } else if ($estat_tiquet == "Reparant") {
                        $options_estat = "<option value='" . $id_reparant . "' selected>Reparant</option>
                        <option value='" . $id_pendent_reparar . "'>Pendent de reparar</option>";
                    } else {
                        $options_estat = "<option value='" . $tiquet_existent['id_estat'] . "' selected>" . $estat_model->obtenirEstatPerId($tiquet_existent['id_estat']) . "</option>";
                        $data['estat_editable'] = "disabled";
                    }

                    $data['estats'] = $options_estat;
                } else {
                    $options_estat = "<option value='" . $tiquet_existent['id_estat'] . "' selected>" . $estat_model->obtenirEstatPerId($tiquet_existent['id_estat']) . "</option>";
                    $data['estat_editable'] = "disabled";
                    $data['estats'] = $options_estat;
                }

                $data['nom_centre_emissor'] = "";
                $data['nom_centre_reparador'] = "";
                if ($tiquet_existent['codi_centre_emissor'] != null) {
                    $data['nom_centre_emissor'] = $centre_model->obtenirCentre($tiquet_existent['codi_centre_emissor'])['nom_centre'];
                }
                if ($tiquet_existent['codi_centre_reparador'] != null) {
                    $data['nom_centre_reparador'] = $centre_model->obtenirCentre($tiquet_existent['codi_centre_reparador'])['nom_centre'];
                }


                $data['id_intervencio_vista'] = null;
                $kpacrud2 = false;
                if ($id_intervencio != null) {
                    $intervencio = $intervencio_model->obtenirIntervencioPerId($id_intervencio);

                    if ($intervencio != null) {

                        $data['id_intervencio_vista'] = $intervencio['id_intervencio'];
                        $data['nom_tipus_intervencio_vista'] = $tipus_intervencio_model->obtenirNomTipusIntervencio($intervencio['id_tipus_intervencio'])['nom_tipus_intervencio'];
                        $data['descripcio_intervencio_vista'] = $intervencio['descripcio_intervencio'];
                        $data['correu_alumne_vista'] = $intervencio['correu_alumne'];
                        $data['id_xtec_vista'] = $intervencio['id_xtec'];

                        $kpacrud2 = true;
                    }
                }


                if ($kpacrud2) {
                    $crud->addItemLink('view', 'fa-eye-slash', base_url('tiquets/' . $id_tiquet), 'Deixar de veure Intervenció');

                    // KPACRUD INVENTARI ASSIGNAT A LA INTERVENCIÓ

                    $crud2 = new KpaCrud();
                    $crud2->setConfig('onlyView');
                    $crud2->setConfig([
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
                    $crud2->setTable('vista_inventari');
                    $crud2->setPrimaryKey('id_inventari');
                    $crud2->hideHeadLink([
                        'js-bootstrap',
                        'css-bootstrap',
                    ]);
                    $crud2->setColumns([
                        'id_inventari_limitat',
                        'nom_tipus_inventari',
                        'data_compra'
                    ]);
                    $crud2->setColumnsInfo([
                        'id_inventari_limitat' => [
                            'name' => lang('inventari.id_inventari')
                        ],
                        'nom_tipus_inventari' => [
                            'name' => lang('inventari.nom_tipus_inventari')
                        ],
                        'data_compra' => [
                            'name' => lang('inventari.data_compra')
                        ]
                    ]);

                    if ($role == "alumne" || $role == "professor") {
                        $crud2->addWhere('codi_centre', $actor['codi_centre']);
                        $crud2->addWhere('id_intervencio', $id_intervencio, true);
                    } else if ($role == "sstt" || $role == "admin_sstt") {
                        $crud2->addWhere('id_sstt', $actor['id_sstt']);
                        $crud2->addWhere('id_intervencio', $id_intervencio, true);
                    } else if ($role == "desenvolupador") {
                        $crud2->addWhere('id_intervencio', $id_intervencio, true);
                    }


                    $data['output2'] = $crud2->render();
                }



                // Establim la forma de renderitzar la imatge
                $render = new ImageRenderer(
                    new RendererStyle(400),
                    new SvgImageBackEnd()
                );
                
                $writer = new Writer($render); // Establim el writer amb la forma de renderitzar imatges
        
                $data['qrcode_image2'] = base64_encode($writer->writeString(base_url($tiquet_existent['id_tiquet']))); // Generem el codi que ha d'anar a la imatge


                $data['output'] = $crud->render();
                return view('tiquet' . DIRECTORY_SEPARATOR . 'vistaTiquet', $data);
            }
        } else {

            return redirect()->to('tiquets');
        }
    }

    public function viewTiquet_post()
    {

        $tiquet_model = new TiquetModel();

        $input = $this->request->getPost('tiquet_seleccionat');

        if (substr_count((string) $input, " // ") != 3) {
            return redirect()->back();
        }

        $id_tiquet = trim(explode('//', (string) $input)[3]);

        $tiquet = $tiquet_model->getTiquetById($id_tiquet);

        if ($tiquet != null) {
            return redirect()->to(base_url('/tiquets/' . $id_tiquet));
        } else {
            return redirect()->back();
        }
    }

    public function createTiquet_post()
    {
        $tiquet_model = new TiquetModel;
        $centre_model = new CentreModel();
        $sstt_model = new SSTTModel();

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

            $nom_persona_contacte_centre = $this->request->getPost('sNomContacteCentre');
            $correu_persona_contacte_centre = $this->request->getPost('sCorreuContacteCentre');
            $data_alta = date("Y-m-d H:i:s");

            $actor = session()->get('user_data');
            $centre_emissor = $actor['codi_centre'];
            $role = $actor['role'];


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

                                        $msg = lang('alertes.flash_data_create_tiquet');
                                        session()->setFlashdata('crearTiquet', $msg);

                                        $id_sstt = $centre_model->obtenirCentre($centre_emissor)['id_sstt'];
                                        $model->addTiquet($uuid, $csv_data[0], $csv_data[1], $csv_data[2], $csv_data[3], $data_alta, null, $csv_data[4], 1, $centre_emissor, null, $id_sstt);
                                    } else if ($role == "sstt" || $role == "admin_sstt") {

                                        if ($csv_data[6] == null) {
                                            $msg = lang('alertes.flash_data_create_tiquet');
                                            session()->setFlashdata('crearTiquet', $msg);

                                            $model->addTiquet($uuid, $csv_data[0], $csv_data[1], $csv_data[2], $csv_data[3], $data_alta, null, $csv_data[4], 1, $csv_data[5], null, $actor['id_sstt']);
                                        } else {

                                            $msg = lang('alertes.flash_data_create_tiquet');
                                            session()->setFlashdata('crearTiquet', $msg);

                                            $model->addTiquet($uuid, $csv_data[0], $csv_data[1], $csv_data[2], $csv_data[3], $data_alta, null, $csv_data[4], 2, $csv_data[5], $csv_data[6], $actor['id_sstt']);
                                        }
                                    } else if ($role == "desenvolupador") {

                                        if ($csv_data[6] == null) {
                                            $msg = lang('alertes.flash_data_create_tiquet');
                                            session()->setFlashdata('crearTiquet', $msg);

                                            $model->addTiquet($uuid, $csv_data[0], $csv_data[1], $csv_data[2], $csv_data[3], $data_alta, null, $csv_data[4], 1, $csv_data[5], null, $csv_data[7]);
                                        } else {

                                            $msg = lang('alertes.flash_data_create_tiquet');
                                            session()->setFlashdata('crearTiquet', $msg);

                                            $model->addTiquet($uuid, $csv_data[0], $csv_data[1], $csv_data[2], $csv_data[3], $data_alta, null, $csv_data[4], 2, $csv_data[5], $csv_data[6], $csv_data[7]);
                                        }
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

                if ($centre_emissor == "no_codi") {
                    
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


                    if ($centre_emissor != null && $centre_model->obtenirCentre($centre_emissor) == null) {
                        $msg = lang('alertes.filter_error_centre_reparador');
                        session()->setFlashdata('error_filtre', $msg);
                        return redirect()->back()->withInput();
                    }

                    if ($centre_reparador != null && $centre_model->obtenirCentre($centre_reparador) == null) {
                        $msg = lang('alertes.filter_error_centre_reparador');
                        session()->setFlashdata('error_filtre', $msg);
                        return redirect()->back()->withInput();
                    }
                }




                for ($i = 1; $i <= $num_tiquets; $i++) {
                    $codi_equip = $this->request->getPost('equipment_code_' . $i);
                    $tipus = $this->request->getPost('type_' . $i);
                    $problem = $this->request->getPost('problem_' . $i);

                    $uuid = $uuid_library->v4();
                    if ($role == "professor" || $role == "centre_emissor") {

                        $msg = lang('alertes.flash_data_create_tiquet');
                        session()->setFlashdata('crearTiquet', $msg);

                        $id_sstt = $centre_model->obtenirCentre($centre_emissor)['id_sstt'];
                        $tiquet_model->addTiquet($uuid, $codi_equip, $problem, $nom_persona_contacte_centre, $correu_persona_contacte_centre, $data_alta, null, $tipus, 1, $centre_emissor, null, $id_sstt);
                    
                    } elseif ($role == "sstt" || $role == "admin_sstt") {

                        if ($centre_reparador == null) {
                            $msg = lang('alertes.flash_data_create_tiquet');
                            session()->setFlashdata('crearTiquet', $msg);
                            $tiquet_model->addTiquet($uuid, $codi_equip, $problem, $nom_persona_contacte_centre, $correu_persona_contacte_centre, $data_alta, null, $tipus, 1, $centre_emissor, null, $actor['id_sstt']);
                        } else {
                            $msg = lang('alertes.flash_data_create_tiquet');
                            session()->setFlashdata('crearTiquet', $msg);
                            $tiquet_model->addTiquet($uuid, $codi_equip, $problem, $nom_persona_contacte_centre, $correu_persona_contacte_centre, $data_alta, null, $tipus, 2, $centre_emissor, $centre_reparador, $actor['id_sstt']);
                        }

                    } elseif ($role == "desenvolupador") {
                        

                        $id_sstt = $this->request->getPost("sstt");
                        $id_sstt = trim(explode('-', (string) $id_sstt)[0]);

                        // TODO Bea ficar alerta
                        if ($id_sstt != null && $sstt_model->obtenirSSTTPerId($id_sstt) == null) {
                            return redirect()->back()->withInput();
                        }

                        if ($centre_reparador == null) {
                            $msg = lang('alertes.flash_data_create_tiquet');
                            session()->setFlashdata('crearTiquet', $msg);
                            $tiquet_model->addTiquet($uuid, $codi_equip, $problem, $nom_persona_contacte_centre, $correu_persona_contacte_centre, $data_alta, null, $tipus, 1, $centre_emissor, null, $id_sstt);
                        } else {
                            $msg = lang('alertes.flash_data_create_tiquet');
                            session()->setFlashdata('crearTiquet', $msg);
                            $tiquet_model->addTiquet($uuid, $codi_equip, $problem, $nom_persona_contacte_centre, $correu_persona_contacte_centre, $data_alta, null, $tipus, 2, $centre_emissor, $centre_reparador, $id_sstt);
                        }
                    }
                }
            }
        }

        if ($role == "professor") {
            return redirect()->to(base_url('/tiquets/emissor'));
        } else {
            return redirect()->to(base_url('/tiquets'));
        }
    }

    /**
     * Funció que ens dirigeix cap al formulari per crear un tiquet
     *
     * @author Blai Burgués Vicente
     */
    public function createTiquet()
    {
        $sstt_model = new SSTTModel();
        $centre_model = new CentreModel();

        $actor = session()->get('user_data');
        $role = $actor['role'];
        $data['role'] = $role;

        if ($role == "alumne") {
            return redirect()->to(base_url('/tiquets'));
        }

        $tipus_dispositius = new TipusDispositiuModel;
        $array_tipus_dispositius = $tipus_dispositius->getTipusDispositius();
        $array_tipus_dispositius_nom = [];

        $options_tipus_dispositius = "";
        for ($i = 0; $i < sizeof($array_tipus_dispositius); $i++) {
            if ($array_tipus_dispositius[$i]['actiu'] == "1") {
                $options_tipus_dispositius .= "<option value=" . $array_tipus_dispositius[$i]['id_tipus_dispositiu'] . ">";
                $options_tipus_dispositius .= $array_tipus_dispositius[$i]['nom_tipus_dispositiu'];
                $options_tipus_dispositius .= "</option>";
                $array_tipus_dispositius_nom[$i] = $array_tipus_dispositius[$i]['nom_tipus_dispositiu'];
            }
        }

        $data['tipus_dispositius'] = $options_tipus_dispositius;
        $data['json_tipus_dispositius'] = json_encode($array_tipus_dispositius_nom);


        if ($role == "desenvolupador" || $role == "admin_sstt" || $role == "sstt") {
            
            $array_centres = $centre_model->obtenirCentres();
            $options_tipus_dispositius_emissors = "";
            $options_tipus_dispositius_reparadors = "";
            for ($i = 0; $i < sizeof($array_centres); $i++) {
                if (($role == "sstt" || $role == "admin_sstt") && $array_centres[$i]['id_sstt'] == $actor['id_sstt']) {
                    $options_tipus_dispositius_emissors .= "<option value=\"" . $array_centres[$i]['codi_centre'] . " - " . $array_centres[$i]['nom_centre'] . "\">";
                    $options_tipus_dispositius_emissors .= $array_centres[$i]['nom_centre'];
                    $options_tipus_dispositius_emissors .= "</option>";
                } else if ($role == "desenvolupador") {
                    $options_tipus_dispositius_emissors .= "<option value=\"" . $array_centres[$i]['codi_centre'] . " - " . $array_centres[$i]['nom_centre'] . "\">";
                    $options_tipus_dispositius_emissors .= $array_centres[$i]['nom_centre'];
                    $options_tipus_dispositius_emissors .= "</option>";
                }


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
            $data['centres_emissors'] = $options_tipus_dispositius_emissors;
            $data['centres_reparadors'] = $options_tipus_dispositius_reparadors;
        }

        if ($role == "desenvolupador") {
            $data['sstt'] = "";
            $options_sstt = "";

            $array_sstt = $sstt_model->obtenirSSTT();

            for ($i = 0; $i < sizeof($array_sstt); $i++) {
                if ($array_sstt[$i]['id_sstt'] != "0") {
                    $options_sstt .= "<option value=\"" . $array_sstt[$i]['id_sstt'] . " - " . $array_sstt[$i]['nom_sstt'] . "\">";
                    $options_sstt .= $array_sstt[$i]['nom_sstt'];
                    $options_sstt .= "</option>";
                }
            }

            $data['sstt'] = $options_sstt;
        }


        $codi_centre = session()->get('user_data')['codi_centre'];

        if ($codi_centre != "no_codi") {
            $centre = new CentreModel;
            $data['nom_persona_contacte_centre'] = $centre->obtenirNomResponsable($codi_centre);
            $data['correu_persona_contacte_centre'] = $centre->obtenirCorreuResponsable($codi_centre);
        } else {
            $data['nom_persona_contacte_centre'] = null;
            $data['correu_persona_contacte_centre'] = null;
        }

        //dades tipus dispositiu
        $tipus_dispositiu_model = new TipusDispositiuModel();
        $array_tipus_dispositiu = $tipus_dispositiu_model->getTipusDispositius();
        $data['array_tipus_dispositiu'] = $array_tipus_dispositiu;

        $data['title'] = lang('general_lang.formulari_tiquet');
        return view('formularis' . DIRECTORY_SEPARATOR . 'formulariTiquet', $data);
    }

    /**
     * Funció que ens dirigeix cap al formulari per crear un tiquet
     *
     * @author Blai Burgués Vicente
     */
    public function descarregar($arxiu)
    {
        $tipus_dispositiu_model = new TipusDispositiuModel();

        $role = session()->get('user_data')['role'];

        $array_tipus_dispositiu = $tipus_dispositiu_model->obtenirTipusDispositiuActiu();

        if ($arxiu == "exemple_afegir_tiquet") {

            if ($role == "professor" || $role == "centre_emissor" || $role == "centre_reparador") {
                //$file = new \CodeIgniter\Files\File(WRITEPATH . "uploads" . DIRECTORY_SEPARATOR . "csv" . DIRECTORY_SEPARATOR . "exemple_afegir_tiquet_professorat.csv"); // Definim el nom de l'arxiu amb ruta
                //$file_name = "exemple_afegir_tiquet_professorat.csv";

                $file_name = "exemple_afegir_tiquet_professorat.csv";
                $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                $file = fopen($file_path, 'w');
                fwrite($file, "\xEF\xBB\xBF");
                $header = ['Codi del equip *','Descripció avaria *', 'Nom persona contacte centre *', 'Correu persona contacte centre *', 'Codi tipus de dispositiu *','','','Informació codi tipus dispositiu','Informació tipus dispositiu'];
                fputcsv($file, $header, ';'); 
                $primer =  true;
                foreach ($array_tipus_dispositiu as $row) {
                    if ($primer) {
                        $data = ['EXEMPLE',"Descripció d'exemple.",'Persona contacte','Correu persona contacte','1','','', $row['id_tipus_dispositiu'], $row['nom_tipus_dispositiu']];
                        $primer = false;
                    } else {
                        $data = ['','','','','','','', $row['id_tipus_dispositiu'], $row['nom_tipus_dispositiu']];
                    }
                    fputcsv($file, $data, ';');
                }
                fclose($file);

            } else if ($role == "sstt" || $role == "admin_sstt") {
                //$file = new \CodeIgniter\Files\File(WRITEPATH . "uploads" . DIRECTORY_SEPARATOR . "csv" . DIRECTORY_SEPARATOR . "exemple_afegir_tiquet_sstt.csv"); // Definim el nom de l'arxiu amb ruta
                //$file_name = "exemple_afegir_tiquet_sstt.csv";

                $file_name = "exemple_afegir_tiquet_sstt.csv";
                $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                $file = fopen($file_path, 'w');
                fwrite($file, "\xEF\xBB\xBF");
                $header = ['Codi del equip *','Descripció avaria *', 'Nom persona contacte centre *', 'Correu persona contacte centre *', 'Codi tipus de dispositiu *','Codi centre emissor','Codi centre reparador','','','Informació codi tipus dispositiu','Informació tipus dispositiu'];
                fputcsv($file, $header, ';'); 
                $primer =  true;
                foreach ($array_tipus_dispositiu as $row) {
                    if ($primer) {
                        $data = ['EXEMPLE',"Descripció d'exemple.",'Persona contacte','Correu persona contacte','1','25006732 (pot estar buit)','25002799 (pot estar buit)','','', $row['id_tipus_dispositiu'], $row['nom_tipus_dispositiu']];
                        $primer = false;
                    } else {
                        $data = ['','','','','','','','','', $row['id_tipus_dispositiu'], $row['nom_tipus_dispositiu']];
                    }
                    fputcsv($file, $data, ';');
                }
                fclose($file);
            } else if ($role == "desenvolupador") {
                //$file = new \CodeIgniter\Files\File(WRITEPATH . "uploads" . DIRECTORY_SEPARATOR . "csv" . DIRECTORY_SEPARATOR . "exemple_afegir_tiquet_desenvolupador.csv"); // Definim el nom de l'arxiu amb ruta
                //$file_name = "exemple_afegir_tiquet_desenvolupador.csv";

                $file_name = "exemple_afegir_tiquet_desenvolupador.csv";
                $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                $file = fopen($file_path, 'w');
                fwrite($file, "\xEF\xBB\xBF");
                /*Codi del equip *;Descripció avaria *;Nom persona contacte centre;Correu persona contacte centre;Codi tipus de dispositiu *;Codi centre emissor;Codi centre reparador;Serveis Territorials
                EXEMPLE;Descripció d'exemple.;Persona;Correu persona;1;25006732 (pot estar buit);25002799 (pot estar buit);125*/
                $header = ['Codi del equip *','Descripció avaria *', 'Nom persona contacte centre *', 'Correu persona contacte centre *', 'Codi tipus de dispositiu *','Codi centre emissor','Codi centre reparador','Serveis Territorials *','','','Informació codi tipus dispositiu','Informació tipus dispositiu'];
                fputcsv($file, $header, ';'); 
                $primer =  true;
                foreach ($array_tipus_dispositiu as $row) {
                    if ($primer) {
                        $data = ['EXEMPLE',"Descripció d'exemple.",'Persona contacte','Correu persona contacte','1','25006732 (pot estar buit)','25002799 (pot estar buit)','125','','', $row['id_tipus_dispositiu'], $row['nom_tipus_dispositiu']];
                        $primer = false;
                    } else {
                        $data = ['','','','','','','','','','', $row['id_tipus_dispositiu'], $row['nom_tipus_dispositiu']];
                    }
                    fputcsv($file, $data, ';');
                }
                fclose($file);
            }

            // En cas que no es tracti d'un fitxer llencem que no s'ha trobat
            /*if (!$file->isFile()) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException("El fitxer CSV d'exemple per afegir tiquets no ha estat trobat!");
            }*/

            header('Content-Description: File Transfer');
            header('Content-Type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename=' . basename($file_path));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_path));
            readfile($file_path);

            /*// Llegim l'arxiu i donem resposta
            $filedata = new \SplFileObject($file->getPathname(), "r");
            $data1 = $filedata->fread($filedata->getSize());
            //return $this->response->setContentType($file->getMimeType())->setBody($data1);
            return $this->response->download($file_name, "\xEF\xBB\xBF" . $data1, $file->getMimeType());*/
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

        $actor = session()->get('user_data');
        $role = $actor['role'];
        $data['role'] = $role;

        if ($role == "alumne") {
            return redirect()->to(base_url('/tiquets'));
        }

        $tiquet = $tiquet_model->getTiquetById($id_tiquet);
        $data['tiquet'] = $tiquet;


        if ($tiquet != null) {

            $array_tipus_dispositius = $tipus_dispositius->getTipusDispositius();
            $array_tipus_dispositius_nom = [];

            $options_tipus_dispositius = "";
            for ($i = 0; $i < sizeof($array_tipus_dispositius); $i++) {
                if ($array_tipus_dispositius[$i]['actiu'] == "1") {
                    if (($i + 1) != $data['tiquet']['id_tipus_dispositiu']) {
                        $options_tipus_dispositius .= "<option value=" . ($i + 1) . ">";
                    } else {
                        $options_tipus_dispositius .= "<option value=" . ($i + 1) . " selected>";
                    }
                    $options_tipus_dispositius .= $array_tipus_dispositius[$i]['nom_tipus_dispositiu'];
                    $options_tipus_dispositius .= "</option>";
                    $array_tipus_dispositius_nom[$i] = $array_tipus_dispositius[$i]['nom_tipus_dispositiu'];
                }
            }

            $data['tipus_dispositius'] = $options_tipus_dispositius;
            $data['json_tipus_dispositius'] = json_encode($array_tipus_dispositius_nom);

            $array_centres = $centre_model->obtenirCentres();
            $options_tipus_dispositius_emissors = "";
            $options_tipus_dispositius_reparadors = "";
            $data['centre_emissor_selected'] = null;
            $data['centre_reparador_selected'] = null;
            for ($i = 0; $i < sizeof($array_centres); $i++) {

                if ($role == "desenvolupador" || (($role == "admin_sstt" || $role == "sstt") && $array_centres[$i]['id_sstt'] == $actor['id_sstt'])) {
                    if ($tiquet['codi_centre_emissor'] != null && $tiquet['codi_centre_emissor'] == $array_centres[$i]['codi_centre']) {
                        $data['centre_emissor_selected'] = $array_centres[$i]['codi_centre'] . " - " . $array_centres[$i]['nom_centre'];
                        $options_tipus_dispositius_emissors .= "<option value='" . $array_centres[$i]['codi_centre'] . " - " . $array_centres[$i]['nom_centre'] . "' selected>";
                    } else {
                        $options_tipus_dispositius_emissors .= "<option value='" . $array_centres[$i]['codi_centre'] . " - " . $array_centres[$i]['nom_centre'] . "'>";
                    }

                    $options_tipus_dispositius_emissors .= $array_centres[$i]['nom_centre'];
                    $options_tipus_dispositius_emissors .= "</option>";
                }


                if ($array_centres[$i]['taller'] == 1 && ($role == "desenvolupador" || (($role == "admin_sstt" || $role == "sstt") && $array_centres[$i]['id_sstt'] == $actor['id_sstt']))) {
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
            if ($role == "sstt" && $estat != "Pendent de recollir" && $estat != "Emmagatzemat a SSTT" && $estat != "Assignat i pendent de recollir" && $estat != "Assignat i emmagatzemat a SSTT") {
                $data['informacio_editable'] = "disabled";
                $data['informacio_required'] = "";
                session()->setFlashdata('required', false);
            }


            $id_pendent_recollir = $estat_model->obtenirIdPerEstat("Pendent de recollir");
            $id_assignat_pendent_recollir = $estat_model->obtenirIdPerEstat("Assignat i pendent de recollir");
            $id_emmagatzemat_sstt = $estat_model->obtenirIdPerEstat("Emmagatzemat a SSTT");
            $id_assignat_emmagatzemat_sstt = $estat_model->obtenirIdPerEstat("Assignat i emmagatzemat a SSTT");
            $id_pendent_reparar = $estat_model->obtenirIdPerEstat("Pendent de reparar");
            $id_reparant = $estat_model->obtenirIdPerEstat("Reparant");
            $id_reparat_pendent_recollir = $estat_model->obtenirIdPerEstat("Reparat i pendent de recollir");
            $id_pendent_retorn  = $estat_model->obtenirIdPerEstat("Pendent de retorn");
            $id_retornat = $estat_model->obtenirIdPerEstat("Retornat");
            $id_rebutjat_per_sstt = $estat_model->obtenirIdPerEstat("Rebutjat per SSTT");
            $id_desguassat  = $estat_model->obtenirIdPerEstat("Desguassat");


            $estat_tiquet = $estat_model->obtenirEstatPerId($tiquet['id_estat']);
            $data['estat_tiquet'] = $estat_tiquet;
            if ($role == "sstt" || $role == "admin_sstt") {
                $centre_emissor_tiquet = $centre_model->obtenirCentre($tiquet['codi_centre_emissor']);
                $centre_reparador_tiquet =  $centre_model->obtenirCentre($tiquet['codi_centre_reparador']);
                $estat_editable = false;
                if ($centre_emissor_tiquet != null) {
                    if ($centre_emissor_tiquet['id_sstt'] == $actor['id_sstt']) {
                        $estat_editable = true;
                    }
                } else if ($centre_reparador_tiquet != null) {
                    if ($centre_reparador_tiquet['id_sstt'] == $actor['id_sstt']) {
                        $estat_editable = true;
                    }
                }
            }

            $data['estat_ediatble'] = "";
            if ($estat_tiquet == "Pendent de recollir") {

                if ($role == "sstt" && $estat_editable) {

                    $options_estat = "<option value='" . $id_pendent_recollir . "' selected>Pendent de recollir</option>
                                    <option value='" . $id_emmagatzemat_sstt . "'>Emmagatzemat a SSTT (no reversible)</option>
                                    <option value='" . $id_rebutjat_per_sstt . "'>Rebutjat per SSTT (no reversible)</option>";
                } else if (($role == "admin_sstt" && $estat_editable) || $role == "desenvolupador") {

                    $array_estat = $estat_model->getEstats();
                    $options_estat = "";
                    for ($i = 0; $i < sizeof($array_estat); $i++) {

                        if (($i + 1) != $data['tiquet']['id_estat']) {
                            $options_estat .= "<option value='" . ($i + 1) . "'>";
                        } else {
                            $options_estat .= "<option value='" . ($i + 1) . "' selected>";
                        }

                        $options_estat .= $array_estat[$i]['nom_estat'];
                        $options_estat .= "</option>";
                    }
                } else {
                    $options_estat = "<option value='" . $id_pendent_recollir . "' selected>Pendent de recollir</option>";
                    $data['estat_ediatble'] = "disabled";
                }
            } else if ($estat_tiquet == "Rebutjat per SSTT") {

                if (($role == "admin_sstt" && $estat_editable) || $role == "desenvolupador") {

                    $array_estat = $estat_model->getEstats();
                    $options_estat = "";
                    for ($i = 0; $i < sizeof($array_estat); $i++) {

                        if (($i + 1) != $data['tiquet']['id_estat']) {
                            $options_estat .= "<option value='" . ($i + 1) . "'>";
                        } else {
                            $options_estat .= "<option value='" . ($i + 1) . "' selected>";
                        }

                        $options_estat .= $array_estat[$i]['nom_estat'];
                        $options_estat .= "</option>";
                    }
                } else {
                    $options_estat = "<option value='" . $id_rebutjat_per_sstt . "' selected>Rebutjat per SSTT</option>";
                    $data['estat_ediatble'] = "disabled";
                }
            } else if ($estat_tiquet == "Assignat i pendent de recollir") {

                if ($role == "sstt" && $estat_editable) {

                    $options_estat = "<option value='" . $id_assignat_pendent_recollir . "' selected>Assignat i pendent de recollir</option>
                    <option value='" . $id_assignat_emmagatzemat_sstt . "'>Assignat i emmagatzemat a SSTT (no reversible)</option>";
                } else if (($role == "admin_sstt" && $estat_editable) || $role == "desenvolupador") {

                    $array_estat = $estat_model->getEstats();
                    $options_estat = "";
                    for ($i = 0; $i < sizeof($array_estat); $i++) {

                        if (($i + 1) != $data['tiquet']['id_estat']) {
                            $options_estat .= "<option value='" . ($i + 1) . "'>";
                        } else {
                            $options_estat .= "<option value='" . ($i + 1) . "' selected>";
                        }

                        $options_estat .= $array_estat[$i]['nom_estat'];
                        $options_estat .= "</option>";
                    }
                } else {
                    $options_estat = "<option value='" . $id_assignat_pendent_recollir . "' selected>Assignat i pendent de recollir</option>";
                    $data['estat_ediatble'] = "disabled";
                }
            } else if ($estat_tiquet == "Emmagatzemat a SSTT") {

                if ($role == "sstt" && $estat_editable) {

                    $options_estat = "<option value='" . $id_emmagatzemat_sstt . "' selected>Emmagatzemat a SSTT</option>
                                    <option value='" . $id_desguassat . "'>Desguassat (no reversible)</option>";
                } else if (($role == "admin_sstt" && $estat_editable) || $role == "desenvolupador") {

                    $array_estat = $estat_model->getEstats();
                    $options_estat = "";
                    for ($i = 0; $i < sizeof($array_estat); $i++) {

                        if (($i + 1) != $data['tiquet']['id_estat']) {
                            $options_estat .= "<option value='" . ($i + 1) . "'>";
                        } else {
                            $options_estat .= "<option value='" . ($i + 1) . "' selected>";
                        }

                        $options_estat .= $array_estat[$i]['nom_estat'];
                        $options_estat .= "</option>";
                    }
                } else {
                    $options_estat = "<option value='" . $id_emmagatzemat_sstt . "' selected>Emmagatzemat a SSTT</option>";
                    $data['estat_ediatble'] = "disabled";
                }
            } else if ($estat_tiquet == "Assignat i emmagatzemat a SSTT") {

                if ($role == "sstt" && $estat_editable) {

                    $options_estat = "<option value='" . $id_assignat_emmagatzemat_sstt . "' selected>Assignat i emmagatzemat a SSTT</option>
                                    <option value='" . $id_pendent_reparar . "'>Pendent de reparar (no reversible)</option>";
                } else if (($role == "admin_sstt" && $estat_editable) || $role == "desenvolupador") {

                    $array_estat = $estat_model->getEstats();
                    $options_estat = "";
                    for ($i = 0; $i < sizeof($array_estat); $i++) {

                        if (($i + 1) != $data['tiquet']['id_estat']) {
                            $options_estat .= "<option value='" . ($i + 1) . "'>";
                        } else {
                            $options_estat .= "<option value='" . ($i + 1) . "' selected>";
                        }

                        $options_estat .= $array_estat[$i]['nom_estat'];
                        $options_estat .= "</option>";
                    }
                } else {
                    $options_estat = "<option value='" . $id_assignat_emmagatzemat_sstt . "' selected>Assignat i emmagatzemat a SSTT</option>";
                    $data['estat_ediatble'] = "disabled";
                }
            } else if ($estat_tiquet == "Pendent de reparar") {

                if ($role == "professor" && $tiquet['codi_centre_reparador'] == $actor['codi_centre']) {

                    $options_estat = "<option value='" . $id_pendent_reparar . "' selected>Pendent de reparar</option>
                                    <option value='" . $id_reparant . "'>Reparant</option>";
                } else if (($role == "admin_sstt" && $estat_editable) || $role == "desenvolupador") {

                    $array_estat = $estat_model->getEstats();
                    $options_estat = "";
                    for ($i = 0; $i < sizeof($array_estat); $i++) {

                        if (($i + 1) != $data['tiquet']['id_estat']) {
                            $options_estat .= "<option value='" . ($i + 1) . "'>";
                        } else {
                            $options_estat .= "<option value='" . ($i + 1) . "' selected>";
                        }

                        $options_estat .= $array_estat[$i]['nom_estat'];
                        $options_estat .= "</option>";
                    }
                } else {
                    $options_estat = "<option value='" . $id_pendent_reparar . "' selected>Pendent de reparar</option>";
                    $data['estat_ediatble'] = "disabled";
                }
            } else if ($estat_tiquet == "Reparant") {

                if ($role == "professor" && $tiquet['codi_centre_reparador'] == $actor['codi_centre']) {

                    $options_estat = "<option value='" . $id_reparant . "' selected>Reparant</option>
                    <option value='" . $id_pendent_reparar . "'>Pendent de reparar</option>
                    <option value='" . $id_reparat_pendent_recollir . "'>Reparat i pendent de recollir (no reversible)</option>";
                } else if (($role == "admin_sstt" && $estat_editable) || $role == "desenvolupador") {

                    $array_estat = $estat_model->getEstats();
                    $options_estat = "";
                    for ($i = 0; $i < sizeof($array_estat); $i++) {

                        if (($i + 1) != $data['tiquet']['id_estat']) {
                            $options_estat .= "<option value='" . ($i + 1) . "'>";
                        } else {
                            $options_estat .= "<option value='" . ($i + 1) . "' selected>";
                        }

                        $options_estat .= $array_estat[$i]['nom_estat'];
                        $options_estat .= "</option>";
                    }
                } else {
                    $options_estat = "<option value='" . $id_reparant . "' selected>Reparant</option>";
                    $data['estat_ediatble'] = "disabled";
                }
            } else if ($estat_tiquet == "Reparat i pendent de recollir") {

                if ($role == "sstt" && $estat_editable) {

                    $options_estat = "<option value='" . $id_reparat_pendent_recollir . "' selected>Reparat i pendent de recollir</option>
                    <option value='" . $id_pendent_retorn . "'>Pendent de retorn (no reversible)</option>";
                } else if (($role == "admin_sstt" && $estat_editable) || $role == "desenvolupador") {

                    $array_estat = $estat_model->getEstats();
                    $options_estat = "";
                    for ($i = 0; $i < sizeof($array_estat); $i++) {

                        if (($i + 1) != $data['tiquet']['id_estat']) {
                            $options_estat .= "<option value='" . ($i + 1) . "'>";
                        } else {
                            $options_estat .= "<option value='" . ($i + 1) . "' selected>";
                        }

                        $options_estat .= $array_estat[$i]['nom_estat'];
                        $options_estat .= "</option>";
                    }
                } else {
                    $options_estat = "<option value='" . $id_reparat_pendent_recollir . "' selected>Reparat i pendent de recollir</option>";
                    $data['estat_ediatble'] = "disabled";
                }
            } else if ($estat_tiquet == "Pendent de retorn") {

                if ($role == "sstt" && $estat_editable) {

                    $options_estat = "<option value='" . $id_pendent_retorn . "' selected>Pendent de retorn</option>
                    <option value='" . $id_retornat . "'>Retornat (no reversible)</option>
                    <option value='" . $id_desguassat . "'>Desguassat (no reversible)</option>";
                } else if (($role == "admin_sstt" && $estat_editable) || $role == "desenvolupador") {

                    $array_estat = $estat_model->getEstats();
                    $options_estat = "";
                    for ($i = 0; $i < sizeof($array_estat); $i++) {

                        if (($i + 1) != $data['tiquet']['id_estat']) {
                            $options_estat .= "<option value='" . ($i + 1) . "'>";
                        } else {
                            $options_estat .= "<option value='" . ($i + 1) . "' selected>";
                        }

                        $options_estat .= $array_estat[$i]['nom_estat'];
                        $options_estat .= "</option>";
                    }
                } else {
                    $options_estat = "<option value='" . $id_pendent_retorn . "' selected>Pendent de retorn</option>";
                    $data['estat_ediatble'] = "disabled";
                }
            } else if ($estat_tiquet == "Retornat") {

                if (($role == "admin_sstt" && $estat_editable) || $role == "desenvolupador") {

                    $array_estat = $estat_model->getEstats();
                    $options_estat = "";
                    for ($i = 0; $i < sizeof($array_estat); $i++) {

                        if (($i + 1) != $data['tiquet']['id_estat']) {
                            $options_estat .= "<option value='" . ($i + 1) . "'>";
                        } else {
                            $options_estat .= "<option value='" . ($i + 1) . "' selected>";
                        }

                        $options_estat .= $array_estat[$i]['nom_estat'];
                        $options_estat .= "</option>";
                    }
                } else {
                    $options_estat = "<option value='" . $id_retornat . "' selected>Retornat</option>";
                    $data['estat_ediatble'] = "disabled";
                }
            } else if ($estat_tiquet == "Desguassat") {

                if (($role == "admin_sstt" && $estat_editable) || $role == "desenvolupador") {

                    $array_estat = $estat_model->getEstats();
                    $options_estat = "";
                    for ($i = 0; $i < sizeof($array_estat); $i++) {

                        if (($i + 1) != $data['tiquet']['id_estat']) {
                            $options_estat .= "<option value='" . ($i + 1) . "'>";
                        } else {
                            $options_estat .= "<option value='" . ($i + 1) . "' selected>";
                        }

                        $options_estat .= $array_estat[$i]['nom_estat'];
                        $options_estat .= "</option>";
                    }
                } else {
                    $options_estat = "<option value='" . $id_desguassat . "' selected>Desguassat</option>";
                    $data['estat_ediatble'] = "disabled";
                }
            }

            $data['estats'] = $options_estat;
        } else {
            return redirect()->to(base_url('/tiquets'));
        }





        return view('formularis' . DIRECTORY_SEPARATOR . 'formulariEditarTiquet', $data);
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
        $centre_model = new CentreModel();

        $actor = session()->get('user_data');
        $role = $actor['role'];

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

        if ($role == "alumne" || $this->validate($validationRules)) {
            $error = true;
            
            if ($role == "alumne") {
                $tiquet = $tiquet_model->getTiquetById(session()->getFlashdata('id_tiquet_alumne'));
            } else {
                $tiquet = $tiquet_model->getTiquetById(session()->getFlashdata('id_tiquet'));
            }

            if ($tiquet != null) {
                $estat_origen = $estat_model->obtenirEstatPerId($tiquet['id_estat']);
                
                if ($role == "alumne") {

                    $estat_desti = $this->request->getPost('estat');
                    $estat_desti = $this->request->getPost('estat');
                    if ($estat_desti != null) {
                        $estat_desti_nom = $estat_model->obtenirEstatPerId($estat_desti);
                    } else {
                        $estat_desti_nom = null;
                    }

                    $data_estat = [
                        "id_estat" => $estat_desti,
                    ];


                    $estat_editable = false;
                    if ($estat_origen == "Pendent de reparar") {

                        if ($estat_desti_nom == "Reparant") {
                            $estat_editable = true;
                        }
                    } else if ($estat_origen == "Reparant") {

                        if ($estat_desti_nom == "Pendent de reparar") {
                            $estat_editable = true;
                        }
                    }

                    if ($estat_editable) {
                        $data_estat['id_estat'] = $estat_model->obtenirIdPerEstat($estat_desti_nom);
                    } else {
                        $data_estat['id_estat'] = $tiquet['id_estat'];
                    }

                    if ($role == "alumne" && $tiquet['codi_centre_reparador'] == $actor['codi_centre']) {
                        $tiquet_model->updateTiquet(session()->getFlashdata('id_tiquet_alumne'), $data_estat);
                        $msg = lang('alertes.flash_data_update_tiquet') . $tiquet['id_tiquet'];
                        session()->setFlashdata('editarTiquet', $msg);
                        $error = false;
                        
                    }

                } else if ($role == "professor" || $role == "centre_reparador" || $role == "centre_emissor") {
                    
                    $codi_centre = session()->get('user_data')['codi_centre'];
                    $codi_centre_emissor = $tiquet['codi_centre_emissor'];
                    $codi_centre_reparador = $tiquet['codi_centre_reparador'];

                    $nom_contacte_centre = $this->request->getPost('sNomContacteCentre');
                    $correu_contacte_centre = $this->request->getPost('sCorreuContacteCentre');
                    $codi_equip = $this->request->getPost('equipment_code');
                    $tipus_dispositiu = $this->request->getPost('type');
                    $descripcio_avaria = $this->request->getPost('problem');
                    $estat_desti = $this->request->getPost('estat');
                    $estat_desti = $this->request->getPost('estat');
                    if ($estat_desti != null) {
                        $estat_desti_nom = $estat_model->obtenirEstatPerId($estat_desti);
                    } else {
                        $estat_desti_nom = null;
                    }

                    if ($codi_centre_emissor != null && $centre_model->obtenirCentre($codi_centre_emissor) == null) {
                        $msg = lang('alertes.filter_error_centre_reparador');
                        session()->setFlashdata('error_filtre', $msg);
                        return redirect()->back()->withInput();
                    }

                    if ($codi_centre_reparador != null && $centre_model->obtenirCentre($codi_centre_reparador) == null) {
                        $msg = lang('alertes.filter_error_centre_reparador');
                        session()->setFlashdata('error_filtre', $msg);
                        return redirect()->back()->withInput();
                    }

                    $data = [
                        "nom_persona_contacte_centre" => $nom_contacte_centre,
                        "correu_persona_contacte_centre" => $correu_contacte_centre,
                        "codi_equip" => $codi_equip,
                        "id_tipus_dispositiu" => $tipus_dispositiu,
                        "descripcio_avaria" => $descripcio_avaria
                    ];

                    // Controlem que es puguin modificar les dades
                    if (($role == "centre_emissor" || $role == "professor" || $role == "centre_reparador") && $estat_origen == "Pendent de recollir" && $codi_centre == $codi_centre_emissor) {
                        $msg = lang('alertes.flash_data_update_tiquet') . session()->getFlashdata('id_tiquet');
                        session()->setFlashdata('editarTiquet', $msg);
                        $tiquet_model->updateTiquet(session()->getFlashdata('id_tiquet'), $data);
                        $error = false;
                    }


                    // Controlem que es pugui modificar l'estat
                    $data_estat = [
                        "id_estat" => $estat_desti,
                    ];


                    $estat_editable = false;
                    if ($estat_origen == "Pendent de reparar") {

                        if ($estat_desti_nom == "Reparant") {
                            $estat_editable = true;
                        }
                    } else if ($estat_origen == "Reparant") {

                        if ($estat_desti_nom == "Pendent de reparar" || $estat_desti_nom == "Reparat i pendent de recollir") {
                            $estat_editable = true;
                        }
                    }

                    if ($estat_editable) {
                        $data_estat['id_estat'] = $estat_model->obtenirIdPerEstat($estat_desti_nom);
                    } else {
                        $data_estat['id_estat'] = $tiquet['id_estat'];
                    }

                    if ($role == "professor" && $codi_centre == $codi_centre_reparador) {
                        $msg = lang('alertes.flash_data_update_tiquet') . session()->getFlashdata('id_tiquet');
                        session()->setFlashdata('editarTiquet', $msg);
                        $tiquet_model->updateTiquet(session()->getFlashdata('id_tiquet'), $data_estat);
                        $error = false;
                    }
                } else if ($role == "sstt" || $role == "admin_sstt" || $role == "desenvolupador") {

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
                    if ($estat_desti != null) {
                        $estat_desti_nom = $estat_model->obtenirEstatPerId($estat_desti);
                    } else {
                        $estat_desti_nom = null;
                    }
                    $descripcio_avaria = $this->request->getPost('problem');

                    $centre_emissor = trim(explode('-', (string) $centre_emissor)[0]);
                    $centre_reparador = trim(explode('-', (string) $centre_reparador)[0]);

                    if ($centre_emissor != null && $centre_model->obtenirCentre($centre_emissor) == null) {
                        $msg = lang('alertes.filter_error_centre_reparador');
                        session()->setFlashdata('error_filtre', $msg);
                        return redirect()->back()->withInput();
                    }

                    if ($centre_reparador != null && $centre_model->obtenirCentre($centre_reparador) == null) {
                        $msg = lang('alertes.filter_error_centre_reparador');
                        session()->setFlashdata('error_filtre', $msg);
                        return redirect()->back()->withInput();
                    }

                    $data_informacio = [
                        "nom_persona_contacte_centre" => $nom_contacte_centre,
                        "correu_persona_contacte_centre" => $correu_contacte_centre,
                        "codi_equip" => $codi_equip,
                        "id_tipus_dispositiu" => $tipus_dispositiu,
                        "id_estat" => $estat_desti,
                        "descripcio_avaria" => $descripcio_avaria
                    ];

                    $data_estat = [
                        "id_estat" => $estat_desti,
                    ];

                    if ($centre_emissor != "") {
                        $data_informacio['codi_centre_emissor'] = $centre_emissor;
                        $data_estat['codi_centre_emissor'] = $centre_emissor;
                    }

                    if ($centre_reparador != "") {
                        $data_informacio['codi_centre_reparador'] = $centre_reparador;
                    }

                    if ($role == "sstt" || $role == "admin_sstt") {
                        $emissor = $centre_model->obtenirCentre($centre_emissor);
                        $reparador = $centre_model->obtenirCentre($centre_reparador);
                        if ($emissor == null || $emissor['id_sstt'] != $actor['id_sstt']) {
                            $centre_emissor = null;
                            $data_informacio['codi_centre_emissor'] = $centre_emissor;
                            $data_estat['codi_centre_emissor'] = $centre_emissor;
                        }
                        if ($reparador == null || $reparador['id_sstt'] != $actor['id_sstt']) {
                            $centre_reparador = null;
                            $data_informacio['codi_centre_reparador'] = $centre_reparador;
                        }
                    }


                    $centre = $centre_model->obtenirCentre($centre_reparador);
                    // Controlem que es puguin modificar les dades
                    if ($role == "sstt") {

                        $estat_editable = false;
                        $infromacio_editable = false;
                        if ($estat_origen == "Pendent de recollir") {

                            $infromacio_editable = true;

                            if ($estat_desti_nom == "Pendent de recollir" && $centre != null) {

                                $estat_desti_nom = "Assignat i pendent de recollir";
                                $estat_editable = true;
                            } else if ($estat_desti_nom == "Emmagatzemat a SSTT" && $centre != null) {

                                $estat_desti_nom = "Assignat i emmagatzemat a SSTT";
                                $estat_editable = true;
                            } else if ($estat_desti_nom == "Emmagatzemat a SSTT" || $estat_desti_nom == "Rebutjat per SSTT") {
                                $estat_editable = true;
                            }
                        } else if ($estat_origen == "Assignat i pendent de recollir") {

                            $infromacio_editable = true;

                            if ($estat_desti_nom == "Assignat i emmagatzemat a SSTT") {

                                if ($centre != null) {
                                    $estat_desti_nom = "Assignat i emmagatzemat a SSTT";
                                    $estat_editable = true;
                                } else {
                                    $estat_desti_nom = "Emmagatzemat a SSTT";
                                    $estat_editable = true;
                                }
                            } else if ($estat_desti_nom == "Assignat i pendent de recollir") {

                                if ($centre == null) {
                                    $estat_desti_nom = "Pendent de recollir";
                                    $estat_editable = true;
                                }
                            }
                        } else if ($estat_origen == "Emmagatzemat a SSTT") {

                            $infromacio_editable = true;

                            if ($estat_desti_nom == "Desguassat") {
                                $estat_editable = true;
                            } else if ($centre != null) {
                                $estat_desti_nom = "Assignat i emmagatzemat a SSTT";
                                $estat_editable = true;
                            }
                        } else if ($estat_origen == "Assignat i emmagatzemat a SSTT") {

                            $infromacio_editable = true;

                            if ($centre == null) {
                                $estat_desti_nom = "Emmagatzemat a SSTT";
                                $estat_editable = true;
                            } else {

                                if ($estat_desti_nom == "Pendent de reparar") {
                                    $estat_editable = true;
                                }
                            }
                        } else if ($estat_origen == "Reparat i pendent de recollir") {

                            if ($estat_desti_nom == "Pendent de retorn") {
                                $estat_editable = true;
                            }
                        } else if ($estat_origen == "Pendent de retorn") {

                            if ($estat_desti_nom == "Retornat" || $estat_desti_nom == "Desguassat") {
                                $estat_editable = true;
                            }
                        }

                        if ($infromacio_editable) {
                            if ($estat_editable) {
                                $data_informacio['id_estat'] = $estat_model->obtenirIdPerEstat($estat_desti_nom);
                            } else {
                                $data_informacio['id_estat'] = $tiquet['id_estat'];
                            }
                            $msg = lang('alertes.flash_data_update_tiquet') . session()->getFlashdata('id_tiquet');
                            session()->setFlashdata('editarTiquet', $msg);
                            $tiquet_model->updateTiquet(session()->getFlashdata('id_tiquet'), $data_informacio);
                        } else {
                            if ($estat_editable) {
                                $data_estat['id_estat'] = $estat_model->obtenirIdPerEstat($estat_desti_nom);
                            } else {
                                $data_estat['id_estat'] = $tiquet['id_estat'];
                            }
                            $msg = lang('alertes.flash_data_update_tiquet') . session()->getFlashdata('id_tiquet');
                            session()->setFlashdata('editarTiquet', $msg);
                            $tiquet_model->updateTiquet(session()->getFlashdata('id_tiquet'), $data_estat);
                        }

                        $error = false;
                    } else if ($role == "admin_sstt" || $role == "desenvolupador") {
                        $msg = lang('alertes.flash_data_update_tiquet') . session()->getFlashdata('id_tiquet');
                        session()->setFlashdata('editarTiquet', $msg);
                        $tiquet_model->updateTiquet(session()->getFlashdata('id_tiquet'), $data_informacio);
                        $error = false;
                    }
                }
            } else {
                return redirect()->back()->withInput();
            }
        } else {
            return redirect()->back()->withInput();
        }
        

        if ($error) {
            if ($role == "alumne") {
                return redirect()->to(base_url('/tiquets/' . session()->getFlashdata('id_tiquet_alumne')));
            } else {
                return redirect()->to(base_url('/tiquets/editar/' . session()->getFlashdata('id_tiquet')));
            }
        } else {

            if ($role == "alumne") {
                $model_estat = new EstatModel();
                $estat = $model_estat->obtenirEstatPerId($this->request->getPost('estat'));
                $msg = lang('alertes.tipus_estat_canviat') .'<b>'. $estat .'</b>';
                session()->setFlashdata('editarEstat', $msg);
                return redirect()->to(base_url('/tiquets/' . session()->getFlashdata('id_tiquet_alumne')));
            } else {
                return redirect()->to(base_url('/tiquets/editar/' . session()->getFlashdata('id_tiquet')));
            }

            /*if ($role == "professor" && $tiquet['codi_centre_emissor'] == $actor['codi_centre']) {
                return redirect()->to(base_url('/tiquets/emissor'));
            } else if($role == "alumne") {
                return redirect()->to(base_url('/tiquets/' . session()->getFlashdata('id_tiquet_alumne')));
            } else {
                return redirect()->to(base_url('/tiquets'));
            }*/
        }
    }

    public function descarregarTiquetPDF($id_tiquet)
    {
        $tiquet_model = new TiquetModel();
        $estat_model = new EstatModel();
        $intervencio_model = new IntervencioModel();
        $inventari_model = new InventariModel();
        $tipus_dispositiu_model = new TipusDispositiuModel();
        $centre_model = new CentreModel();
        $tipus_intervencio_model = new TipusIntervencioModel();
        $tipus_inventari_model = new TipusInventariModel();

        $actor = session()->get('user_data');
        $role = $actor['role'];

        $tiquet = $tiquet_model->getTiquetById($id_tiquet);

        if ($tiquet != null && ($role == "sstt" || $role == "admin_sstt" || $role == "desenvolupador")) {

            $id_sstt_emissor = null;
            if ($tiquet['codi_centre_emissor'] != null) {
                $id_sstt_emissor = $centre_model->obtenirCentre($tiquet['codi_centre_emissor'])['id_sstt'];
            }
            $id_sstt_reparador = null;
            if ($tiquet['codi_centre_reparador'] != null) {
                $id_sstt_reparador = $centre_model->obtenirCentre($tiquet['codi_centre_reparador'])['id_sstt'];
            }

            if (($role == "sstt" || $role == "admin_sstt") && ($id_sstt_emissor == $actor['id_sstt'] || $id_sstt_reparador == $actor['id_sstt']) || $role == "desenvolupador") {

                $estat = $estat_model->obtenirEstatPerId($tiquet['id_estat']);

                if ($estat == "Retornat" || $estat == "Desguassat") {

                    // Carreguem les dades
                    $data['tiquet'] = $tiquet;
                    $data['tipus_dispositiu'] = $tipus_dispositiu_model->getNomTipusDispositiu($tiquet['id_tipus_dispositiu'])['nom_tipus_dispositiu'];

                    $data['nom_centre_emissor'] = "";
                    if ($tiquet['codi_centre_emissor'] != null) {
                        $data['nom_centre_emissor'] = $centre_model->obtenirCentre($tiquet['codi_centre_emissor'])['nom_centre'];
                    }

                    $data['nom_centre_reparador'] = null;
                    if ($tiquet['codi_centre_reparador'] != null) {
                        $data['nom_centre_reparador'] = $centre_model->obtenirCentre($tiquet['codi_centre_reparador'])['nom_centre'];
                    }

                    $preu_total = 0;
                    $array_intervencions = $intervencio_model->obtenirIntervencionsTiquet($id_tiquet);
                    $array_inventari = [];

                    for ($i = 0; $i < sizeof($array_intervencions); $i++) {
                        $array_intervencions[$i]['tipus_intervencio'] = $tipus_intervencio_model->obtenirNomTipusIntervencio($array_intervencions[$i]['id_tipus_intervencio'])['nom_tipus_intervencio'];
                        $array_inventari[$i] = $inventari_model->obtenirInventariIntervencio($array_intervencions[$i]['id_intervencio']);
                    }

                    for ($i = 0; $i < sizeof($array_intervencions); $i++) {
                        for ($j = 0; $j < sizeof($array_inventari[$i]); $j++) {
                            $array_inventari[$i][$j]['tipus_inventari'] = $tipus_inventari_model->obtenirTipusInventariPerId($array_inventari[$i][$j]['id_tipus_inventari'])['nom_tipus_inventari'];
                            $preu_total += $array_inventari[$i][$j]['preu'];
                        }
                    }

                    $data['intervencions'] = $array_intervencions;
                    $data['inventaris'] = $array_inventari;
                    $data['preu_total'] = $preu_total;


                    $html = view('tiquet' . DIRECTORY_SEPARATOR . 'vistaPDF', $data); // Carreguem la vista per obtenir el codi HTML

                    // Cridem la llibreria PDF, per generar-lo i descarregar-lo com a resposta
                    $mpdf = new \Mpdf\Mpdf();
                    $mpdf->WriteHTML($html);
                    $this->response->setHeader('Content-Type', 'application/pdf');
                    $mpdf->Output('tiquet_' . $id_tiquet . '.pdf', 'D');
                } else {
                    return redirect()->back();
                }
            } else {
                return redirect()->to(base_url('/tiquets'));
            }
        } else {
            return redirect()->to(base_url('/tiquets'));
        }
    }
}
