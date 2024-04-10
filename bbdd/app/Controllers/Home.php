<?php

namespace App\Controllers;

use App\Models\CentreModel;
use App\Models\TipusDispositiuModel;
use App\Models\TiquetModel;

class Home extends BaseController
{
    protected $helpers = ['form', 'file', 'filesystem'];

    public function index($code) {
        return redirect()->to(base_url('/login'));
    }

    public function canviLanguage() {
        if (session()->language == 'ca') {
            $this->request->setlocale('es');
            session()->language = 'es';
        } else if (session()->language == 'es') {
            $this->request->setlocale('ca');
            session()->language = 'ca';
        }

        return redirect()->back()->withInput();
    }

    public function createTiquet_post()
    {
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
