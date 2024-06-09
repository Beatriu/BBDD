<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CentreModel;
use App\Models\PoblacioModel;
use CodeIgniter\HTTP\ResponseInterface;
use SIENSIS\KpaCrud\Libraries\KpaCrud;

class CentresController extends BaseController
{
    public function registreCentres()
    {
        $role = session()->get('user_data')['role'];
        if ($role == 'sstt' || $role == 'admin_sstt' || $role == 'desenvolupador') {

            $actor = session()->get('user_data');
            $data['role'] = $role;
            $data['title'] = lang('registre.titol_centres');


            $crud = new KpaCrud();                              
            $crud->setConfig('onlyView');                   
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
            ]);   
            $crud->setTable('vista_centres');                    
            $crud->setPrimaryKey('codi_centre');
            $crud->hideHeadLink([
                'js-bootstrap',
                'css-bootstrap',
            ]);
            $crud->addItemLink('edit', 'fa-pencil', base_url('/tiquets/editar'), 'Editar Tiquet');
            $crud->addItemLink('view', 'fa-eye', base_url('/centres/filtre'), 'Veure Tiquet');

            $crud->setColumns([
                'codi_centre',
                'nom_centre',
                'actiu',
                'taller',
                'telefon_centre',
                'correu_persona_contacte_centre',
                'adreca_fisica_centre',
                'nom_poblacio',
                'nom_comarca',
                'Preu_total',
                'Tiquets_del_centre'
            ]);
            $crud->setColumnsInfo([
                'codi_centre' => [
                    'name' => lang("centre.codi_centre")
                ],
                'nom_centre' => [
                    'name' => lang("centre.nom_centre")
                ],
                'actiu' => [
                    'name' => lang("centre.actiu")
                ],
                'taller' => [
                    'name' => lang("centre.taller")
                ],
                'telefon_centre' => [
                    'name' => lang("centre.telefon_centre")
                ],
                'correu_persona_contacte_centre' => [
                    'name' => lang("centre.nom_correu_persona_contacte_centre")
                ],
                'adreca_fisica_centre' => [
                    'name' => lang("centre.adreca")
                ],
                'nom_poblacio' => [
                    'name' => lang("centre.nom_poblacio")
                ],
                'nom_comarca' => [
                    'name' => lang("centre.nom_comarca")
                ],
                'Preu_total' => [
                    'name' => lang("centre.preu_total")
                ],
                'Tiquets_del_centre' => [
                    'name' => lang("centre.Tiquets_del_centre")
                ],

            ]);

            if ($role == 'sstt' || $role == 'admin_sstt') {
                $crud->addWhere('id_sstt', $actor['id_sstt']);
            }

            $data['output'] = $crud->render();

            return view('registres' . DIRECTORY_SEPARATOR . 'registreCentres', $data);
        } else {
            return redirect()->back();
        }
    }

    public function crear_centre()
    {
        $data['title'] = lang('centre.formulari_centre');
        $role = session()->get('user_data')['role'];
        if ($role == 'sstt' || $role == 'admin_sstt' || $role == 'desenvolupador') {

            $actor = session()->get('user_data');

            $poblacio_model = new PoblacioModel();
            $array_poblacions = $poblacio_model->obtenirPoblacions();
            $options_poblacions = "";
            for ($i = 0; $i < sizeof($array_poblacions); $i++) {
                if(($role == 'sstt' || $role == 'admin_sstt') && $actor['id_sstt'] == $array_poblacions[$i]['id_sstt']){
                    if ($array_poblacions[$i]['actiu'] == "1") {
                        $options_poblacions .= "<option value=\"" . $array_poblacions[$i]['id_poblacio'] . " - " . $array_poblacions[$i]['nom_poblacio'] . "\">";
                        $options_poblacions .= $array_poblacions[$i]['nom_poblacio'];
                        $options_poblacions .= "</option>";
                    }
                } else if($role == 'desenvolupador'){
                    if ($array_poblacions[$i]['actiu'] == "1") {
                        $options_poblacions .= "<option value=\"" . $array_poblacions[$i]['id_poblacio'] . " - " . $array_poblacions[$i]['nom_poblacio'] . "\">";
                        $options_poblacions .= $array_poblacions[$i]['nom_poblacio'];
                        $options_poblacions .= "</option>";
                    }
                }
                
            }

            $data['poblacions'] = $options_poblacions;
            return view('formularis' . DIRECTORY_SEPARATOR . 'formulariAfegirCentre', $data);
        } else {
            return redirect()->back();
        }
    }

    public function crear_centre_post()
    {
        $role = session()->get('user_data')['role'];
        $actor = session()->get('user_data');

        $validationRules = [
            'codi_centre' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => lang('alumne.correu_alumne_required'),
                ],
            ],
            'nom_centre' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => lang('alumne.correu_alumne_required'),
                ],
            ],
            'centre_actiu' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => lang('alumne.correu_alumne_required'),
                ],
            ],
            'centre_taller' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => lang('general_lang.contrasenya_required'),
                ],
            ],
            'nom_poblacio' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => lang('general_lang.contrasenya_required'),
                ],
            ],
            'login_centre' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => lang('general_lang.contrasenya_required'),
                ],
            ],
        ];

        if ($this->validate($validationRules)) {

            $codi_centre_i_nom = $this->request->getPost('codi_centre');
            $codi_centre = trim(explode('-', (string) $codi_centre_i_nom)[0]);
            $nom_centre = $this->request->getPost('nom_centre');
            $centre_actiu = $this->request->getPost('centre_actiu');
            $centre_taller = $this->request->getPost('centre_taller');
            $telefon_centre = $this->request->getPost('telefon_centre');
            $adreca_centre = $this->request->getPost('adreca');
            $nom_persona_de_contacte = $this->request->getPost('nom_persona_de_contacte');
            $correu_persona_contacte = $this->request->getPost('correu_persona_contacte');
            $nom_poblacio = $this->request->getPost('nom_poblacio');
            $codi_poblacio = trim(explode('-', (string) $nom_poblacio)[0]);
            $login_centre = $this->request->getPost('login_centre');

            $model_centre = new CentreModel();
            $poblacio_model = new PoblacioModel();
            $centre = $model_centre->obtenirCentre($codi_centre);
            
            if($centre == null){
                if($role == 'sstt' || $role == 'admin_sstt'){
                    $activitat_centre = false;
                    $es_taller = false;
                    if($centre_actiu == 'actiu'){
                        $activitat_centre = true;
                    }
                    if($centre_taller == 'taller'){
                        $es_taller = true;
                    }
                    $id_comarca = $poblacio_model->getPoblacio($codi_poblacio)['id_comarca'];
                    $model_centre->addCentre($codi_centre, $nom_centre, $activitat_centre, $es_taller, $telefon_centre, $adreca_centre, $nom_persona_de_contacte, $correu_persona_contacte, $actor['id_sstt'], $codi_poblacio, $id_comarca, $login_centre);
                } else {
                    $activitat_centre = false;
                    $es_taller = false;
                    if($centre_actiu == 'actiu'){
                        $activitat_centre = true;
                    }
                    if($centre_taller == 'taller'){
                        $es_taller = true;
                    }
                    $model_poblacio = new PoblacioModel();
                    $poblacio = $model_poblacio->getPoblacio($codi_poblacio);
                    $id_comarca = $poblacio_model->getPoblacio($codi_poblacio)['id_comarca'];
                    $model_centre->addCentre($codi_centre, $nom_centre, $activitat_centre, $es_taller, $telefon_centre, $adreca_centre, $nom_persona_de_contacte, $correu_persona_contacte, $poblacio['id_sstt'], $codi_poblacio, $id_comarca, $login_centre);
                }
                return redirect()->to(base_url('/centres'));
            } else {
                $msg = lang('alertes.centre_existeix');
                session()->setFlashdata('afegirCentre', $msg);
                return redirect()->back()->withInput();
            }
            
        } else {
            $msg = lang('alertes.centre_existeix');
            session()->setFlashdata('afegirCentre_requerits', $msg);
            return redirect()->back()->withInput();
        }
    }

    public function filtrar_centre($codi_centre){
        $filtre_session = session()->get('filtres');
        $session = session();

        if($filtre_session !== null){
            session()->remove('filtres');
        } else {
            $filtres = [];
            $session->set('filtres', $filtres);
        }

        $model_centre = new CentreModel();
        $centre = $model_centre->obtenirCentre($codi_centre);

        if($centre['taller'] == false){
            $array_centre_emissor = [];
            array_push($array_centre_emissor, $codi_centre);
            $session->push('filtres', ['nom_centre_emissor' => $array_centre_emissor]);
        } else {
            $array_centre_reparador = [];
            array_push($array_centre_reparador, $codi_centre);
            $session->push('filtres', ['nom_centre_reparador' => $array_centre_reparador]);
        }
        
        return redirect()->to('tiquets');
    }
}
