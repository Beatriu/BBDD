<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CentreModel;
use App\Models\CursModel;
use App\Models\EstatModel;
use App\Models\IntervencioModel;
use App\Models\TipusIntervencioModel;
use App\Models\TiquetModel;

class IntervencionsController extends BaseController
{

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


        if ($role == "centre_emissor" || $role == "centre_reparador" || $role == "sstt") {
            return redirect()->to(base_url('/registreTiquet'));
        } else {

            $tiquet = $tiquet_model->getTiquetById($id_tiquet);

            if ($role == "professor" || $role == "alumne") {
                $estats_professor = $estat_model->getProfessorEstats();

                for ($i = 0; $i < sizeof($estats_professor); $i++) {
                    $estats_consulta[$i] = $estats_professor[$i]['id_estat'];
                }

                if (!in_array($tiquet['id_estat'], $estats_consulta) || $actor['codi_centre'] != $tiquet['codi_centre_reparador']) {
                    return redirect()->to(base_url('/registreTiquet'));
                }

            } else if ($role == "admin_sstt") {
                $id_sstt_tiquet = $centre_model->obtenirCentre($tiquet['codi_centre_emissor'])['id_sstt'];

                if ($id_sstt_tiquet != $actor['id_sstt']) {
                    return redirect()->to(base_url('/registreTiquet'));
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
                return redirect()->to(base_url('/registreTiquet'));
            } else {

                $id_tiquet = session()->getFlashdata("id_tiquet_afegir_intervencio");
                $tiquet = $tiquet_model->getTiquetById($id_tiquet);

                if ($role == "professor" || $role == "alumne") {
                    $estats_professor = $estat_model->getProfessorEstats();
    
                    for ($i = 0; $i < sizeof($estats_professor); $i++) {
                        $estats_consulta[$i] = $estats_professor[$i]['id_estat'];
                    }
    
                    if (!in_array($tiquet['id_estat'], $estats_consulta) || $actor['codi_centre'] != $tiquet['codi_centre_reparador']) {
                        return redirect()->to(base_url('/registreTiquet'));
                    }
    
                } else if ($role == "admin_sstt") {
                    $id_sstt_tiquet = $centre_model->obtenirCentre($tiquet['codi_centre_emissor'])['id_sstt'];
    
                    if ($id_sstt_tiquet != $actor['id_sstt']) {
                        return redirect()->to(base_url('/registreTiquet'));
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
            
                return redirect()->to('vistaTiquet/' . $id_tiquet);
            }

        }
    }


    public function editarIntervencio() {
        $role = session()->get('user_data')['role'];

        if ($role == "centre_emissor" || $role == "centre_reparador") {
            return redirect()->to(base_url('/registreTiquet'));
        } else {
            $tipus_intervencio_model = new TipusIntervencioModel();
            $curs_model = new CursModel();
    
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
    
            $data['title'] = lang('intervencio.formulari_editar_intervencio');

            return view('formularis' . DIRECTORY_SEPARATOR .'formulariEditarIntervencio', $data);
        }

    }

    public function editarIntervencio_post() {
        
    }
}
