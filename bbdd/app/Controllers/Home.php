<?php

namespace App\Controllers;

use App\Models\CentreModel;
use App\Models\LoginModel;
use App\Models\ProfessorModel;
use App\Models\TipusDispositiuModel;
use App\Models\TiquetModel;

class Home extends BaseController
{
    protected $helpers = ['form', 'file', 'filesystem'];

    public function login(): string
    {
        $locale = $this->request->getLocale();
        $data['title'] = "login";
        $data['locale'] = $locale;
        return view('logins\loginGeneral', $data);
    }

    public function login_post()
    {
        $locale = $this->request->getLocale();
        $data['locale'] = $locale;
        $data['title'] = "login";

        // Determinem les regles de validació
        $validationRules = [
            'sUser' => [
                'rules'  => 'required|max_length[32]',
                'errors' => [
                    'required' => lang('general_lang.nom_usuari_required'),
                    'max_length' => lang('general_lang.nom_usuari_required_max_length'),
                ],
            ],
            'sPssw' => [
                'rules'  => 'required|min_length[6]',
                'errors' => [
                    'required' => lang('general_lang.contrasenya_required'),
                    'min_length' => lang('general_lang.contrasenya_min_length'),
                ],
            ],
        ];

        if ($this->validate($validationRules)) { // En cas que es compleixin les regles de validació

            // Obtenim del formualri el nom d'usuari
            $nom_login = $this->request->getPost('sUser');
            
            // Obtenim l'usuari mitjançant aquest
            $login_model = new LoginModel;
            $login_obtingut = $login_model->obtenirLogin($nom_login);

            if ($login_obtingut != null) { // En cas que existeixi

                // Obtenim la contrasenya del formulari
                $contrasenya = $this->request->getPost('sPssw');
                
                if (password_verify($contrasenya[0], $login_obtingut['contrasenya'])) { // Verifiquem que la contrasenya coincideixi amb la de la base de dades

                    return redirect()->to(base_url($locale . '/formulariTiquet'));
                }

            }
        }

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

        $csv = $this->request->getFiles();


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
            $codi_centre = session()->get('codi_centre');
            
            
            //$imagefile = $this->request->getFiles();    és el csv
           
            
            if ($csv['csv_tiquet'] != "") {

                $codi_centre = session()->get('codi_centre');
                $codi_professor = "1";

                // Carreguem el fitxer a writable/uploads
                if ($csv) {
                    if ($this->request->getFiles()) {
                        $file = $csv['csv_tiquet'];
                        if ($file->isValid() && !$file->hasMoved()) {

                            $newName = $file->getClientName();

                            $ruta = WRITEPATH . "uploads" . DIRECTORY_SEPARATOR . $codi_centre;
                            if (!is_dir($ruta)) {
                                mkdir($ruta, 0755);
                            }

                            $ruta = WRITEPATH . "uploads" . DIRECTORY_SEPARATOR . $codi_centre . DIRECTORY_SEPARATOR . $codi_professor;
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
                                    $model->addTiquet($csv_data[1], $csv_data[2], $csv_data[3], $csv_data[4], $csv_data[5], $csv_data[6], $csv_data[7], $csv_data[8], $csv_data[9], $csv_data[10]);
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

                for ($i = 1; $i <= $num_tiquets; $i++) {
                    $codi_equip = $this->request->getPost('equipment_code_' . $i);
                    $tipus = $this->request->getPost('type_' . $i);
                    $problem = $this->request->getPost('problem_' . $i);

                    $tiquet_model->addTiquet($codi_equip, $problem, $nom_persona_contacte_centre, $correu_persona_contacte_centre, $data_alta, null, $tipus, 1, $codi_centre, null);
                }
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
