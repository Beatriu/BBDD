<?php

namespace App\Controllers;

use App\Models\CentreModel;
use App\Models\TipusDispositiuModel;
use App\Models\TiquetModel;

class Home extends BaseController
{
    protected $helpers = ['form', 'file', 'filesystem'];

    public function index() {
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
            $centre_emissor = session()->get('user_data')['codi_centre'];
            $role = session()->get('user_data')['role'];
            
            //$imagefile = $this->request->getFiles();    és el csv
           
            
            if ($csv['csv_tiquet'] != "") {

                $codi_professor = "1";

                // Carreguem el fitxer a writable/uploads
                if ($csv) {
                    if ($this->request->getFiles()) {
                        $file = $csv['csv_tiquet'];
                        if ($file->isValid() && !$file->hasMoved()) {

                            $newName = $file->getClientName();

                            $ruta = WRITEPATH . "uploads" . DIRECTORY_SEPARATOR . $centre_emissor;
                            if (!is_dir($ruta)) {
                                mkdir($ruta, 0755);
                            }

                            $ruta = WRITEPATH . "uploads" . DIRECTORY_SEPARATOR . $centre_emissor . DIRECTORY_SEPARATOR . $codi_professor;
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
                                    $model->addTiquet($csv_data[1], $csv_data[2], $csv_data[3], $csv_data[4], $data_alta, null, $csv_data[7], $csv_data[8], $csv_data[9], $csv_data[10]);
                                    
                                    if ($role == "professor" || $role == "centre_emissor") {
                                        $model->addTiquet($csv_data[1], $csv_data[2], $csv_data[3], $csv_data[4], $data_alta, null, $csv_data[7], $csv_data[8], $centre_emissor, null);
                                    } else {
                                        $model->addTiquet($csv_data[1], $csv_data[2], $csv_data[3], $csv_data[4], $data_alta, null, $csv_data[7], $csv_data[8], $csv_data[9], $csv_data[10]);
                                    }
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

                if ($centre_emissor == "no_codi"){
                    if ($role != "professor" && $role != "centre_emissor") {                    
                        $centre_emissor = $this->request->getPost('centre_emissor');
                        $centre_reparador = $this->request->getPost('centre_reparador');
                        $centre_emissor = trim(explode('-', (string) $centre_emissor)[0]);
                        $centre_reparador = trim(explode('-', (string) $centre_reparador)[0]);
                    } 
    
                    if ($centre_emissor == "") {
                        $centre_emissor = null;
                    }
    
                    if ($centre_reparador == "") {
                        $centre_reparador = null;
                    }
                }

                for ($i = 1; $i <= $num_tiquets; $i++) {
                    $codi_equip = $this->request->getPost('equipment_code_' . $i);
                    $tipus = $this->request->getPost('type_' . $i);
                    $problem = $this->request->getPost('problem_' . $i);

                    if ($role == "professor" || $role == "centre_emissor") {
                        $tiquet_model->addTiquet($codi_equip, $problem, $nom_persona_contacte_centre, $correu_persona_contacte_centre, $data_alta, null, $tipus, 1, $centre_emissor, null);
                    } else {
                        $tiquet_model->addTiquet($codi_equip, $problem, $nom_persona_contacte_centre, $correu_persona_contacte_centre, $data_alta, null, $tipus, 1, $centre_emissor, $centre_reparador);
                    }
                    
                }
            }


        }
        
        return redirect()->to(base_url('/registreTiquet'));
    }

    /**
     * Funció que ens dirigeix cap al formulari per crear un tiquet
     *
     * @author Blai Burgués Vicente
     */
    public function createTiquet() 
    {
        $role = session()->get('user_data')['role'];

        if ($role == "alumne") {
            return redirect()->to(base_url('/registreTiquet'));
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

        $centre_model = new CentreModel();
        $array_centres = $centre_model->obtenirCentres();
        $options_tipus_dispositius_emissors = "";
        $options_tipus_dispositius_reparadors = "";
        for ($i = 0; $i < sizeof($array_centres); $i++) {
            $options_tipus_dispositius_emissors .= "<option value='" . $array_centres[$i]['codi_centre'] . " - " . $array_centres[$i]['nom_centre'] . "'>";
            $options_tipus_dispositius_emissors .= $array_centres[$i]['nom_centre'];
            $options_tipus_dispositius_emissors .= "</option>";

            if ($array_centres[$i]['taller'] == 1) {
                $options_tipus_dispositius_reparadors .= "<option value='" . $array_centres[$i]['codi_centre'] . " - " . $array_centres[$i]['nom_centre'] . "'>";
                $options_tipus_dispositius_reparadors .= $array_centres[$i]['nom_centre'];
                $options_tipus_dispositius_reparadors .= "</option>";
            }
        }
        $data['centres_emissors'] = $options_tipus_dispositius_emissors;
        $data['centres_reparadors'] = $options_tipus_dispositius_reparadors;


        // TREURE AIXÒ
        $codi_centre = session()->get('user_data')['codi_centre'];

        if ($codi_centre != "no_codi") {
            $centre = new CentreModel;
            $data['nom_persona_contacte_centre'] = $centre->obtenirNomResponsable($codi_centre);
            $data['correu_persona_contacte_centre'] = $centre->obtenirCorreuResponsable($codi_centre);
        } else {
            $data['nom_persona_contacte_centre'] = null;
            $data['correu_persona_contacte_centre'] = null;
        }

        $data['title'] = lang('general_lang.formulari_tiquet');
        return view('formularis' . DIRECTORY_SEPARATOR .'formulariTiquet', $data);
    }

        /**
     * Funció que ens dirigeix cap al formulari per crear un tiquet
     *
     * @author Blai Burgués Vicente
     */
    public function descarregar($arxiu) 
    {
        $role = session()->get('user_data')['role'];

        if ($arxiu == "exemple_afegir_tiquet") {

            if ($role == "professor" || $role == "centre_emissor" || $role == "centre_reparador") {
                
                $file = new \CodeIgniter\Files\File("exemple_afegir_tiquet_professorat.csv"); // Definim el nom de l'arxiu amb ruta
    
                // En cas que no es tracti d'un fitxer llencem que no s'ha trobat
                if (!$file->isFile()){
                    throw new \CodeIgniter\Exceptions\PageNotFoundException("exemple_afegir_tiquet_professorat.csv no found");
                }
    
                // Llegim l'arxiu i donem resposta
                $filedata = new \SplFileObject($file->getPathname(), "r");
                $data1 = $filedata->fread($filedata->getSize());
                return $this->response->setContentType($file->getMimeType())->setBody($data1);
                
            } else if ($role == "sstt" || $role == "admin_sstt" || $role == "desenvolupador") {

            }

        }
    }
}
