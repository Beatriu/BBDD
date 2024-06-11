<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CentreModel;
use App\Models\ComarcaModel;
use App\Models\EstatModel;
use App\Models\IntervencioModel;
use App\Models\InventariModel;
use App\Models\PoblacioModel;
use App\Models\TipusDispositiuModel;
use App\Models\TipusInventariModel;
use App\Models\TiquetModel;
use SIENSIS\KpaCrud\Libraries\KpaCrud;

class InventarisController extends BaseController
{
    protected $helpers = ['form'];

    public function index()
    {
    }

    public function registreInventari($asignacio = null, $id_inventari = null)
    {
        $inventari_model = new InventariModel();
        $centre_model = new CentreModel();
        $session_filtre = session()->get('filtresInventari');
        $data['session_filtre'] = $session_filtre;
        
        $actor = session()->get('user_data');
        $role = $actor['role'];
        $data['role'] = $role;

        if ($role == "centre_reparador") {

            return redirect()->to(base_url('/tiquets'));
        } else {

            $data['id_inventari'] = null;
            $data['no_permisos'] = null;
            if ($id_inventari != null) {

                $inventari_eliminar = $inventari_model->obtenirInventariPerId($id_inventari);

                if ($inventari_eliminar != null) {
                    $codi_centre_inventari = $inventari_eliminar['codi_centre'];
                    $id_sstt_inventari = $centre_model->obtenirCentre($codi_centre_inventari)['id_sstt'];

                    if (($role == "professor" && $codi_centre_inventari == $actor['codi_centre']) || ($role == "admin_sstt" && $id_sstt_inventari == $actor['id_sstt']) || ($role == "desenvolupador")) {

                        //Preguntar a la bbdd quin tiquet es i retornar l'array del tiquet.
                        $data['id_inventari'] = $id_inventari;
                        session()->setFlashdata("id_inventari", $inventari_eliminar);
                    } else {
                        $data['no_permisos'] = "inventari.no_permisos";
                    }
                } else {
                    $data['no_permisos'] = "inventari.no_existeix";
                }
            }

            $data['title'] = 'Inventari';
            $data['tipus_dispositius'] = $this->selectorTipusDispositiu();
            $data['centre_reparador'] = $this->selectorCentreReparador($role, $actor);
            $data['poblacio'] = $this->selectorPoblacio($role, $actor);
            $data['comarca'] = $this->selectorComarca($role, $actor);

            $crud = new KpaCrud();
            $crud->setConfig('onlyView');
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
            $crud->setTable('vista_inventari');
            $crud->setPrimaryKey('id_inventari');
            $crud->hideHeadLink([
                'js-bootstrap',
                'css-bootstrap',
            ]);

            if ($role == "alumne" || $role == "professor" || $role == "centre_reparador") {

                $crud->setColumns([
                    'id_inventari_limitat',
                    'nom_tipus_inventari',
                    'descripcio_inventari_limitada',
                    'data_compra',
                    'id_intervencio',
                    'preu'
                ]);
                $crud->setColumnsInfo([
                    'id_inventari_limitat' => [
                        'name' => lang('inventari.id_inventari')
                    ],
                    'nom_tipus_inventari' => [
                        'name' => lang('inventari.nom_tipus_inventari')
                    ],
                    'descripcio_inventari_limitada' => [
                        'name' => lang('inventari.descripcio_inventari_limitada')
                    ],
                    'data_compra' => [
                        'name' => lang('inventari.data_compra')
                    ],
                    'id_intervencio' => [
                        'name' => lang('inventari.id_intervencio')
                    ],
                    'preu' => [
                        'name' => lang('inventari.preu')
                    ]
                ]);

                $crud->addWhere('codi_centre', $actor['codi_centre']);
            } else if ($role == "sstt" || $role == "admin_sstt") {

                $crud->setColumns([
                    'id_inventari_limitat',
                    'nom_tipus_inventari',
                    'descripcio_inventari_limitada',
                    'data_compra',
                    'id_intervencio',
                    'nom_centre',
                    'nom_poblacio',
                    'nom_comarca',
                    'preu'
                ]);
                $crud->setColumnsInfo([
                    'id_inventari_limitat' => [
                        'name' => lang('inventari.id_inventari')
                    ],
                    'nom_tipus_inventari' => [
                        'name' => lang('inventari.nom_tipus_inventari')
                    ],
                    'descripcio_inventari_limitada' => [
                        'name' => lang('inventari.descripcio_inventari_limitada')
                    ],
                    'data_compra' => [
                        'name' => lang('inventari.data_compra')
                    ],
                    'id_intervencio' => [
                        'name' => lang('inventari.id_intervencio')
                    ],
                    'nom_centre' => [
                        'name' => lang('inventari.nom_centre')
                    ],
                    'nom_poblacio' => [
                        'name' => lang('inventari.nom_poblacio')
                    ],
                    'nom_comarca' => [
                        'name' => lang('inventari.nom_comarca')
                    ],
                    'preu' => [
                        'name' => lang('inventari.preu')
                    ]
                ]);

                $crud->addWhere('id_sstt', $actor['id_sstt']);
            } else if ($role == "desenvolupador") {

                $crud->setColumns([
                    'id_inventari_limitat',
                    'nom_tipus_inventari',
                    'descripcio_inventari_limitada',
                    'data_compra',
                    'id_intervencio',
                    'nom_centre',
                    'nom_sstt',
                    'nom_poblacio',
                    'nom_comarca',
                    'preu',
                ]);
                $crud->setColumnsInfo([
                    'id_inventari_limitat' => [
                        'name' => lang('inventari.id_inventari')
                    ],
                    'nom_tipus_inventari' => [
                        'name' => lang('inventari.nom_tipus_inventari')
                    ],
                    'descripcio_inventari_limitada' => [
                        'name' => lang('inventari.descripcio_inventari_limitada')
                    ],
                    'data_compra' => [
                        'name' => lang('inventari.data_compra')
                    ],
                    'id_intervencio' => [
                        'name' => lang('inventari.id_intervencio')
                    ],
                    'nom_centre' => [
                        'name' => lang('inventari.nom_centre')
                    ],
                    'nom_sstt' => [
                        'name' => lang('inventari.nom_sstt')
                    ],
                    'nom_poblacio' => [
                        'name' => lang('inventari.nom_poblacio')
                    ],
                    'nom_comarca' => [
                        'name' => lang('inventari.nom_comarca')
                    ],
                    'preu' => [
                        'name' => lang('inventari.preu')
                    ]
                ]);
            }

            if ($role !== 'sstt' && $role !== 'alumne') {
                $crud->addItemLink('delete', 'fa-trash', base_url('inventari/esborrar/' . $asignacio), 'Eliminar Peça');
            }

            if (is_array($session_filtre)) {

                if (isset($session_filtre['tipus_dispositiu'])) {
                    $model_tipus_inventari = new TipusInventariModel();
                    $tipus_inventari = $model_tipus_inventari->obtenirTipusInventariPerId($session_filtre['tipus_dispositiu'][0]);
                    $data['tipus_dispositiu_escollit'] = $tipus_inventari['nom_tipus_inventari'];
                    $crud->addWhere('nom_tipus_inventari', $tipus_inventari['nom_tipus_inventari'], true);
                }
                if (isset($session_filtre['nom_centre_reparador'])) {
                    $model_centre = new CentreModel();
                    $centre_reparador_escollit = $model_centre->obtenirCentre($session_filtre['nom_centre_reparador'][0]);
                    $data['centre_reparador_escollit'] = $centre_reparador_escollit;

                    $crud->addWhere('codi_centre', $session_filtre['nom_centre_reparador'][0], true);
                }
                if (isset($session_filtre['data_creacio_inici']) && isset($session_filtre['data_creacio_fi'])) {
                    $data_de_la_sessio = $session_filtre['data_creacio_inici'][0];
                    $data_nova_inici = date('d-m-Y', strtotime($data_de_la_sessio));


                    $data_de_la_sessio = $session_filtre['data_creacio_fi'][0];
                    $data_nova_fi = date('d-m-Y', strtotime($data_de_la_sessio));

                    $crud->addWhere("data_alta_format BETWEEN '" . $data_nova_inici . "' AND  '" . $data_nova_fi . "'");
                }
                if (isset($session_filtre['nom_poblacio'])) {
                    $model_poblacio = new PoblacioModel();
                    $poblacio_escollida = $model_poblacio->getPoblacio($session_filtre['nom_poblacio'][0], true);
                    $data['poblacio_escollida'] = $poblacio_escollida['nom_poblacio'];
                    $crud->addWhere('id_poblacio', $poblacio_escollida['id_poblacio'], true);
                }
                if (isset($session_filtre['nom_comarca'])) {
                    $model_comarca = new ComarcaModel();
                    $comarca_escollida = $model_comarca->obtenirComarca($session_filtre['nom_comarca'][0], true);
                    $data['comarca_escollida'] = $comarca_escollida['nom_comarca'];

                    $crud->addWhere('id_comarca', $comarca_escollida['id_comarca'], true);
                }
            }

            if($asignacio == "no_assignat" || $asignacio == null){
                $crud->addWhere('id_intervencio', null, true);
            } else if($asignacio == "assignat"){
                $crud->addWhere("id_intervencio IS NOT null");
            } 
            
            $data['asignat'] = $asignacio;

            $data['output'] = $crud->render();
            $data['uri'] = $this->request->getPath();
            return view('registres' . DIRECTORY_SEPARATOR . 'registreInventari', $data);
        }
    }


    public function crearInventari()
    {

        $centre_model = new CentreModel();
        $tipus_inventari_model = new TipusInventariModel();

        $actor = session()->get('user_data');
        $role = $actor['role'];
        $data['role'] = $role;
        $data['title'] = "Inventari";


        $array_tipus_inventari = $tipus_inventari_model->obtenirTipusInventari();
        $options_tipus_inventari = "";
        for ($i = 0; $i < sizeof($array_tipus_inventari); $i++) {
            if ($array_tipus_inventari[$i]['actiu'] == "1") {
                $options_tipus_inventari .= "<option value=\"" . $array_tipus_inventari[$i]['id_tipus_inventari'] . " - " . $array_tipus_inventari[$i]['nom_tipus_inventari'] . "\">";
                $options_tipus_inventari .= $array_tipus_inventari[$i]['nom_tipus_inventari'];
                $options_tipus_inventari .= "</option>";
            }
        }

        $data['tipus_inventari'] = $options_tipus_inventari;


        if ($role == "admin_sstt") {

            $array_centres = $centre_model->obtenirCentres();
            $options_centres = "";
            for ($i = 0; $i < sizeof($array_centres); $i++) {
                if ($array_centres[$i]['id_sstt'] == $actor['id_sstt'] && $array_centres[$i]['taller'] == 1) {
                    $options_centres .= "<option value=\"" . $array_centres[$i]['codi_centre'] . " - " . $array_centres[$i]['nom_centre'] . "\">";
                    $options_centres .= $array_centres[$i]['nom_centre'];
                    $options_centres .= "</option>";
                }
            }
            $data['centres'] = $options_centres;
        } else if ($role == "desenvolupador") {
            $array_centres = $centre_model->obtenirCentres();
            $options_centres = "";
            for ($i = 0; $i < sizeof($array_centres); $i++) {
                if ($array_centres[$i]['taller'] == 1) {
                    $options_centres .= "<option value=\"" . $array_centres[$i]['codi_centre'] . " - " . $array_centres[$i]['nom_centre'] . "\">";
                    $options_centres .= $array_centres[$i]['nom_centre'];
                    $options_centres .= "</option>";
                }
            }
            $data['centres'] = $options_centres;
        }

        return view('formularis' . DIRECTORY_SEPARATOR . 'formulariAfegirInventari', $data);
    }

    public function crearInventari_post()
    {
        $inventari_model = new InventariModel();
        $centre_model = new CentreModel();
        $tipus_inventari_model = new TipusInventariModel();

        $actor = session()->get('user_data');
        $role = $actor['role'];

        if ($role == "professor") {

            $validationRules = [
                'quantitat' => [
                    'rules'  => 'required',
                    'errors' => [
                        'required' => lang('inventari.errors.quantitat_required'),
                    ],
                ],
                'tipus_inventari' => [
                    'rules'  => 'required',
                    'errors' => [
                        'required' => lang('inventari.errors.tipus_inventari_required'),
                    ],
                ],
                'preu' => [
                    'rules'  => 'required',
                    'errors' => [
                        'required' => lang('inventari.errors.preu_required'),
                    ],
                ],
                'descripcio_inventari' => [
                    'rules' => 'required|max_length[512]',
                    'errors' => [
                        'required' => lang('inventari.errors.descripcio_inventari_required'),
                        'max_length' => lang('inventari.errors.descripcio_inventari_max'),
                    ],
                ],
            ];
        } else if ($role == "admin_sstt" || $role == "desenvolupador") {

            $validationRules = [
                'quantitat' => [
                    'rules'  => 'required',
                    'errors' => [
                        'required' => lang('inventari.errors.quantitat_required'),
                    ],
                ],
                'tipus_inventari' => [
                    'rules'  => 'required',
                    'errors' => [
                        'required' => lang('inventari.errors.tipus_inventari_required'),
                    ],
                ],
                'preu' => [
                    'rules'  => 'required',
                    'errors' => [
                        'required' => lang('inventari.errors.preu_required'),
                    ],
                ],
                'descripcio_inventari' => [
                    'rules' => 'required|max_length[512]',
                    'errors' => [
                        'required' => lang('inventari.errors.descripcio_inventari_required'),
                        'max_length' => lang('inventari.errors.descripcio_inventari_max'),
                    ],
                ],
                'codi_centre' => [
                    'rules'  => 'required',
                    'errors' => [
                        'required' => lang('inventari.errors.codi_centre_required'),
                    ],
                ],
            ];
        }


        if ($this->validate($validationRules)) {

            if ($role == "centre_emissor") {
                return redirect()->to(base_url('/tiquets'));
            } else if ($role == "alumne" || $role == "centre_reparador" || $role == "sstt") {
                return redirect()->to(base_url('/inventari'));
            } else {

                $descripcio_inventari = $this->request->getPost('descripcio_inventari');
                $data_compra = date("Y-m-d");
                $preu = $this->request->getPost('preu');
                $quantitat = $this->request->getPost('quantitat');

                $tipus_inventari = $this->request->getPost('tipus_inventari');
                $id_tipus_inventari = trim(explode('-', (string) $tipus_inventari)[0]);

                $tipus_inventari_obtingut = $tipus_inventari_model->obtenirTipusInventariPerId($id_tipus_inventari);
                if ($tipus_inventari_obtingut == null || $tipus_inventari_obtingut['actiu'] == "0") {
                    $msg = lang("alertes.filter_error_tipus_dispositiu");
                    session()->setFlashdata("escriure_malament", $msg);
                    return redirect()->back()->withInput();
                }

                if ($role == "professor") {

                    for ($i = 0; $i < $quantitat; $i++) {
                        $uuid_library = new \App\Libraries\UUID;
                        $uuid = $uuid_library->v4();
                        $inventari_model->addInventari($uuid, $descripcio_inventari, $data_compra, $preu, $actor['codi_centre'], $id_tipus_inventari, null);
                        $msg = lang('alertes.flash_data_create_inventari');
                        session()->setFlashdata('afegirInventari', $msg);
                    }
                } else if ($role == "admin_sstt") {

                    $codi_centre = $this->request->getPost('codi_centre');
                    $codi_centre = trim(explode('-', (string) $codi_centre)[0]);

                    if ($centre_model->obtenirCentre($codi_centre)['id_sstt'] == $actor['id_sstt']) {

                        for ($i = 0; $i < $quantitat; $i++) {
                            $uuid_library = new \App\Libraries\UUID;
                            $uuid = $uuid_library->v4();
                            $inventari_model->addInventari($uuid, $descripcio_inventari, $data_compra, $preu, $codi_centre, $id_tipus_inventari, null);
                            $msg = lang('alertes.flash_data_create_inventari');
                            session()->setFlashdata('afegirInventari', $msg);
                        }
                    } else {
                        return redirect()->back()->withInput();
                    }
                } else if ($role == "desenvolupador") {

                    $codi_centre = $this->request->getPost('codi_centre');
                    $codi_centre = trim(explode('-', (string) $codi_centre)[0]);

                    for ($i = 0; $i < $quantitat; $i++) {
                        $uuid_library = new \App\Libraries\UUID;
                        $uuid = $uuid_library->v4();
                        $inventari_model->addInventari($uuid, $descripcio_inventari, $data_compra, $preu, $codi_centre, $id_tipus_inventari, null);
                        $msg = lang('alertes.flash_data_create_inventari');
                        session()->setFlashdata('afegirInventari', $msg);
                    }
                }

                return redirect()->to(base_url('/inventari'));
            }
        } else {
            return redirect()->back()->withInput();
        }
    }


    public function eliminarInventari($id_inventari_eliminar)
    {
        $inventari_model = new InventariModel();
        $centre_model = new CentreModel();

        $inventari_eliminar = $inventari_model->obtenirInventariPerId($id_inventari_eliminar);
        if ($inventari_eliminar != null) {
            $actor = session()->get('user_data');
            $role = $actor['role'];

            if ($role == "centre_emissor") {
                return redirect()->to(base_url('/tiquets'));
            } else if ($role == "alumne" || $role == "centre_reparador" || $role == "sstt") {
                $msg = lang("inventari.no_permisos");
                session()->setFlashdata("no_permisos", $msg);
                return redirect()->to(base_url('/inventari'));
            } else {
                if ($role == "professor" && $inventari_eliminar['codi_centre'] == $actor['codi_centre'] && $inventari_eliminar['id_intervencio'] == null) {

                    $inventari_model->deleteInventari($inventari_eliminar['id_inventari']);
                    $msg = lang('alertes.flash_data_delete_inventari') . $inventari_eliminar['id_inventari'];
                    session()->setFlashdata('esborrarInventari', $msg);
                } else if ($role == "admin_sstt" && $centre_model->obtenirCentre($inventari_eliminar['codi_centre'])['id_sstt'] == $actor['id_sstt']) {
                    // Admin SSTT pot esborrar una peça encara que estigui assignada a una intervenció. La intervenció, deixarà de tenir la peça
                    $inventari_model->deleteInventari($inventari_eliminar['id_inventari']);
                    $msg = lang('alertes.flash_data_delete_inventari') . $inventari_eliminar['id_inventari'];
                    session()->setFlashdata('esborrarInventari', $msg);
                } else if ($role == "desenvolupador") {

                    $inventari_model->deleteInventari($inventari_eliminar['id_inventari']);
                    $msg = lang('alertes.flash_data_delete_inventari') . $inventari_eliminar['id_inventari'];
                    session()->setFlashdata('esborrarInventari', $msg);
                } else {
                    $msg = lang("inventari.no_permisos");
                    session()->setFlashdata("no_permisos", $msg);
                }
                
                return redirect()->to(base_url('/inventari'));
            }
        } else {
            return redirect()->to(base_url('/inventari/esborrar/' . $id_inventari_eliminar));
        }
    }

    public function assignarInventari($id_tiquet, $id_intervencio)
    {

        $tiquet_model = new TiquetModel();
        $intervencio_model = new IntervencioModel();
        $inventari_model = new InventariModel();
        $tipus_inventari_model = new TipusInventariModel();
        $estat_model = new EstatModel();
        $centre_model = new CentreModel();

        $actor = session()->get('user_data');
        $role = $actor['role'];

        $tiquet = $tiquet_model->getTiquetById($id_tiquet);
        $intervencio = $intervencio_model->obtenirIntervencioPerId($id_intervencio);
        if ($tiquet != null && $intervencio != null) {

            $estat = $estat_model->obtenirEstatPerId($tiquet['id_estat']);
            $data['estat'] = $estat;

            // KPACRUD INVENTARI ASSIGNAT A LA INTERVENCIÓ
            $data['title'] = 'Inventari Assignat';
            $data['id_tiquet'] = $id_tiquet;
            $data['id_intervencio'] = $id_intervencio;

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
            $crud->setTable('vista_inventari');
            $crud->setPrimaryKey('id_inventari');
            $crud->hideHeadLink([
                'js-bootstrap',
                'css-bootstrap',
            ]);
            $crud->setColumns([
                'id_inventari_limitat',
                'nom_tipus_inventari',
                'data_compra'
            ]);
            $crud->setColumnsInfo([
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

            if (($role == "alumne" || $role == "professor") && ($estat == "Pendent de reparar" || $estat == "Reparant")) {
                $crud->addItemLink('delete', 'fa-trash', base_url('inventari/desassignar'), 'Desassignar Peça');

                $crud->addWhere('codi_centre', $actor['codi_centre']);
                $crud->addWhere('id_intervencio', $id_intervencio, true);
            } else if ($role == "admin_sstt") {
                $crud->addItemLink('delete', 'fa-trash', base_url('inventari/desassignar'), 'Desassignar Peça');

                $crud->addWhere('id_sstt', $actor['id_sstt']);
                $crud->addWhere('id_intervencio', $id_intervencio, true);
            } else if ($role == "desenvolupador") {
                $crud->addItemLink('delete', 'fa-trash', base_url('inventari/desassignar'), 'Desassignar Peça');
                $crud->addWhere('id_intervencio', $id_intervencio, true);
            }



            $data['output'] = $crud->render();

            // Carregar peces d'inventari
            if (($role == "alumne" || $role == "professor") && ($estat == "Pendent de reparar" || $estat == "Reparant")) {
                $array_inventari = $inventari_model->obtenirInventariCentre($actor['codi_centre']);
            } else if ($role == "admin_sstt") {

                $array_inventari = $inventari_model->obtenirInventariCentre($tiquet['codi_centre_reparador']);
            } else if ($role == "desenvolupador") {

                $array_inventari = $inventari_model->obtenirInventariCentre($tiquet['codi_centre_reparador']);
            }

            $data['inventari_list'] = "";
            for ($i = 0; $i < sizeof($array_inventari); $i++) {

                if ($array_inventari[$i]['id_intervencio'] == null) {
                    $nom_tipus_inventari = $tipus_inventari_model->obtenirTipusInventariPerId($array_inventari[$i]['id_tipus_inventari'])['nom_tipus_inventari'];
                    $data['inventari_list'] .= "<option value=\"" . $nom_tipus_inventari . " // " . $array_inventari[$i]['data_compra'] . " // " . $array_inventari[$i]['id_inventari'] . "\">" . $nom_tipus_inventari . " // " . $array_inventari[$i]['data_compra'] . " // " . $array_inventari[$i]['id_inventari'] . "</option>";
                }
            }

            return view('registres' . DIRECTORY_SEPARATOR . 'registreInventariAssignat', $data);
        } else {
            return redirect()->back();
        }
    }

    public function assignarInventari_post($id_tiquet, $id_intervencio, $editar)
    {
        $tiquet_model = new TiquetModel();
        $intervencio_model = new IntervencioModel();
        $inventari_model = new InventariModel();
        $estat_model = new EstatModel();

        $actor = session()->get('user_data');
        $role = $actor['role'];

        $tiquet = $tiquet_model->getTiquetById($id_tiquet);
        $intervencio = $intervencio_model->obtenirIntervencioPerId($id_intervencio);
        if ($tiquet != null && $intervencio != null) {

            $inventari_post = $this->request->getPost('inventari');
            if ($inventari_post != "") {

                if (substr_count((string) $inventari_post, " // ") != 2) {
                    $msg = lang('alertes.error_tipus_inventari_assignar');
                    session()->setFlashdata('error_tipus_inventari', $msg);
                    return redirect()->back();
                }

                $id_inventari = trim(explode('//', (string) $inventari_post)[2]);
                $tipus_inventari = trim(explode('//', (string) $inventari_post)[0]);
                $inventari = $inventari_model->obtenirInventariPerId($id_inventari);

                if ($inventari != null && $inventari['id_intervencio'] == null) {
                    $estat = $estat_model->obtenirEstatPerId($tiquet['id_estat']);

                    if (($role == "alumne" || $role == "professor") && ($estat == "Pendent de reparar" || $estat == "Reparant")) {
                        $inventari_model->editarInventariAssignar($id_inventari, $id_intervencio);
                        $msg = lang('alertes.flash_data_assignar_inventari') . $tipus_inventari;
                        session()->setFlashdata('assignarInventari', $msg);
                    } else if ($role == "admin_sstt" || $role == "desenvolupador") {
                        $inventari_model->editarInventariAssignar($id_inventari, $id_intervencio);
                        $msg = lang('alertes.flash_data_assignar_inventari') . $tipus_inventari;
                        session()->setFlashdata('assignarInventari', $msg);
                    }


                    $data = [
                        "preu_total" => $tiquet['preu_total'] + $inventari['preu'],
                    ];

                    $tiquet_model->updateTiquet($tiquet['id_tiquet'], $data);
                }
            }
            if ($editar == "noeditar") {
                return redirect()->to(base_url('/tiquets/' . $id_tiquet . "/assignar/" . $id_intervencio));
            } else if ($editar == "editar") {
                return redirect()->to('editar/intervencio/' . $id_tiquet . '/' . $id_intervencio);
            }
            
        }
    }

    public function desassignarInventari($id_inventari, $editar = null)
    {
        $inventari_model = new InventariModel();
        $intervencio_model = new IntervencioModel();
        $tiquet_model = new TiquetModel();
        $estat_model = new EstatModel();

        $inventari_desassignar = $inventari_model->obtenirInventariPerId($id_inventari);

        if ($inventari_desassignar != null) {

            $actor = session()->get('user_data');
            $role = $actor['role'];

            if ($role == "centre_emissor") {
                return redirect()->back();
            } else if ($role == "centre_reparador" || $role == "sstt") {
                return redirect()->back();
            } else {

                $id_intervencio = $inventari_desassignar['id_intervencio'];
                $id_tiquet = $intervencio_model->obtenirIntervencioPerId($id_intervencio)['id_tiquet'];
                $tiquet = $tiquet_model->getTiquetById($id_tiquet);
                $estat = $estat_model->obtenirEstatPerId($tiquet['id_estat']);

                if (($role == "alumne" || $role == "professor") && ($estat == "Pendent de reparar" || $estat == "Reparant")) {
                    $inventari_model->editarInventariDesassignar($id_inventari);
                    $msg = lang('alertes.flash_data_desasignar_inventari') . $id_inventari;
                    session()->setFlashdata('desassingarInventari', $msg);
                } else if ($role == "admin_sstt" || $role == "desenvolupador") {
                    $inventari_model->editarInventariDesassignar($id_inventari);
                    $msg = lang('alertes.flash_data_desasignar_inventari') . $id_inventari;
                    session()->setFlashdata('desassingarInventari', $msg);
                }

                $data = [
                    "preu_total" => $tiquet['preu_total'] - $inventari_desassignar['preu'],
                ];

                $tiquet_model->updateTiquet($tiquet['id_tiquet'], $data);

                if ($editar == null) {
                    return redirect()->to(base_url('/tiquets/' . $id_tiquet . "/assignar/" . $id_intervencio));
                } else if ($editar == "editar") {
                    return redirect()->to('editar/intervencio/' . $id_tiquet . '/' . $id_intervencio);
                }
            }
        } else {
            return redirect()->back();
        }
    }

    public function selectorTipusDispositiu()
    {
        $tipus_dispositius = new TipusInventariModel();
        $array_tipus_dispositius = $tipus_dispositius->obtenirTipusInventari();
        $array_tipus_dispositius_nom = [];
        $sessio_filtres = session()->get('filtresInventari');
        $model_tipus_inventari = new TipusInventariModel();
        $tipus_inventari = '';

        if (isset($sessio_filtres['tipus_dispositiu'])) {
            $tipus_inventari = $model_tipus_inventari->obtenirTipusInventariPerId($sessio_filtres['tipus_dispositiu'][0]);
        }
        $options_tipus_dispositius = "";
        $options_tipus_dispositius .= "<option value='' selected disabled>" . lang('registre.not_value_option_select_tipus_dispositiu') . "</option>";
        for ($i = 0; $i < sizeof($array_tipus_dispositius); $i++) {
            if ($array_tipus_dispositius[$i]['actiu'] == "1") {
                if (isset($sessio_filtres['tipus_dispositiu']) && $tipus_inventari['nom_tipus_inventari'] == $array_tipus_dispositius[$i]['nom_tipus_inventari']) {
                    $options_tipus_dispositius .= "<option value=\"" . $array_tipus_dispositius[$i]['id_tipus_inventari'] . " - " . $array_tipus_dispositius[$i]['nom_tipus_inventari'] . "\" selected>";
                } else {
                    $options_tipus_dispositius .= "<option value=\"" . $array_tipus_dispositius[$i]['id_tipus_inventari'] . " - " . $array_tipus_dispositius[$i]['nom_tipus_inventari'] . "\">";
                }

                $options_tipus_dispositius .= $array_tipus_dispositius[$i]['nom_tipus_inventari'];
                $options_tipus_dispositius .= "</option>";
                $array_tipus_dispositius_nom[$i] = $array_tipus_dispositius[$i]['nom_tipus_inventari'];
            }
        }

        return $options_tipus_dispositius;
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

    public function selectorPoblacio($role, $actor)
    {
        $poblacio_model = new PoblacioModel();
        $array_poblacions = $poblacio_model->obtenirPoblacions();
        $options_poblacions = "";
        for ($i = 0; $i < sizeof($array_poblacions); $i++) {
            if ($array_poblacions[$i]['actiu'] == "1") {
                $options_poblacions .= "<option value=\"" . $array_poblacions[$i]['id_poblacio'] . " - " . $array_poblacions[$i]['nom_poblacio'] . "\">";
                $options_poblacions .= $array_poblacions[$i]['nom_poblacio'];
                $options_poblacions .= "</option>";
            }
        }

        return $options_poblacions;
    }

    public function selectorComarca($role, $actor)
    {
        $comarca_model = new ComarcaModel();
        $array_comarques = $comarca_model->obtenirComarques();
        $options_comarques = "";
        for ($i = 0; $i < sizeof($array_comarques); $i++) {
            if ($array_comarques[$i]['actiu'] == "1") {
                $options_comarques .= "<option value=\"" . $array_comarques[$i]['id_comarca'] . " - " . $array_comarques[$i]['nom_comarca'] . "\">";
                $options_comarques .= $array_comarques[$i]['nom_comarca'];
                $options_comarques .= "</option>";
            }
        }

        return $options_comarques;
    }

    public function filtrePost()
    {
        $centre_model = new CentreModel();
        $poblacio_model = new PoblacioModel();
        $comarca_model = new ComarcaModel();

        $session = session();
        $sessio_filtres = $session->get('filtresInventari');

        $eliminar = $this->request->getPost('submit_eliminar_filtres');

        if ($eliminar !== null) {
            $session->remove('filtresInventari');
        } else {

            if ($sessio_filtres == null) {
                $filtres = [];
                $session->set('filtresInventari', $filtres);
            }

            $dades = $this->request->getPost();

            if (isset($dades['selector_tipus_dispositiu'])) {
                $array_tipus_dispositiu = [];
                $tipus_dispositiu_seleccionat = $dades['selector_tipus_dispositiu'];
                $tipus_dispositiu = trim(explode('-', (string) $tipus_dispositiu_seleccionat)[0]);
                array_push($array_tipus_dispositiu, $tipus_dispositiu);
                $session->push('filtresInventari', ['tipus_dispositiu' => $array_tipus_dispositiu]);
            }
            if (isset($dades['nom_centre_reparador_list']) && $dades['nom_centre_reparador_list'] !== '') {

                $array_centre_reparador = [];
                $nom_centre_reparador = $dades['nom_centre_reparador_list'];
                $centre_reparador = trim(explode('-', (string) $nom_centre_reparador)[0]);

                if ($centre_reparador != null && $centre_model->obtenirCentre($centre_reparador) == null) {
                    $msg = lang('alertes.filter_error_centre_reparador');
                    session()->setFlashdata('error_filtre', $msg);
                    return redirect()->back()->withInput();
                }

                array_push($array_centre_reparador, $centre_reparador);
                $session->push('filtresInventari', ['nom_centre_reparador' => $array_centre_reparador]);
            }
            if (isset($dades['data_creacio_inici']) && $dades['data_creacio_inici'] !== '' && isset($dades['data_creacio_fi']) &&  $dades['data_creacio_fi'] !== '') {
                $array_data_creacio_inici = [];
                $data_creacio_inici = $dades['data_creacio_inici'];
                array_push($array_data_creacio_inici, $data_creacio_inici);
                $session->push('filtres', ['data_creacio_inici' => $array_data_creacio_inici]);
                $array_data_creacio_fi = [];
                $data_creacio_fi = $dades['data_creacio_fi'];
                array_push($array_data_creacio_fi, $data_creacio_fi);
                $session->push('filtres', ['data_creacio_fi' => $array_data_creacio_fi]);
            }
            if (isset($dades['nom_poblacio_list']) && $dades['nom_poblacio_list'] !== '') {

                $array_poblacio = [];
                $nom_poblacio = $dades['nom_poblacio_list'];
                $poblacio = trim(explode('-', (string) $nom_poblacio)[0]);

                if ($poblacio != null && $poblacio_model->getPoblacio($poblacio) == null) {
                    $msg = lang('alertes.filter_error_centre_reparador');
                    session()->setFlashdata('error_filtre', $msg);
                    return redirect()->back()->withInput();
                }

                array_push($array_poblacio, $poblacio);
                $session->push('filtresInventari', ['nom_poblacio' => $array_poblacio]);
            }
            if (isset($dades['nom_comarca_list']) && $dades['nom_comarca_list'] !== '') {

                $array_comarca = [];
                $nom_comarca = $dades['nom_comarca_list'];
                $comarca = trim(explode('-', (string) $nom_comarca)[0]);

                if ($comarca != null && $comarca_model->obtenirComarca($comarca) == null) {
                    $msg = lang('alertes.filter_error_centre_reparador');
                    session()->setFlashdata('error_filtre', $msg);
                    return redirect()->back()->withInput();
                }

                array_push($array_comarca, $comarca);
                $session->push('filtresInventari', ['nom_comarca' => $array_comarca]);
            }
        }
        return redirect()->back()->withInput();
    }

    public function eliminarFiltre()
    {
        $filtre_eliminar = $this->request->getPost();
        $filtre_session = session()->get('filtresInventari');
        $eliminar = $this->request->getPost('submit_eliminar_filtres');
        if ($eliminar !== null) {
            session()->remove('filtresInventari');
        }
        if ($filtre_eliminar['operacio'] === 'Dispositiu') {
            unset($filtre_session['tipus_dispositiu']);
            session()->set('filtresInventari', $filtre_session);
        }
        if ($filtre_eliminar['operacio'] === 'Estat') {
            unset($filtre_session['estat']);
            session()->set('filtresInventari', $filtre_session);
        }
        if ($filtre_eliminar['operacio'] == 'Centre_emissor') {
            unset($filtre_session['nom_centre_emissor']);
            session()->set('filtresInventari', $filtre_session);
        }
        if ($filtre_eliminar['operacio'] == 'Centre_reparador') {
            unset($filtre_session['nom_centre_reparador']);
            session()->set('filtresInventari', $filtre_session);
        }
        if ($filtre_eliminar['operacio'] == 'data_creacio') {
            unset($filtre_session['data_creacio']);
            session()->set('filtresInventari', $filtre_session);
        }
        if (count($filtre_session) == 0) {
            session()->remove('filtresInventari');
        }
        if ($filtre_eliminar['operacio'] == 'data_creacio_inici') {
            unset($filtre_session['data_creacio_inici']);
            session()->set('filtres', $filtre_session);
            unset($filtre_session['data_creacio_fi']);
            session()->set('filtres', $filtre_session);
        }

        return redirect()->back()->withInput();
    }

    function triar_Inventari(){
        $inventari = $this->request->getPost()['select_inventari'];
        //dd($this->request->getPost());
        if($inventari == "no_assignat"){
            return $this->registreInventari("no_assignat", null);
        } else if ($inventari == "assignat"){
            return $this->registreInventari( "assignat", null);
        } else if($inventari == "tot") {
            return $this->registreInventari( "tot", null);
        } else {
            return $this->registreInventari( null, null);
        }
    }
}
