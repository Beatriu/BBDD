<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CentreModel;
use App\Models\CursModel;
use App\Models\EstatModel;
use App\Models\IntervencioModel;
use App\Models\TipusIntervencioModel;
use App\Models\TiquetModel;
use Faker\Provider\Base;

class IntervencionsController extends BaseController
{
    protected $helpers = ['form'];

    public function createIntervencio($id_tiquet)
    {
        $tiquet_model = new TiquetModel();
        $estat_model = new EstatModel();
        $centre_model = new CentreModel();
        $tipus_intervencio_model = new TipusIntervencioModel();
        $curs_model = new CursModel();

        $actor = session()->get('user_data');
        $role = $actor['role'];
        $data['id_tiquet'] = $id_tiquet;


        $tiquet = $tiquet_model->getTiquetById($id_tiquet);

        if ($tiquet != null) {

            if ($role == "centre_emissor" || $role == "centre_reparador" || $role == "sstt") {
                return redirect()->to(base_url('/tiquets'));
            } else {
    
                if ($role == "professor" || $role == "alumne") {
                    $estats_professor = $estat_model->getProfessorEstats();
    
                    for ($i = 0; $i < sizeof($estats_professor); $i++) {
                        $estats_consulta[$i] = $estats_professor[$i]['id_estat'];
                    }
    
                    if (!in_array($tiquet['id_estat'], $estats_consulta) || $actor['codi_centre'] != $tiquet['codi_centre_reparador']) {
                        return redirect()->to(base_url('/tiquets'));
                    }
    
                } else if ($role == "admin_sstt") {
                    $id_sstt_tiquet = $centre_model->obtenirCentre($tiquet['codi_centre_emissor'])['id_sstt'];
    
                    if ($id_sstt_tiquet != $actor['id_sstt']) {
                        return redirect()->to(base_url('/tiquets'));
                    }
    
                }
    
                session()->setFlashdata("id_tiquet_afegir_intervencio", $id_tiquet);
        
                $array_tipus_intervencio = $tipus_intervencio_model->obtenirTipusIntervencio();
                $options_tipus_intervencio = "";
                for ($i = 0; $i < sizeof($array_tipus_intervencio); $i++) {
                    $options_tipus_intervencio .= "<option value='" . $array_tipus_intervencio[$i]['id_tipus_intervencio'] . " - " . $array_tipus_intervencio[$i]['nom_tipus_intervencio'] . "'>";
                    $options_tipus_intervencio .= $array_tipus_intervencio[$i]['nom_tipus_intervencio'];
                    $options_tipus_intervencio .= "</option>";
                }
        
        
                $array_curs = $curs_model->obtenirCursos();
                $options_curs = "";
                for ($i = 0; $i < sizeof($array_curs); $i++) {
                    $options_curs .= "<option value=\"" . $array_curs[$i]['id_curs'] . " - " . $array_curs[$i]['curs'] . " " . $array_curs[$i]['cicle'] . " " . $array_curs[$i]['titol'] . "\">";
                    $options_curs .= $array_curs[$i]['curs'] . " " . $array_curs[$i]['cicle'] . " " . $array_curs[$i]['titol'];
                    $options_curs .= "</option>";
                }
        
        
                $data['tipus_intervencio'] = $options_tipus_intervencio;
                $data['cursos'] = $options_curs;
        
                $data['title'] = lang('intervencio.formulari_intervencio');
                return view('formularis' . DIRECTORY_SEPARATOR .'formulariAfegirIntervencio', $data);
            }

        } else {
            return redirect()->to(base_url('/tiquets'));
        }

    }

    public function createIntervencio_post()
    {
        $intervencio_model = new IntervencioModel();
        $tiquet_model = new TiquetModel();
        $estat_model = new EstatModel();
        $centre_model = new CentreModel();

        $validationRules = [
            'tipus_intervencio' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => lang('intervencio.tipus_intervencio_required'),
                ],
            ],            
            'curs' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => lang('intervencio.curs_required'),
                ],
            ],
            'descripcio_intervencio' => [
                'rules' => 'max_length[512]',
                'errors' => [
                    'max_length' => lang('intervencio.descripcio_intervencio_max'),
                ],
            ],
        ];

        if ($this->validate($validationRules)) {

            $actor = session()->get('user_data');
            $role = $actor['role'];

            if ($role == "centre_emissor" || $role == "centre_reparador" || $role == "sstt") {
                return redirect()->to(base_url('/tiquets'));
            } else {

                $id_tiquet = session()->getFlashdata("id_tiquet_afegir_intervencio");
                $tiquet = $tiquet_model->getTiquetById($id_tiquet);

                if ($role == "professor" || $role == "alumne") {
                    $estats_professor = $estat_model->getProfessorEstats();
    
                    for ($i = 0; $i < sizeof($estats_professor); $i++) {
                        $estats_consulta[$i] = $estats_professor[$i]['id_estat'];
                    }
    
                    if (!in_array($tiquet['id_estat'], $estats_consulta) || $actor['codi_centre'] != $tiquet['codi_centre_reparador']) {
                        return redirect()->to(base_url('/tiquets'));
                    }
    
                } else if ($role == "admin_sstt") {
                    $id_sstt_tiquet = $centre_model->obtenirCentre($tiquet['codi_centre_emissor'])['id_sstt'];
    
                    if ($id_sstt_tiquet != $actor['id_sstt']) {
                        return redirect()->to(base_url('/tiquets'));
                    }
    
                }

                $uuid_library = new \App\Libraries\UUID;
                $uuid = $uuid_library->v4();
                $tipus_intervencio = $this->request->getPost('tipus_intervencio');
                $curs = $this->request->getPost('curs');
                $descripcio_intervencio = $this->request->getPost('descripcio_intervencio');
                $data_intervencio = date("Y-m-d H:i:s");
    
                $id_xtec = null;
                $correu_alumne = null;
    
                $id_tipus_intervencio = trim(explode('-', (string) $tipus_intervencio)[0]);
                $id_curs = trim(explode('-', (string) $curs)[0]);
    
                if ($role == "professor") {
                    $id_xtec = explode("@", session()->get('user_data')['mail'])[0];
                } else if ($role == "alumne") {
                    $correu_alumne = session()->get('user_data')['mail'];
                }
    
                $intervencio_model->addIntervencio($uuid, $descripcio_intervencio, $id_tiquet, $data_intervencio, $id_tipus_intervencio, $id_curs, $correu_alumne, $id_xtec);
            
                return redirect()->to('tiquets/' . $id_tiquet);
            }

        } else {
            return redirect()->back()->withInput();
        }
    }


    public function editarIntervencio($id_tiquet, $id_intervencio) {

        $tipus_intervencio_model = new TipusIntervencioModel();
        $curs_model = new CursModel();
        $tiquet_model = new TiquetModel();
        $intervencio_model = new IntervencioModel();
        $estat_model = new EstatModel();
        $centre_model = new CentreModel();

        $actor = session()->get('user_data');
        $role = $actor['role'];

        $tiquet = $tiquet_model->getTiquetById($id_tiquet);

        if ($tiquet != null) {

            $intervencio = $intervencio_model->obtenirIntervencioPerId($id_intervencio);

            if ($intervencio != null) {

                if ($role == "centre_emissor" || $role == "centre_reparador") {
                    return redirect()->to(base_url('/tiquets'));
                } else {
        
                    if ($role == "professor" || $role == "alumne") {

                        $estats_professor = $estat_model->getProfessorEstats();
        
                        for ($i = 0; $i < sizeof($estats_professor); $i++) {
                            $estats_consulta[$i] = $estats_professor[$i]['id_estat'];
                        }
    
                        if (!in_array($tiquet['id_estat'], $estats_consulta) || $actor['codi_centre'] != $tiquet['codi_centre_reparador']) {
                            return redirect()->to(base_url('/tiquets'));
                        }

                        if ($role == "alumne" && ($intervencio['id_xtec'] != null || $intervencio['id_estat'] == $estat_model->obtenirIdPerEstat("Reparat i pendent de recollir"))) {
                            return redirect()->to(base_url('/tiquets/' . $id_tiquet));
                        }
        
                    } else if ($role == "admin_sstt") {
                        $id_sstt_tiquet = $centre_model->obtenirCentre($tiquet['codi_centre_emissor'])['id_sstt'];
        
                        if ($id_sstt_tiquet != $actor['id_sstt']) {
                            return redirect()->to(base_url('/tiquets'));
                        }
        
                    }
        
                    $data['id_tiquet'] = $id_tiquet;
                    $data['intervencio'] = $intervencio;
                    session()->setFlashdata('id_tiquet_intervencio_editar', $id_tiquet);
                    session()->setFlashdata('id_intervencio_editar', $id_intervencio);
        
                    $array_tipus_intervencio = $tipus_intervencio_model->obtenirTipusIntervencio();
                    $options_tipus_intervencio = "";
                    $data['selected_intervencio'] = "";
                    for ($i = 0; $i < sizeof($array_tipus_intervencio); $i++) {
                        $options_tipus_intervencio .= "<option value='" . $array_tipus_intervencio[$i]['id_tipus_intervencio'] . " - " . $array_tipus_intervencio[$i]['nom_tipus_intervencio'] . "'>";
                        $options_tipus_intervencio .= $array_tipus_intervencio[$i]['nom_tipus_intervencio'];
                        $options_tipus_intervencio .= "</option>";
                        if ($array_tipus_intervencio[$i]['id_tipus_intervencio'] == $intervencio['id_tipus_intervencio']) {
                            $data['selected_intervencio'] = $array_tipus_intervencio[$i]['id_tipus_intervencio'] . " - " . $array_tipus_intervencio[$i]['nom_tipus_intervencio'];
                        }
                    }
            
                    $array_curs = $curs_model->obtenirCursos();
                    $options_curs = "";
                    $data['selected_curs'] = "";
                    for ($i = 0; $i < sizeof($array_curs); $i++) {
                        $options_curs .= "<option value=\"" . $array_curs[$i]['id_curs'] . " - " . $array_curs[$i]['curs'] . " " . $array_curs[$i]['cicle'] . " " . $array_curs[$i]['titol'] . "\">";
                        $options_curs .= $array_curs[$i]['curs'] . " " . $array_curs[$i]['cicle'] . " " . $array_curs[$i]['titol'];
                        $options_curs .= "</option>";
                        if ($array_curs[$i]['id_curs'] == $intervencio['id_curs']) {
                            $data['selected_curs'] = $array_curs[$i]['id_curs'] . " - " . $array_curs[$i]['curs'] . " " . $array_curs[$i]['cicle'] . " " . $array_curs[$i]['titol'];
                        }
                    }
            
            
                    $data['tipus_intervencio'] = $options_tipus_intervencio;
                    $data['cursos'] = $options_curs;
            
                    $data['title'] = lang('intervencio.formulari_editar_intervencio');
        
                    return view('formularis' . DIRECTORY_SEPARATOR .'formulariEditarIntervencio', $data);
                }

            } else {
                return redirect()->to(base_url('/tiquets'));
            }

        } else {
            return redirect()->to(base_url('/tiquets'));
        }



    }

    public function editarIntervencio_post() {

        $tiquet_model = new TiquetModel();
        $intervencio_model = new IntervencioModel(); 
        $estat_model = new EstatModel();
        $centre_model = new CentreModel();      

        $actor = session()->get('user_data');
        $role = $actor['role'];



        $validationRules = [
            'tipus_intervencio' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => lang('intervencio.tipus_intervencio_required'),
                ],
            ],            
            'curs' => [
                'rules'  => 'required',
                'errors' => [
                    'required' => lang('intervencio.curs_required'),
                ],
            ],
            'descripcio_intervencio' => [
                'rules' => 'max_length[512]',
                'errors' => [
                    'max_length' => lang('intervencio.descripcio_intervencio_max'),
                ],
            ],
        ];

        if ($this->validate($validationRules)) {



            if ($role == "centre_emissor" || $role == "centre_reparador" || $role == "sstt") {
                return redirect()->to(base_url('/tiquets'));
            } else {

                $id_tiquet_intervencio_editar = session()->getFlashdata('id_tiquet_intervencio_editar');
                $tiquet_intervencio_editar = $tiquet_model->getTiquetById($id_tiquet_intervencio_editar);
    
                if ($tiquet_intervencio_editar != null) {
    
                    $id_intervencio_editar = session()->getFlashdata('id_intervencio_editar');
                    $intervencio_editar = $intervencio_model->obtenirIntervencioPerId($id_intervencio_editar);
                 
                    if ($intervencio_editar == null) {
                        return redirect()->to(base_url('/tiquets/' . $id_tiquet_intervencio_editar));
                    } 
    
                } else {
                    return redirect()->to(base_url('/tiquets'));
                }

                

                if ($role == "professor" || $role == "alumne") {

                    $estats_professor = $estat_model->getProfessorEstats();
    
                    for ($i = 0; $i < sizeof($estats_professor); $i++) {
                        $estats_consulta[$i] = $estats_professor[$i]['id_estat'];
                    }

                    if (!in_array($tiquet_intervencio_editar['id_estat'], $estats_consulta) || $actor['codi_centre'] != $tiquet_intervencio_editar['codi_centre_reparador']) {
                        return redirect()->to(base_url('/tiquets'));
                    }

                    if ($role == "alumne" && ($intervencio_editar['id_xtec'] != null || $intervencio_editar['id_estat'] == $estat_model->obtenirIdPerEstat("Reparat i pendent de recollir"))) {
                        return redirect()->to(base_url('/tiquets/' . $id_tiquet_intervencio_editar));
                    }
    
                } else if ($role == "admin_sstt") {
                    $id_sstt_tiquet = $centre_model->obtenirCentre($tiquet_intervencio_editar['codi_centre_emissor'])['id_sstt'];
    
                    if ($id_sstt_tiquet != $actor['id_sstt']) {
                        return redirect()->to(base_url('/tiquets'));
                    }
    
                }


                $tipus_intervencio = $this->request->getPost('tipus_intervencio');
                $curs = $this->request->getPost('curs');
                $descripcio_intervencio = $this->request->getPost('descripcio_intervencio');
                $id_tipus_intervencio = trim(explode('-', (string) $tipus_intervencio)[0]);
                $id_curs = trim(explode('-', (string) $curs)[0]);

                $id_xtec = $intervencio_editar['id_xtec'];
                $correu_alumne = $intervencio_editar['correu_alumne'];
    
                if ($role == "professor") {
                    $id_xtec = explode("@", session()->get('user_data')['mail'])[0];
                } else if ($role == "alumne") {
                    $correu_alumne = session()->get('user_data')['mail'];
                }
    
                $intervencio_model->editarIntervencio($id_intervencio_editar, $id_tipus_intervencio, $id_curs, $descripcio_intervencio, $correu_alumne, $id_xtec);
            
                return redirect()->to('tiquets/' . $id_tiquet_intervencio_editar);

            }


        } else {
            return redirect()->back()->withInput();
        }
    }

    public function eliminarIntervencio_vista($id_tiquet, $id_intervencio)
    {
        $tiquet_model = new TiquetModel();
        $intervencio_model = new IntervencioModel();
        $estat_model = new EstatModel();
        $centre_model = new CentreModel();

        $actor = session()->get('user_data');
        $role = $actor['role'];

        $tiquet = $tiquet_model->getTiquetById($id_tiquet);

        if ($tiquet != null) {

            $intervencio = $intervencio_model->obtenirIntervencioPerId($id_intervencio);

            if ($intervencio != null) {

                if ($role == "centre_emissor" || $role == "centre_reparador" || $role == "sstt") {
                    return redirect()->to(base_url('/tiquets'));
                } else {
    
                    if ($role == "professor" || $role == "alumne") {
                        $estats_professor = $estat_model->getProfessorEstats();
        
                        for ($i = 0; $i < sizeof($estats_professor); $i++) {
                            $estats_consulta[$i] = $estats_professor[$i]['id_estat'];
                        }

                        if (!in_array($tiquet['id_estat'], $estats_consulta) || $actor['codi_centre'] != $tiquet['codi_centre_reparador']) {
                            return redirect()->to(base_url('/tiquets'));
                        }

                        if ($role == "alumne" && ($intervencio['id_xtec'] != null || $intervencio['id_estat'] == $estat_model->obtenirIdPerEstat("Reparat i pendent de recollir"))) {
                            return redirect()->to(base_url('/tiquets/' . $id_tiquet));
                        }
        
                    } else if ($role == "admin_sstt") {
                        $id_sstt_tiquet = $centre_model->obtenirCentre($tiquet['codi_centre_emissor'])['id_sstt'];
        
                        if ($id_sstt_tiquet != $actor['id_sstt']) {
                            return redirect()->to(base_url('/tiquets'));
                        }
        
                    }
                    
                    $data['id_intervencio'] = $id_tiquet;
                    session()->setFlashdata("id_intervencio",$intervencio);
                    return redirect()->to(base_url('/tiquets/' . $id_tiquet));

                }

            } else {
                return redirect()->to(base_url('/tiquets'));
            }

        } else {
            return redirect()->to(base_url('/tiquets'));
        }

    }

    public function eliminarIntervencio($id_tiquet, $id_intervencio)
    {

        $tiquet_model = new TiquetModel();
        $intervencio_model = new IntervencioModel();
        $estat_model = new EstatModel();
        $centre_model = new CentreModel();

        $actor = session()->get('user_data');
        $role = $actor['role'];

        $tiquet = $tiquet_model->getTiquetById($id_tiquet);
        $intervencio = $intervencio_model->obtenirIntervencioPerId($id_intervencio);

        if ($tiquet != null) {
            
            if ($intervencio != null) {
                
                if ($role == "centre_emissor" || $role == "centre_reparador" || $role == "sstt") {
                    return redirect()->to(base_url('/tiquets'));
                } else {
    
                    if ($role == "professor" || $role == "alumne") {

                        $estats_professor = $estat_model->getProfessorEstats();
        
                        for ($i = 0; $i < sizeof($estats_professor); $i++) {
                            $estats_consulta[$i] = $estats_professor[$i]['id_estat'];
                        }
    
                        if (!in_array($tiquet['id_estat'], $estats_consulta) || $actor['codi_centre'] != $tiquet['codi_centre_reparador']) {
                            return redirect()->to(base_url('/tiquets'));
                        }

                        if ($role == "alumne" && ($intervencio['id_xtec'] != null || $intervencio['id_estat'] == $estat_model->obtenirIdPerEstat("Reparat i pendent de recollir"))) {
                            return redirect()->to(base_url('/tiquets/' . $id_tiquet));
                        }
        
                    } else if ($role == "admin_sstt") {
                        $id_sstt_tiquet = $centre_model->obtenirCentre($tiquet['codi_centre_emissor'])['id_sstt'];
        
                        if ($id_sstt_tiquet != $actor['id_sstt']) {
                            return redirect()->to(base_url('/tiquets'));
                        }
        
                    }

                    $intervencio_model->deleteIntervencio($id_intervencio);
                    return redirect()->to(base_url('/tiquets/' . $id_tiquet));

                }
    
            } else {
                return redirect()->to(base_url('/tiquets'));
            }

        } else {
            return redirect()->to(base_url('/tiquets'));
        }

    }
}
