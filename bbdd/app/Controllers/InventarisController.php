<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CentreModel;
use App\Models\EstatModel;
use App\Models\IntervencioModel;
use App\Models\InventariModel;
use App\Models\TipusInventariModel;
use App\Models\TiquetModel;
use SIENSIS\KpaCrud\Libraries\KpaCrud;

class InventarisController extends BaseController
{
    protected $helpers = ['form'];
    
    public function index()
    {

    }

    public function registreInventari($id_inventari = null)
    {
        $inventari_model = new InventariModel();
        $centre_model = new CentreModel();

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
                        session()->setFlashdata("id_inventari",$inventari_eliminar);
            
                    } else {
                        $data['no_permisos'] = "inventari.no_permisos";
                    }
                } else {
                    $data['no_permisos'] = "inventari.no_existeix";
                }

    
            }

            $data['title'] = 'Inventari';

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
            
            if ($role == "alumne" || $role == "professor" || $role == "centre_reparador") {

                $crud->setColumns([
                    'id_inventari',
                    'nom_tipus_inventari',
                    'descripcio_inventari_limitada',
                    'data_compra',
                    'id_intervencio'
                ]);
                $crud->setColumnsInfo([
                    'id_inventari' => [
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
                ]);

                $crud->addWhere('codi_centre', $actor['codi_centre']);

            } else if ($role == "sstt" || $role == "admin_sstt") {

                $crud->setColumns([
                    'id_inventari',
                    'nom_tipus_inventari',
                    'descripcio_inventari_limitada',
                    'data_compra',
                    'id_intervencio',
                    'nom_centre'
                ]);
                $crud->setColumnsInfo([
                    'id_inventari' => [
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
                    ]
                ]);

                $crud->addWhere('id_sstt', $actor['id_sstt']);

            } else if ($role == "desenvolupador") {

                $crud->setColumns([
                    'id_inventari',
                    'nom_tipus_inventari',
                    'descripcio_inventari_limitada',
                    'data_compra',
                    'id_intervencio',
                    'nom_centre',
                    'nom_sstt'
                ]);
                $crud->setColumnsInfo([
                    'id_inventari' => [
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
                    ]
                ]);

            }
            if($role !== 'sstt' && $role !== 'alumne'){
                $crud->addItemLink('delete', 'fa-trash', base_url('inventari/esborrar'), 'Eliminar Peça');
            }
            
    
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
        $options_tipus_intervencio = "";
        for ($i = 0; $i < sizeof($array_tipus_inventari); $i++) {
            $options_tipus_intervencio .= "<option value=\"" . $array_tipus_inventari[$i]['id_tipus_inventari'] . " - " . $array_tipus_inventari[$i]['nom_tipus_inventari'] . "\">";
            $options_tipus_intervencio .= $array_tipus_inventari[$i]['nom_tipus_inventari'];
            $options_tipus_intervencio .= "</option>";
        }


        $data['tipus_inventari'] = $options_tipus_intervencio;


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

                if ($role == "professor") {

                    for ($i = 0; $i < $quantitat; $i++) {
                        $uuid_library = new \App\Libraries\UUID;
                        $uuid = $uuid_library->v4();
                        $inventari_model->addInventari($uuid, $descripcio_inventari, $data_compra, $preu, $actor['codi_centre'], $id_tipus_inventari, null);
                    }

                } else if ($role == "admin_sstt") {

                    $codi_centre = $this->request->getPost('codi_centre');
                    $codi_centre = trim(explode('-', (string) $codi_centre)[0]);

                    if ($centre_model->obtenirCentre($codi_centre)['id_sstt'] == $actor['id_sstt']) {
                        
                        for ($i = 0; $i < $quantitat; $i++) {
                            $uuid_library = new \App\Libraries\UUID;
                            $uuid = $uuid_library->v4();
                            $inventari_model->addInventari($uuid, $descripcio_inventari, $data_compra, $preu, $codi_centre, $id_tipus_inventari, null);
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
                return redirect()->to(base_url('/inventari'));
            } else {

                if ($role == "professor" && $inventari_eliminar['codi_centre'] == $actor['codi_centre'] && $inventari_eliminar['id_intervencio'] == null) {

                    $inventari_model->deleteInventari($inventari_eliminar['id_inventari']);

                } else if ($role == "admin_sstt" && $centre_model->obtenirCentre($inventari_eliminar)['id_sstt'] == $actor['id_sstt']) {

                    $inventari_model->deleteInventari($inventari_eliminar['id_inventari']);

                } else if ($role == "desenvolupador") {

                    $inventari_model->deleteInventari($inventari_eliminar['id_inventari']);

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

            $crud->setColumns([
                'id_inventari',
                'nom_tipus_inventari',
                'data_compra'
            ]);
            $crud->setColumnsInfo([
                'id_inventari' => [
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
            } else if ($role == "admin_sstt" || $role == "desenvolupador") {
                $crud->addItemLink('delete', 'fa-trash', base_url('inventari/desassignar'), 'Desassignar Peça');
            }

            $crud->addWhere('codi_centre', $actor['codi_centre']);
            $crud->addWhere('id_intervencio', $id_intervencio, true);

            $data['output'] = $crud->render();

            // Carregar peces d'inventari
            $array_inventari = $inventari_model->obtenirInventariCentre($actor['codi_centre']);
            $data['inventari_list'] = "";
            for ($i = 0; $i < sizeof($array_inventari); $i++) {

                if ($array_inventari[$i]['id_intervencio'] == null) {
                    $nom_tipus_inventari = $tipus_inventari_model->obtenirTipusInventariPerId($array_inventari[$i]['id_tipus_inventari'])['nom_tipus_inventari'];
                    $data['inventari_list'] .= "<option value=\"" . $nom_tipus_inventari . " // " . $array_inventari[$i]['data_compra'] . " // " . $array_inventari[$i]['id_inventari'] . "\">" . $nom_tipus_inventari . " // " . $array_inventari[$i]['data_compra'] . " // " . $array_inventari[$i]['id_inventari'] . "</option>";
                }

            }



            return view('registres' . DIRECTORY_SEPARATOR .'registreInventariAssignat', $data);



        } else {
            return redirect()->back();
        }
    }

    public function assignarInventari_post($id_tiquet, $id_intervencio)
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

                $id_inventari = trim(explode('//', (string) $inventari_post)[2]);

                $inventari = $inventari_model->obtenirInventariPerId($id_inventari);
    
                if ($inventari != null && $inventari['id_intervencio'] == null) {
                    $estat = $estat_model->obtenirEstatPerId($tiquet['id_estat']);
    
                    if (($role == "alumne" || $role == "professor") && ($estat == "Pendent de reparar" || $estat == "Reparant")) {
                        $inventari_model->editarInventariAssignar($id_inventari, $id_intervencio);
                    } else if ($role == "admin_sstt" || $role == "desenvolupador") {
                        $inventari_model->editarInventariAssignar($id_inventari, $id_intervencio);
                    }
                }
                
            }

            return redirect()->to(base_url('/tiquets/' . $id_tiquet . "/assignar/" . $id_intervencio));
        }
    }

    public function desassignarInventari($id_inventari)
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
                } else if ($role == "admin_sstt" || $role == "desenvolupador") {
                    $inventari_model->editarInventariDesassignar($id_inventari);
                }
                
                return redirect()->to(base_url('/tiquets/' . $id_tiquet . "/assignar/" . $id_intervencio));

            }

        } else {
            return redirect()->back();
        }
    }
}
