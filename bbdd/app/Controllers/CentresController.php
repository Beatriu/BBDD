<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Database\Seeds\AfegirTiquetSeeder;
use App\Models\CentreModel;
use App\Models\ComarcaModel;
use App\Models\PoblacioModel;
use App\Models\TiquetModel;
use CodeIgniter\HTTP\ResponseInterface;
use SIENSIS\KpaCrud\Libraries\KpaCrud;

class CentresController extends BaseController
{
    public function registreCentres()
    {
        $role = session()->get('user_data')['role'];
        $session_filtre = session()->get('filtresCentres');
        $data['session_filtre'] = $session_filtre;

        if ($role == 'sstt' || $role == 'admin_sstt' || $role == 'desenvolupador') {

            $actor = session()->get('user_data');
            $data['role'] = $role;
            $data['title'] = lang('registre.titol_centres');
            $data['poblacio'] = $this->selectorPoblacio($role, $actor);
            $data['comarca'] = $this->selectorComarca($role, $actor);

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
            $crud->addItemLink('edit', 'fa-pencil', base_url('/centres/editar'), 'Editar Tiquet');
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

            if (is_array($session_filtre)) {
                
                if (isset($session_filtre['actiu'])) {
                    if($session_filtre['actiu'][0] == 'actiu'){
                        $crud->addWhere('actiu', "Si", true);
                        $data['actiu'] = $session_filtre['actiu'][0];
                    } else {
                        $crud->addWhere('actiu', "No", true);
                        $data['actiu'] = lang('registre.radio_innactiu');
                    }
                    
                }
                if (isset($session_filtre['taller'])) {
                    if($session_filtre['taller'][0] == 'taller'){
                        $crud->addWhere('taller', "Si", true);
                        $data['taller'] = $session_filtre['taller'][0];
                    } else {
                        $crud->addWhere('taller', "No", true);
                        $data['taller'] = lang('registre.radio_no_taller');
                    }
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
                if (($role == 'sstt' || $role == 'admin_sstt') && $actor['id_sstt'] == $array_poblacions[$i]['id_sstt']) {
                    if ($array_poblacions[$i]['actiu'] == "1") {
                        $options_poblacions .= "<option value=\"" . $array_poblacions[$i]['id_poblacio'] . " - " . $array_poblacions[$i]['nom_poblacio'] . "\">";
                        $options_poblacions .= $array_poblacions[$i]['nom_poblacio'];
                        $options_poblacions .= "</option>";
                    }
                } else if ($role == 'desenvolupador') {
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

            if ($centre == null) {
                if ($role == 'sstt' || $role == 'admin_sstt') {
                    $activitat_centre = false;
                    $es_taller = false;
                    if ($centre_actiu == 'actiu') {
                        $activitat_centre = true;
                    }
                    if ($centre_taller == 'taller') {
                        $es_taller = true;
                    }
                    $id_comarca = $poblacio_model->getPoblacio($codi_poblacio)['id_comarca'];
                    $model_centre->addCentre($codi_centre, $nom_centre, $activitat_centre, $es_taller, $telefon_centre, $adreca_centre, $nom_persona_de_contacte, $correu_persona_contacte, $actor['id_sstt'], $codi_poblacio, $id_comarca, $login_centre);
                } else {
                    $activitat_centre = false;
                    $es_taller = false;
                    if ($centre_actiu == 'actiu') {
                        $activitat_centre = true;
                    }
                    if ($centre_taller == 'taller') {
                        $es_taller = true;
                    }
                    $model_poblacio = new PoblacioModel();
                    $poblacio = $model_poblacio->getPoblacio($codi_poblacio);
                    $id_comarca = $poblacio_model->getPoblacio($codi_poblacio)['id_comarca'];
                    $model_centre->addCentre($codi_centre, $nom_centre, $activitat_centre, $es_taller, $telefon_centre, $adreca_centre, $nom_persona_de_contacte, $correu_persona_contacte, $poblacio['id_sstt'], $codi_poblacio, $id_comarca, $login_centre);
                }
                $msg = lang('alertes.new_centre');
                session()->setFlashdata('afegirCentre_success', $msg);
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

    public function filtrar_centre($codi_centre)
    {
        $filtre_session = session()->get('filtres');
        $session = session();

        if ($filtre_session !== null) {
            session()->remove('filtres');
            $filtres = [];
            $session->set('filtres', $filtres);
        } else {
            $filtres = [];
            $session->set('filtres', $filtres);
        }


        $model_centre = new CentreModel();
        $centre = $model_centre->obtenirCentre($codi_centre);

        if ($centre['taller'] == false) {
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

    public function editar_centre($codi_centre)
    {
        $data['title'] = lang('centre.formulari_centre');
        $role = session()->get('user_data')['role'];
        if ($role == 'sstt' || $role == 'admin_sstt' || $role == 'desenvolupador') {

            $actor = session()->get('user_data');
            $centre_model = new CentreModel();
            $centre = $centre_model->obtenirCentre($codi_centre);

            $poblacio_model = new PoblacioModel();
            $array_poblacions = $poblacio_model->obtenirPoblacions();

            $options_poblacions = "";
            for ($i = 0; $i < sizeof($array_poblacions); $i++) {
                if (($role == 'sstt' || $role == 'admin_sstt') && $actor['id_sstt'] == $array_poblacions[$i]['id_sstt']) {
                    if ($array_poblacions[$i]['actiu'] == "1") {
                        $options_poblacions .= "<option value=\"" . $array_poblacions[$i]['id_poblacio'] . " - " . $array_poblacions[$i]['nom_poblacio'] . "\">";
                        $options_poblacions .= $array_poblacions[$i]['nom_poblacio'];
                        $options_poblacions .= "</option>";
                    }
                } else if ($role == 'desenvolupador') {
                    if ($array_poblacions[$i]['actiu'] == "1") {
                        $options_poblacions .= "<option value=\"" . $array_poblacions[$i]['id_poblacio'] . " - " . $array_poblacions[$i]['nom_poblacio'] . "\">";
                        $options_poblacions .= $array_poblacions[$i]['nom_poblacio'];
                        $options_poblacions .= "</option>";
                    }
                }
            }
            session()->setFlashdata('codi_centre', $codi_centre);
            $data['codi_centre'] = $codi_centre;
            $data['nom_centre'] = $centre['nom_centre'];
            $data['telefon'] = $centre['telefon_centre'];
            $data['adreca'] = $centre['adreca_fisica_centre'];
            $data['nom_persona_contacte'] = $centre['nom_persona_contacte_centre'];
            $data['correu_persona_contacte'] = $centre['correu_persona_contacte_centre'];
            $data['login'] = $centre['login'];
            $nom_poblacio = $poblacio_model->getPoblacio($centre['id_poblacio'])['nom_poblacio'];
            $data['poblacio'] = $centre['id_poblacio'] . " - " . $nom_poblacio;
            $data['poblacions'] = $options_poblacions;
            $data['taller'] = $centre['taller'];
            $data['actiu'] = $centre['actiu'];

            $tiquets_model = new TiquetModel();
            $tiquets_reparador = $tiquets_model->getTiquetByCodiCentreReparadorEstat($codi_centre);
            $tiquets_emissor = $tiquets_model->getTiquetByCodiCentreEmissor($codi_centre);
            $suma_tiquets = count($tiquets_emissor) + count($tiquets_reparador);

            if ($suma_tiquets == 0) {
                $data['es_editable'] = true;
            } else {
                $data['es_editable'] = false;
            }

            return view('formularis' . DIRECTORY_SEPARATOR . 'formulariEditarCentre', $data);
        } else {
            return redirect()->back();
        }
    }

    public function editar_centre_post()
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
            $activitat_centre = false;
            $es_taller = false;
            if ($centre_actiu == 'actiu') {
                $activitat_centre = true;
            }
            if ($centre_taller == 'taller') {
                $es_taller = true;
            }

            $telefon_centre = $this->request->getPost('telefon_centre');
            $adreca_centre = $this->request->getPost('adreca');
            $nom_persona_de_contacte = $this->request->getPost('nom_persona_de_contacte');
            $correu_persona_contacte = $this->request->getPost('correu_persona_contacte');
            $nom_poblacio = $this->request->getPost('nom_poblacio');
            $codi_poblacio = trim(explode('-', (string) $nom_poblacio)[0]);
            $login_centre = $this->request->getPost('login_centre');

            $model_poblacio = new PoblacioModel();
            $poblacio = $model_poblacio->getPoblacio($codi_poblacio);

            $model_centre = new CentreModel();
            $centre = $model_centre->obtenirCentre($codi_centre);
            $codi_centre_antic = session()->getFlashdata('codi_centre');

            if ($codi_centre_antic == $codi_centre) {

                $data = [
                    'nom_centre' => $nom_centre,
                    'actiu' => $activitat_centre,
                    'taller' => $es_taller,
                    'telefon_centre' => $telefon_centre,
                    'adreca_fisica_centre' => $adreca_centre,
                    'nom_persona_contacte_centre' => $nom_persona_de_contacte,
                    'correu_persona_contacte_centre' => $correu_persona_contacte,
                    'id_sstt' => $poblacio['id_sstt'],
                    'id_poblacio' => $codi_poblacio
                ];

                $model_centre->editar_centre($codi_centre, $data);
            } else {

                $data = [
                    'codi_centre' => $codi_centre,
                    'nom_centre' => $nom_centre,
                    'actiu' => $activitat_centre,
                    'taller' => $es_taller,
                    'telefon_centre' => $telefon_centre,
                    'adreca_fisica_centre' => $adreca_centre,
                    'nom_persona_contacte_centre' => $nom_persona_de_contacte,
                    'correu_persona_contacte_centre' => $correu_persona_contacte,
                    'id_sstt' => $poblacio['id_sstt'],
                    'id_poblacio' => $codi_poblacio
                ];

                $model_centre->editar_centre($codi_centre_antic, $data);
            }


            $msg = lang('alertes.update_centre') . '<b>' . $codi_centre . " - " . $nom_centre . '</b>';
            session()->setFlashdata('editarCentre', $msg);
            return redirect()->to('/centres');
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function selectorPoblacio($role, $actor)
    {
        $poblacio_model = new PoblacioModel();
        $array_poblacions = $poblacio_model->obtenirPoblacions();
        $options_poblacions = "";

        for ($i = 0; $i < sizeof($array_poblacions); $i++) {
            if (($role == 'sstt' || $role == 'admin_sstt') && $actor['id_sstt'] == $array_poblacions[$i]['id_sstt']) {
                if ($array_poblacions[$i]['actiu'] == "1") {
                    $options_poblacions .= "<option value=\"" . $array_poblacions[$i]['id_poblacio'] . " - " . $array_poblacions[$i]['nom_poblacio'] . "\">";
                    $options_poblacions .= $array_poblacions[$i]['nom_poblacio'];
                    $options_poblacions .= "</option>";
                }
            } else if ($role == 'desenvolupador') {
                if ($array_poblacions[$i]['actiu'] == "1") {
                    $options_poblacions .= "<option value=\"" . $array_poblacions[$i]['id_poblacio'] . " - " . $array_poblacions[$i]['nom_poblacio'] . "\">";
                    $options_poblacions .= $array_poblacions[$i]['nom_poblacio'];
                    $options_poblacions .= "</option>";
                }
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
        $poblacio_model = new PoblacioModel();
        $comarca_model = new ComarcaModel();

        $session = session();
        $sessio_filtres = $session->get('filtresCentres');

        $eliminar = $this->request->getPost('submit_eliminar_filtres');

        if ($eliminar !== null) {
            $session->remove('filtresCentres');
        } else {

            if ($sessio_filtres == null) {
                $filtres = [];
                $session->set('filtresCentres', $filtres);
            }

            $dades = $this->request->getPost();
            
            if ($dades['radio_actiu'] !== 'actiu_i_innactiu') {
                $array_actiu = [];
                $actiu = $dades['radio_actiu'];      
                array_push($array_actiu, $actiu);
                $session->push('filtresCentres', ['actiu' => $array_actiu]);
            }
            if ($dades['radio_taller'] !== 'taller_i_no_taller') {
                $array_taller = [];
                $taller = $dades['radio_taller'];
                array_push($array_taller, $taller);
                $session->push('filtresCentres', ['taller' => $array_taller]);
            }
            if (isset($dades['nom_poblacio_list']) && $dades['nom_poblacio_list'] !== '') {

                $array_poblacio = [];
                $nom_poblacio = $dades['nom_poblacio_list'];
                $poblacio = trim(explode('-', (string) $nom_poblacio)[0]);

                if ($poblacio != null && $poblacio_model->getPoblacio($poblacio) == null) {
                    $msg = lang("alertes.filter_error_poblacio");
                    session()->setFlashdata("escriure_malament_filtre", $msg);
                    return redirect()->back()->withInput();
                }

                array_push($array_poblacio, $poblacio);
                $session->push('filtresCentres', ['nom_poblacio' => $array_poblacio]);
            }
            if (isset($dades['nom_comarca_list']) && $dades['nom_comarca_list'] !== '') {

                $array_comarca = [];
                $nom_comarca = $dades['nom_comarca_list'];
                $comarca = trim(explode('-', (string) $nom_comarca)[0]);

                if ($comarca != null && $comarca_model->obtenirComarca($comarca) == null) {
                    $msg = lang("alertes.filter_error_comarca");
                    session()->setFlashdata("escriure_malament_filtre", $msg);
                    return redirect()->back()->withInput();
                }

                array_push($array_comarca, $comarca);
                $session->push('filtresCentres', ['nom_comarca' => $array_comarca]);
            }
        }
        return redirect()->back()->withInput();
    }

    public function eliminarFiltre()
    {
        $filtre_eliminar = $this->request->getPost();
        $filtre_session = session()->get('filtresCentres');
        $eliminar = $this->request->getPost('submit_eliminar_filtres');

        if ($eliminar !== null) {
            session()->remove('filtresCentres');
        }
        if ($filtre_eliminar['operacio'] === 'Actiu') {
            unset($filtre_session['actiu']);
            session()->set('filtresCentres', $filtre_session);
        }
        if ($filtre_eliminar['operacio'] === 'Taller') {
            unset($filtre_session['taller']);
            session()->set('filtresCentres', $filtre_session);
        }
        if ($filtre_eliminar['operacio'] == 'Poblacio') {
            unset($filtre_session['nom_poblacio']);
            session()->set('filtresCentres', $filtre_session);
        }
        if ($filtre_eliminar['operacio'] == 'Comarca') {
            unset($filtre_session['nom_comarca']);
            session()->set('filtresCentres', $filtre_session);
        }
        return redirect()->back();
    }
}
