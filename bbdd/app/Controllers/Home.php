<?php

namespace App\Controllers;

use App\Models\CentreModel;
use App\Models\ProfessorModel;
use App\Models\TipusDispositiuModel;
use App\Models\TiquetModel;

class Home extends BaseController
{
    public function login(): string
    {
        $locale = $this->request->getLocale();
        $data['title'] = "login";
        $data['locale'] = $locale;
        return view('logins\loginGeneral', $data);
    }
    public function loginSelect(): string
    {
        $data['title'] = "login";
        return view('logins\loginSelect', $data);
    }

    /**
     * Funció que ens dirigeix cap al formulari per crear un tiquet
     *
     * @author Blai Burgués Vicente
     */
    public function createTiquet(): string 
    {
        $locale = $this->request->getLocale();
        $data['locale'] = $locale;
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



        // TREURE AIXÒ
        session()->set(['codi_centre' => '25008443']);
        $codi_centre = session()->get('codi_centre');

        $centre = new CentreModel;
        $data['nom_persona_contacte_centre'] = $centre->obtenirNomResponsable($codi_centre);
        $data['correu_persona_contacte_centre'] = $centre->obtenirCorreuResponsable($codi_centre);

        $data['title'] = lang('general_lang.formulari_tiquet');
        return view('formularis\formulariTiquet', $data);
    }

    public function createTiquet_post()
    {
        $locale = $this->request->getLocale();
        $data['locale'] = $locale;
        $data['title'] = "login";

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

        if ($this->validate($validationRules)) { 
            $tiquet_model = new TiquetModel;

            $nom_persona_contacte_centre = $this->request->getPost('sNomContacteCentre');
            $correu_persona_contacte_centre = $this->request->getPost('sCorreuContacteCentre');

            $data_alta = date("Y-m-d H:i:s");

            $num_tiquets = $this->request->getPost('num_tiquets');
            $codi_centre = session()->get('codi_centre');
            
            // Validem camps            
            for ($i = 1; $i <= intval($num_tiquets); $i++) {
                $codi_equip = $this->request->getPost('equipment_code_' . $i);
                $tipus = $this->request->getPost('type_' . $i);
                $problem = $this->request->getPost('problem_' . $i);

                if ($codi_equip == "" || $tipus == "" || $problem == "") {
                    return redirect()->back()->withInput();
                }
            }

            for ($i = 1; $i <= $num_tiquets; $i++) {
                $codi_equip = $this->request->getPost('equipment_code_' . $i);
                $tipus = $this->request->getPost('type_' . $i);
                $problem = $this->request->getPost('problem_' . $i);

                $tiquet_model->addTiquet($codi_equip, $problem, $nom_persona_contacte_centre, $correu_persona_contacte_centre, $data_alta, null, $tipus, 1, $codi_centre, null);
            }

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



        // TREURE AIXÒ
        session()->set(['codi_centre' => '25008443']);
        $codi_centre = session()->get('codi_centre');

        $centre = new CentreModel;
        $data['nom_persona_contacte_centre'] = $centre->obtenirNomResponsable($codi_centre);
        $data['correu_persona_contacte_centre'] = $centre->obtenirCorreuResponsable($codi_centre);

        return view('formularis\formulariTiquet', $data);
    }

}
