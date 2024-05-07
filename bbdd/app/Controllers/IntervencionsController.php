<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CursModel;
use App\Models\IntervencioModel;
use App\Models\TipusIntervencioModel;

class IntervencionsController extends BaseController
{

    public function createIntervencio()
    {
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
    
            $data['title'] = lang('intervencio.formulari_intervencio');
            return view('formularis' . DIRECTORY_SEPARATOR .'formulariAfegirIntervencio', $data);
        }

    }

    public function createIntervencio_post()
    {
        $intervencio_model = new IntervencioModel();

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

            $role = session()->get('user_data')['role'];

            if ($role == "centre_emissor" || $role == "centre_reparador") {
                return redirect()->to(base_url('/registreTiquet'));
            } else {
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
    
                $intervencio_model->addIntervencioAlumne($uuid, $descripcio_intervencio, "c8a96b5f-e9ce-46a9-bcde-551d95077574", $data_intervencio, $id_tipus_intervencio, $id_curs, $correu_alumne, $id_xtec);
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
