<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CentreModel;
use App\Models\PoblacioModel;
use App\Models\SSTTModel;
use App\Models\TipusDispositiuModel;
use App\Models\TipusInventariModel;
use App\Models\TiquetModel;
use CodeIgniter\HTTP\ResponseInterface;

class DadesController extends BaseController
{
    public function registreDades()
    {
        $tipus_dispositiu_model = new TipusDispositiuModel();

        $actor = session()->get('user_data');
        $role = $actor['role'];
        $data['role'] = $role;
        $data['title'] = "Dades";

        if ($role != "desenvolupador" && $role != "admin_sstt") {
            return redirect()->to(base_url('/tiquets'));
        }

        $array_tipus_dispositiu = $tipus_dispositiu_model->getTipusDispositius();
        $data['tipus_dispositiu'] = "";
        for ($i = 0; $i < sizeof($array_tipus_dispositiu); $i++) {
            if ($array_tipus_dispositiu[$i]['actiu'] == "1") {
                $data['tipus_dispositiu'] .= "<option value = \"". $array_tipus_dispositiu[$i] ['id_tipus_dispositiu']."\">". $array_tipus_dispositiu[$i] ['nom_tipus_dispositiu']."</option>";
            }
        }

        if ($role == "admin_sstt") {

            $data['tipus_actor_nombre_finalitzats'] = "\"<option value='centre_reparador' >". lang('dades.centre_reparador') ."</option><option  value='sstt' >". lang('dades.sstt') ."</option>\"";
            
            $data['tipus_actor_nombre_emesos'] = "\"<option value='centre_emissor' >". lang('dades.centre_emissor') ."</option><option value='poblacio' >". lang('dades.poblacio') ."</option><option value='comarca' >". lang('dades.comarca') ."</option><option  value='sstt' >". lang('dades.sstt') ."</option>\"";

            $data['tipus_actor_despeses'] = "\"<option value='centre_emissor' >". lang('dades.centre_emissor') ."</option><option value='centre_reparador' >". lang('dades.centre_reparador') ."</option><option value='poblacio' >". lang('dades.poblacio') ."</option><option value='comarca' >". lang('dades.comarca') ."</option><option  value='sstt_emissor' >". lang('dades.sstt_emissor') ."</option><option value='sstt_reparador' >". lang('dades.sstt_reparador') ."</option>\"";
        
            $data['tipus_actor_nombre_finalitzats_temps'] = "\"<option value='centre_reparador' >". lang('dades.centre_reparador') ."</option><option value='sstt' >". lang('dades.sstt') ."</option>\"";

            $data['tipus_actor_nombre_emesos_temps'] = "\"<option value='centre_emissor' >". lang('dades.centre_emissor') ."</option><option value='sstt' >". lang('dades.sstt') ."</option>\"";

            $data['tipus_actor_despeses_temps'] = "\"<option value='centre_reparador' >". lang('dades.centre_reparador') ."</option><option value='sstt' >". lang('dades.sstt') ."</option>\"";

        } else if ($role == "desenvolupador") {
           
            $data['tipus_actor_nombre_finalitzats'] = "\"<option value='centre_reparador' >". lang('dades.centre_reparador') ."</option><option  value='sstt' >". lang('dades.sstt') ."</option><option  value='total' >". lang('dades.total') ."</option>\"";
            
            $data['tipus_actor_nombre_emesos'] = "\"<option value='centre_emissor' >". lang('dades.centre_emissor') ."</option><option value='poblacio' >". lang('dades.poblacio') ."</option><option value='comarca' >". lang('dades.comarca') ."</option><option  value='sstt' >". lang('dades.sstt') ."</option><option  value='total' >". lang('dades.total') ."</option>\"";

            $data['tipus_actor_despeses'] = "\"<option value='centre_emissor' >". lang('dades.centre_emissor') ."</option><option value='centre_reparador' >". lang('dades.centre_reparador') ."</option><option value='poblacio' >". lang('dades.poblacio') ."</option><option value='comarca' >". lang('dades.comarca') ."</option><option  value='sstt_emissor' >". lang('dades.sstt_emissor') ."</option><option value='sstt_reparador' >". lang('dades.sstt_reparador') ."</option><option  value='total' >". lang('dades.total') ."</option>\"";
        
            $data['tipus_actor_nombre_finalitzats_temps'] = "\"<option value='centre_reparador' >". lang('dades.centre_reparador') ."</option><option value='sstt' >". lang('dades.sstt') ."</option><option value='total' >". lang('dades.total') ."</option>\"";

            $data['tipus_actor_nombre_emesos_temps'] = "\"<option value='centre_emissor' >". lang('dades.centre_emissor') ."</option><option value='sstt' >". lang('dades.sstt') ."</option><option value='total' >". lang('dades.total') ."</option>\"";
        
            $data['tipus_actor_despeses_temps'] = "\"<option value='centre_reparador' >". lang('dades.centre_reparador') ."</option><option value='sstt' >". lang('dades.sstt') ."</option><option value='total' >". lang('dades.total') ."</option>\"";
        }

        $data['output'] = "";
        if (session()->getFlashdata("taula") != null) {
            $data['output'] = session()->getFlashdata("taula");
        }

        $data['grafic'] = null;
        if (session()->getFlashdata("grafic") != null) {
            $data['grafic'] = session()->getFlashdata("grafic");
        }

        $data['grafic2'] = null;
        if (session()->getFlashdata("grafic2") != null) {
            $data['grafic2'] = session()->getFlashdata("grafic2");
        }

        return view('registres' . DIRECTORY_SEPARATOR . 'registreDades', $data);
    }

    public function descarregarDades($mode = null)
    {
        $tiquet_model = new TiquetModel();
        $centre_model = new CentreModel();
        $tipus_dispositiu_model = new TipusDispositiuModel();
        $sstt_model = new SSTTModel();
        $poblacio_model = new PoblacioModel();

        $actor = session()->get('user_data');
        $role = $actor['role'];

        if ($role == "admin_sstt") {
            $id_sstt = $actor['id_sstt'];
        }

        if ($role != "desenvolupador" && $role != "admin_sstt") {
            return redirect()->to(base_url('/tiquets'));
        }

        $tipus_dades = $this->request->getPost("tipus_dades");
        $tipus_actor = $this->request->getPost("tipus_actor");
        $tipus_dispositius = $this->request->getPost("tipus_dispositiu");

        if ($tipus_dispositius == "sense") {
            $id_tipus_dispositiu = "sense";
        } elseif ($tipus_dispositius == "tots_separats") {
            $id_tipus_dispositiu = "tots_separats";
        } else {
            $id_tipus_dispositiu = $tipus_dispositius;
        }

        $grafic = false;
        $grafic2 = false;
        if ($tipus_dades == "nombre_finalitzats") {
            $estat = $this->request->getPost("estat");


            if ($estat == "finalitzats") {
                $id_estat = "tots";
            } elseif ($estat == "retornats") {
                $id_estat = 9;
            } elseif ($estat == "desguassats") {
                $id_estat = 11;
            } elseif ($estat == "rebutjats") {
                $id_estat = 10;
            }
            

            if ($tipus_actor == "centre_reparador") {

                if ($id_tipus_dispositiu == "sense") {

                    if ($id_estat == "tots") {

                        $array_resultat = $tiquet_model->countNombreDispositiusTotsEstatsSenseTipus();
                        $array_eliminar = [];
                        for ($i = 0; $i < sizeof($array_resultat); $i++) {
                            if ($role == "admin_sstt") {
                                $pertany_sstt = $array_resultat[$i]['id_sstt'] == $id_sstt;
                                if (!$pertany_sstt) {
                                    array_push($array_eliminar, $i);
                                }
                            }
                        }
                        for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                            array_splice($array_resultat,$array_eliminar[$j],1);
                        }
                        
                        $file_name = "nombre_dispositius_finalitzats_" . date('Ymd') . '.csv';
                        $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                        
                        $file = fopen($file_path, 'w');
                        fwrite($file, "\xEF\xBB\xBF");
                        $header = ['Dades','Codi centre', 'Nom centre', 'Nombre dispositius'];
                        $array_visualitzar = [];
                        array_push($array_visualitzar,$header);
                        fputcsv($file, $header, ';'); 
                        foreach ($array_resultat as $row) {
                            $data = [
                                'Nombre de dispositius finalitzats per',
                                $row['codi_centre_reparador'], 
                                $row['nom_centre'], 
                                $row['num_tiquets']
                            ];
                            fputcsv($file, $data, ';'); 
                            array_push($array_visualitzar,$data);
                        }
                        fclose($file);
                        
                    } else {

                        $array_resultat = $tiquet_model->countNombreDispositiusEstatSenseTipus($id_estat);
                        $array_eliminar = [];
                        for ($i = 0; $i < sizeof($array_resultat); $i++) {
                            if ($role == "admin_sstt") {
                                $pertany_sstt = $array_resultat[$i]['id_sstt'] == $id_sstt;
                                if ($pertany_sstt) {
                                    $array_resultat[$i]['nom_centre'] = $centre_model->obtenirCentre($array_resultat[$i]['codi_centre_reparador'])['nom_centre'];
                                } else {
                                    
                                    array_push($array_eliminar, $i);
                                }
                            } else if ($role == "desenvolupador") {
                                $array_resultat[$i]['nom_centre'] = $centre_model->obtenirCentre($array_resultat[$i]['codi_centre_reparador'])['nom_centre'];
                            }
                        }
                        for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                            array_splice($array_resultat,$array_eliminar[$j],1);
                        }
                        
                        $file_name = "nombre_dispositius_". $estat . "_" . date('Ymd') . '.csv';
                        $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                        
                        $file = fopen($file_path, 'w');
                        fwrite($file, "\xEF\xBB\xBF");
                        $header = ['Dades','Codi centre', 'Nom centre', 'Nombre dispositius'];
                        $array_visualitzar = [];
                        array_push($array_visualitzar,$header);
                        fputcsv($file, $header, ';'); 
                        foreach ($array_resultat as $row) {
                            $data = [
                                'Nombre de dispositius ' . $estat . ' per',
                                $row['codi_centre_reparador'], 
                                $row['nom_centre'], 
                                $row['num_tiquets']
                            ];
                            fputcsv($file, $data, ';'); 
                            array_push($array_visualitzar,$data);
                        }
                        fclose($file);
                        
                    }
                    
                } elseif ($id_tipus_dispositiu == "tots_separats") {

                    if ($id_estat == "tots") {

                        $array_resultat = $tiquet_model->countNombreDispositiusTotsEstatsTotsTipus();
                        $array_eliminar = [];
                        for ($i = 0; $i < sizeof($array_resultat); $i++) {
                            if ($role == "admin_sstt") {
                                $pertany_sstt = $array_resultat[$i]['id_sstt'] == $id_sstt;
                                if ($pertany_sstt) {
                                    $array_resultat[$i]['nom_centre'] = $centre_model->obtenirCentre($array_resultat[$i]['codi_centre_reparador'])['nom_centre'];
                                    $array_resultat[$i]['nom_tipus_dispositiu'] = $tipus_dispositiu_model->obtenirTipusDispositiuPerId($array_resultat[$i]['id_tipus_dispositiu'])['nom_tipus_dispositiu'];
                                } else {
                                    
                                    array_push($array_eliminar, $i);
                                }
                            } else if ($role == "desenvolupador") {
                                $array_resultat[$i]['nom_centre'] = $centre_model->obtenirCentre($array_resultat[$i]['codi_centre_reparador'])['nom_centre'];
                                $array_resultat[$i]['nom_tipus_dispositiu'] = $tipus_dispositiu_model->obtenirTipusDispositiuPerId($array_resultat[$i]['id_tipus_dispositiu'])['nom_tipus_dispositiu'];
                            }
                        }
                        for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                            array_splice($array_resultat,$array_eliminar[$j],1);
                        }
                        
                        $file_name = "nombre_dispositius_finalitzats_tipus_dispositiu_" . date('Ymd') . '.csv';
                        $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                        
                        $file = fopen($file_path, 'w');
                        fwrite($file, "\xEF\xBB\xBF");
                        $header = ['Dades','Codi centre', 'Nom centre', 'Nom tipus dispositiu', 'Nombre dispositius'];
                        $array_visualitzar = [];
                        array_push($array_visualitzar,$header);
                        fwrite($file, "\xEF\xBB\xBF");
                        fputcsv($file, $header, ';'); 
                        foreach ($array_resultat as $row) {
                            $data = [
                                'Nombre de dispositius finalitzats per',
                                $row['codi_centre_reparador'], 
                                $row['nom_centre'], 
                                $row['nom_tipus_dispositiu'],
                                $row['num_tiquets'],
                            ];
                            fputcsv($file, $data, ';'); 
                            array_push($array_visualitzar,$data);
                        }
                        fclose($file);

                    } else {

                        $array_resultat = $tiquet_model->countNombreDispositiusEstatTotsTipus($id_estat);
                        $array_eliminar = [];
                        for ($i = 0; $i < sizeof($array_resultat); $i++) {
                            if ($role == "admin_sstt") {
                                $pertany_sstt = $array_resultat[$i]['id_sstt'] == $id_sstt;
                                if ($pertany_sstt) {
                                    $array_resultat[$i]['nom_centre'] = $centre_model->obtenirCentre($array_resultat[$i]['codi_centre_reparador'])['nom_centre'];
                                    $array_resultat[$i]['nom_tipus_dispositiu'] = $tipus_dispositiu_model->obtenirTipusDispositiuPerId($array_resultat[$i]['id_tipus_dispositiu'])['nom_tipus_dispositiu'];
                                } else {
                                    
                                    array_push($array_eliminar, $i);
                                }
                            } else if ($role == "desenvolupador") {
                                $array_resultat[$i]['nom_centre'] = $centre_model->obtenirCentre($array_resultat[$i]['codi_centre_reparador'])['nom_centre'];
                                $array_resultat[$i]['nom_tipus_dispositiu'] = $tipus_dispositiu_model->obtenirTipusDispositiuPerId($array_resultat[$i]['id_tipus_dispositiu'])['nom_tipus_dispositiu'];
                            }
                        }
                        for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                            array_splice($array_resultat,$array_eliminar[$j],1);
                        }
                        
                        $file_name = "nombre_dispositius_". $estat ."_tipus_dispositiu_" . date('Ymd') . '.csv';
                        $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                        
                        $file = fopen($file_path, 'w');
                        fwrite($file, "\xEF\xBB\xBF");
                        $header = ['Dades','Codi centre', 'Nom centre', 'Nom tipus dispositiu', 'Nombre dispositius'];
                        $array_visualitzar = [];
                        array_push($array_visualitzar,$header);
                        fputcsv($file, $header, ';'); 
                        foreach ($array_resultat as $row) {
                            $data = [
                                'Nombre de dispositius ' . $estat . ' per',
                                $row['codi_centre_reparador'], 
                                $row['nom_centre'], 
                                $row['nom_tipus_dispositiu'],
                                $row['num_tiquets']
                            ];
                            fputcsv($file, $data, ';'); 
                            array_push($array_visualitzar,$data);
                        }
                        fclose($file);

                    }

                } else {

                    if ($id_estat == "tots") {
                        
                        $array_resultat = $tiquet_model->countNombreDispositiusTotsEstatsTipus($id_tipus_dispositiu);
                        $nom_tipus_dispositiu = "";
                        $array_eliminar = [];
                        for ($i = 0; $i < sizeof($array_resultat); $i++) {
                            if ($role == "admin_sstt") {
                                $pertany_sstt = $array_resultat[$i]['id_sstt'] == $id_sstt;
                                if ($pertany_sstt) {
                                    $array_resultat[$i]['nom_centre'] = $centre_model->obtenirCentre($array_resultat[$i]['codi_centre_reparador'])['nom_centre'];
                                    $array_resultat[$i]['nom_tipus_dispositiu'] = $tipus_dispositiu_model->obtenirTipusDispositiuPerId($array_resultat[$i]['id_tipus_dispositiu'])['nom_tipus_dispositiu'];
                                    $nom_tipus_dispositiu = $array_resultat[$i]['nom_tipus_dispositiu'];
                                } else {
                                    
                                    array_push($array_eliminar, $i);
                                }
                            } else if ($role == "desenvolupador") {
                                $array_resultat[$i]['nom_centre'] = $centre_model->obtenirCentre($array_resultat[$i]['codi_centre_reparador'])['nom_centre'];
                                $array_resultat[$i]['nom_tipus_dispositiu'] = $tipus_dispositiu_model->obtenirTipusDispositiuPerId($array_resultat[$i]['id_tipus_dispositiu'])['nom_tipus_dispositiu'];
                                $nom_tipus_dispositiu = $array_resultat[$i]['nom_tipus_dispositiu'];
                            }
                        }
                        for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                            array_splice($array_resultat,$array_eliminar[$j],1);
                        }
                        
                        $file_name = "nombre_dispositius_finalitzats_". strtolower($nom_tipus_dispositiu) ."_" . date('Ymd') . '.csv';
                        $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                        
                        $file = fopen($file_path, 'w');
                        fwrite($file, "\xEF\xBB\xBF");
                        $header = ['Dades','Codi centre', 'Nom centre', 'Nom tipus dispositiu', 'Nombre dispositius'];
                        $array_visualitzar = [];
                        array_push($array_visualitzar,$header);
                        fputcsv($file, $header, ';'); 
                        foreach ($array_resultat as $row) {
                            $data = [
                                'Nombre de dispositius finalitzats per',
                                $row['codi_centre_reparador'], 
                                $row['nom_centre'], 
                                $row['nom_tipus_dispositiu'],
                                $row['num_tiquets']
                            ];
                            fputcsv($file, $data, ';'); 
                            array_push($array_visualitzar,$data);
                        }
                        fclose($file);

                    } else {

                        $array_resultat = $tiquet_model->countNombreDispositiusEstatTipus($id_estat, $id_tipus_dispositiu);
                        $nom_tipus_dispositiu = "";
                        $array_eliminar = [];
                        for ($i = 0; $i < sizeof($array_resultat); $i++) {
                            if ($role == "admin_sstt") {
                                $pertany_sstt = $array_resultat[$i]['id_sstt'] == $id_sstt;
                                if ($pertany_sstt) {
                                    $array_resultat[$i]['nom_centre'] = $centre_model->obtenirCentre($array_resultat[$i]['codi_centre_reparador'])['nom_centre'];
                                    $array_resultat[$i]['nom_tipus_dispositiu'] = $tipus_dispositiu_model->obtenirTipusDispositiuPerId($array_resultat[$i]['id_tipus_dispositiu'])['nom_tipus_dispositiu'];
                                    $nom_tipus_dispositiu = $array_resultat[$i]['nom_tipus_dispositiu'];
                                } else {
                                    
                                    array_push($array_eliminar, $i);
                                }
                            } else if ($role == "desenvolupador") {
                                $array_resultat[$i]['nom_centre'] = $centre_model->obtenirCentre($array_resultat[$i]['codi_centre_reparador'])['nom_centre'];
                                $array_resultat[$i]['nom_tipus_dispositiu'] = $tipus_dispositiu_model->obtenirTipusDispositiuPerId($array_resultat[$i]['id_tipus_dispositiu'])['nom_tipus_dispositiu'];
                                $nom_tipus_dispositiu = $array_resultat[$i]['nom_tipus_dispositiu'];
                            }
                        }
                        for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                            array_splice($array_resultat,$array_eliminar[$j],1);
                        }
                        
                        $file_name = "nombre_dispositius_". $estat ."_". strtolower($nom_tipus_dispositiu) ."_" . date('Ymd') . '.csv';
                        $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                        
                        $file = fopen($file_path, 'w');
                        fwrite($file, "\xEF\xBB\xBF");
                        $header = ['Dades','Codi centre', 'Nom centre', 'Nom tipus dispositiu', 'Nombre dispositius'];
                        $array_visualitzar = [];
                        array_push($array_visualitzar,$header);
                        fputcsv($file, $header, ';'); 
                        foreach ($array_resultat as $row) {
                            $data = [
                                'Nombre de dispositius '. $estat .' per',
                                $row['codi_centre_reparador'], 
                                $row['nom_centre'], 
                                $row['nom_tipus_dispositiu'],
                                $row['num_tiquets']
                            ];
                            fputcsv($file, $data, ';'); 
                            array_push($array_visualitzar,$data);
                        }
                        fclose($file);

                    }
                }

            } else if ($tipus_actor == "sstt") {

                if ($id_tipus_dispositiu == "sense") {

                    if ($id_estat == "tots") {

                        $array_resultat = $tiquet_model->countNombreDispositiusTotsEstatsSenseTipusSSTT();
                        $array_eliminar = [];
                        for ($i = 0; $i < sizeof($array_resultat); $i++) {
                            if ($role == "admin_sstt") {
                                $pertany_sstt = $array_resultat[$i]['id_sstt'] == $id_sstt;
                                if ($pertany_sstt) {
                                    $array_resultat[$i]['nom_sstt'] = $sstt_model->obtenirSSTTPerId($array_resultat[$i]['id_sstt'])['nom_sstt'];
                                } else {
                                    
                                    array_push($array_eliminar, $i);
                                }
                            } else if ($role == "desenvolupador") {
                                $array_resultat[$i]['nom_sstt'] = $sstt_model->obtenirSSTTPerId($array_resultat[$i]['id_sstt'])['nom_sstt'];
                            }
                        }
                        for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                            array_splice($array_resultat,$array_eliminar[$j],1);
                        }
                        
                        $file_name = "nombre_dispositius_finalitzats_" . date('Ymd') . '.csv';
                        $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                        
                        $file = fopen($file_path, 'w');
                        fwrite($file, "\xEF\xBB\xBF");
                        $header = ['Dades','Codi SSTT', 'Nom SSTT', 'Nombre dispositius'];
                        $array_visualitzar = [];
                        array_push($array_visualitzar,$header);
                        $grafic = true;
                        $labels = "";
                        $title = "Nombre de dispositius finalitzats per SSTT";
                        $dades = "";
                        fputcsv($file, $header, ';'); 
                        foreach ($array_resultat as $row) {
                            $data = [
                                'Nombre de dispositius finalitzats per',
                                $row['id_sstt'], 
                                $row['nom_sstt'], 
                                $row['num_tiquets']
                            ];
                            fputcsv($file, $data, ';'); 
                            array_push($array_visualitzar,$data);
                            $labels .= "'" . $row['nom_sstt'] . "',";
                            $dades .= "'" . $row['num_tiquets'] . "',";
                        }
                        fclose($file);
                        
                    } else {

                        $array_resultat = $tiquet_model->countNombreDispositiusEstatSenseTipusSSTT($id_estat);
                        $array_eliminar = [];
                        for ($i = 0; $i < sizeof($array_resultat); $i++) {
                            if ($role == "admin_sstt") {
                                $pertany_sstt = $array_resultat[$i]['id_sstt'] == $id_sstt;
                                if ($pertany_sstt) {
                                    $array_resultat[$i]['nom_sstt'] = $sstt_model->obtenirSSTTPerId($array_resultat[$i]['id_sstt'])['nom_sstt'];
                                } else {
                                    
                                    array_push($array_eliminar, $i);
                                }
                            } else if ($role == "desenvolupador") {
                                $array_resultat[$i]['nom_sstt'] = $sstt_model->obtenirSSTTPerId($array_resultat[$i]['id_sstt'])['nom_sstt'];
                            }
                        }
                        for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                            array_splice($array_resultat,$array_eliminar[$j],1);
                        }
                        
                        $file_name = "nombre_dispositius_". $estat . "_" . date('Ymd') . '.csv';
                        $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                        
                        $file = fopen($file_path, 'w');
                        fwrite($file, "\xEF\xBB\xBF");
                        $header = ['Dades','Codi SSTT', 'Nom SSTT', 'Nombre dispositius'];
                        $array_visualitzar = [];
                        array_push($array_visualitzar,$header);
                        $grafic = true;
                        $labels = "";
                        $title = "Nombre de dispositius " . $estat . "per SSTT";
                        $dades = "";
                        fputcsv($file, $header, ';'); 
                        foreach ($array_resultat as $row) {
                            $data = [
                                'Nombre de dispositius ' . $estat . ' per',
                                $row['id_sstt'], 
                                $row['nom_sstt'], 
                                $row['num_tiquets']
                            ];
                            fputcsv($file, $data, ';'); 
                            array_push($array_visualitzar,$data);
                            $labels .= "'" . $row['nom_sstt'] . "',";
                            $dades .= "'" . $row['num_tiquets'] . "',";
                        }
                        fclose($file);
                        
                    }
                    
                } elseif ($id_tipus_dispositiu == "tots_separats") {

                    if ($id_estat == "tots") {

                        $array_resultat = $tiquet_model->countNombreDispositiusTotsEstatsTotsTipusSSTT();
                        $array_eliminar = [];
                        for ($i = 0; $i < sizeof($array_resultat); $i++) {
                            if ($role == "admin_sstt") {
                                $pertany_sstt = $array_resultat[$i]['id_sstt'] == $id_sstt;
                                if ($pertany_sstt) {
                                    $array_resultat[$i]['nom_sstt'] = $sstt_model->obtenirSSTTPerId($array_resultat[$i]['id_sstt'])['nom_sstt'];
                                    $array_resultat[$i]['nom_tipus_dispositiu'] = $tipus_dispositiu_model->obtenirTipusDispositiuPerId($array_resultat[$i]['id_tipus_dispositiu'])['nom_tipus_dispositiu'];
                                } else {
                                    
                                    array_push($array_eliminar, $i);
                                }
                            } else if ($role == "desenvolupador") {
                                $array_resultat[$i]['nom_sstt'] = $sstt_model->obtenirSSTTPerId($array_resultat[$i]['id_sstt'])['nom_sstt'];
                                $array_resultat[$i]['nom_tipus_dispositiu'] = $tipus_dispositiu_model->obtenirTipusDispositiuPerId($array_resultat[$i]['id_tipus_dispositiu'])['nom_tipus_dispositiu'];
                            }
                        }
                        for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                            array_splice($array_resultat,$array_eliminar[$j],1);
                        }
                        
                        $file_name = "nombre_dispositius_finalitzats_tipus_dispositiu_" . date('Ymd') . '.csv';
                        $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                        
                        $file = fopen($file_path, 'w');
                        fwrite($file, "\xEF\xBB\xBF");
                        $header = ['Dades','Codi SSTT', 'Nom SSTT', 'Nom tipus dispositiu', 'Nombre dispositius'];
                        $array_visualitzar = [];
                        array_push($array_visualitzar,$header);
                        fputcsv($file, $header, ';'); 
                        foreach ($array_resultat as $row) {
                            $data = [
                                'Nombre de dispositius finalitzats per',
                                $row['id_sstt'], 
                                $row['nom_sstt'], 
                                $row['nom_tipus_dispositiu'],
                                $row['num_tiquets']
                            ];
                            fputcsv($file, $data, ';'); 
                            array_push($array_visualitzar,$data);
                        }
                        fclose($file);

                    } else {

                        $array_resultat = $tiquet_model->countNombreDispositiusEstatTotsTipusSSTT($id_estat);
                        $array_eliminar = [];
                        for ($i = 0; $i < sizeof($array_resultat); $i++) {
                            if ($role == "admin_sstt") {
                                $pertany_sstt = $array_resultat[$i]['id_sstt'] == $id_sstt;
                                if ($pertany_sstt) {
                                    $array_resultat[$i]['nom_sstt'] = $sstt_model->obtenirSSTTPerId($array_resultat[$i]['id_sstt'])['nom_sstt'];
                                    $array_resultat[$i]['nom_tipus_dispositiu'] = $tipus_dispositiu_model->obtenirTipusDispositiuPerId($array_resultat[$i]['id_tipus_dispositiu'])['nom_tipus_dispositiu'];
                                } else {
                                    
                                    array_push($array_eliminar, $i);
                                }
                            } else if ($role == "desenvolupador") {
                                $array_resultat[$i]['nom_sstt'] = $sstt_model->obtenirSSTTPerId($array_resultat[$i]['id_sstt'])['nom_sstt'];
                                $array_resultat[$i]['nom_tipus_dispositiu'] = $tipus_dispositiu_model->obtenirTipusDispositiuPerId($array_resultat[$i]['id_tipus_dispositiu'])['nom_tipus_dispositiu'];
                            }
                        }
                        for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                            array_splice($array_resultat,$array_eliminar[$j],1);
                        }
                        
                        $file_name = "nombre_dispositius_". $estat ."_tipus_dispositiu_" . date('Ymd') . '.csv';
                        $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                        
                        $file = fopen($file_path, 'w');
                        fwrite($file, "\xEF\xBB\xBF");
                        $header = ['Dades','Codi SSTT', 'Nom SSTT', 'Nom tipus dispositiu', 'Nombre dispositius'];
                        $array_visualitzar = [];
                        array_push($array_visualitzar,$header);
                        fputcsv($file, $header, ';'); 
                        foreach ($array_resultat as $row) {
                            $data = [
                                'Nombre de dispositius ' . $estat . ' per',
                                $row['id_sstt'], 
                                $row['nom_sstt'], 
                                $row['nom_tipus_dispositiu'],
                                $row['num_tiquets']
                            ];
                            fputcsv($file, $data, ';'); 
                            array_push($array_visualitzar,$data);
                        }
                        fclose($file);

                    }

                } else {

                    if ($id_estat == "tots") {
                        
                        $array_resultat = $tiquet_model->countNombreDispositiusTotsEstatsTipusSSTT($id_tipus_dispositiu);
                        $nom_tipus_dispositiu = "";
                        $array_eliminar = [];
                        for ($i = 0; $i < sizeof($array_resultat); $i++) {
                            if ($role == "admin_sstt") {
                                $pertany_sstt = $array_resultat[$i]['id_sstt'] == $id_sstt;
                                if ($pertany_sstt) {
                                    $array_resultat[$i]['nom_sstt'] = $sstt_model->obtenirSSTTPerId($array_resultat[$i]['id_sstt'])['nom_sstt'];
                                    $array_resultat[$i]['nom_tipus_dispositiu'] = $tipus_dispositiu_model->obtenirTipusDispositiuPerId($array_resultat[$i]['id_tipus_dispositiu'])['nom_tipus_dispositiu'];
                                    $nom_tipus_dispositiu = $array_resultat[$i]['nom_tipus_dispositiu'];
                                } else {
                                    
                                    array_push($array_eliminar, $i);
                                }
                            } else if ($role == "desenvolupador") {
                                $array_resultat[$i]['nom_sstt'] = $sstt_model->obtenirSSTTPerId($array_resultat[$i]['id_sstt'])['nom_sstt'];
                                $array_resultat[$i]['nom_tipus_dispositiu'] = $tipus_dispositiu_model->obtenirTipusDispositiuPerId($array_resultat[$i]['id_tipus_dispositiu'])['nom_tipus_dispositiu'];
                                $nom_tipus_dispositiu = $array_resultat[$i]['nom_tipus_dispositiu'];
                            }
                        }
                        for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                            array_splice($array_resultat,$array_eliminar[$j],1);
                        }
                        
                        $file_name = "nombre_dispositius_finalitzats_". strtolower($nom_tipus_dispositiu) ."_" . date('Ymd') . '.csv';
                        $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                        
                        $file = fopen($file_path, 'w');
                        fwrite($file, "\xEF\xBB\xBF");
                        $header = ['Dades','Codi SSTT', 'Nom SSTT', 'Nom tipus dispositiu', 'Nombre dispositius'];
                        $array_visualitzar = [];
                        array_push($array_visualitzar,$header);
                        $grafic = true;
                        $labels = "";
                        $title = "Nombre de dispositius finalitzats - ". strtolower($nom_tipus_dispositiu) . " per SSTT";
                        $dades = "";
                        fputcsv($file, $header, ';'); 
                        foreach ($array_resultat as $row) {
                            $data = [
                                'Nombre de dispositius finalitzats per',
                                $row['id_sstt'], 
                                $row['nom_sstt'], 
                                $row['nom_tipus_dispositiu'],
                                $row['num_tiquets']
                            ];
                            fputcsv($file, $data, ';'); 
                            array_push($array_visualitzar,$data);
                            $labels .= "'" . $row['nom_sstt'] . "',";
                            $dades .= "'" . $row['num_tiquets'] . "',";
                        }
                        fclose($file);

                    } else {

                        $array_resultat = $tiquet_model->countNombreDispositiusEstatTipusSSTT($id_estat, $id_tipus_dispositiu);
                        $nom_tipus_dispositiu = "";
                        $array_eliminar = [];
                        for ($i = 0; $i < sizeof($array_resultat); $i++) {
                            if ($role == "admin_sstt") {
                                $pertany_sstt = $array_resultat[$i]['id_sstt'] == $id_sstt;
                                if ($pertany_sstt) {
                                    $array_resultat[$i]['nom_sstt'] = $sstt_model->obtenirSSTTPerId($array_resultat[$i]['id_sstt'])['nom_sstt'];
                                    $array_resultat[$i]['nom_tipus_dispositiu'] = $tipus_dispositiu_model->obtenirTipusDispositiuPerId($array_resultat[$i]['id_tipus_dispositiu'])['nom_tipus_dispositiu'];
                                    $nom_tipus_dispositiu = $array_resultat[$i]['nom_tipus_dispositiu'];
                                } else {
                                    
                                    array_push($array_eliminar, $i);
                                }
                            } else if ($role == "desenvolupador") {
                                $array_resultat[$i]['nom_sstt'] = $sstt_model->obtenirSSTTPerId($array_resultat[$i]['id_sstt'])['nom_sstt'];
                                $array_resultat[$i]['nom_tipus_dispositiu'] = $tipus_dispositiu_model->obtenirTipusDispositiuPerId($array_resultat[$i]['id_tipus_dispositiu'])['nom_tipus_dispositiu'];
                                $nom_tipus_dispositiu = $array_resultat[$i]['nom_tipus_dispositiu'];
                            }
                        }
                        for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                            array_splice($array_resultat,$array_eliminar[$j],1);
                        }
                        
                        $file_name = "nombre_dispositius_". $estat ."_". strtolower($nom_tipus_dispositiu) ."_" . date('Ymd') . '.csv';
                        $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                        
                        $file = fopen($file_path, 'w');
                        fwrite($file, "\xEF\xBB\xBF");
                        $header = ['Dades','Codi SSTT', 'Nom SSTT', 'Nom tipus dispositiu', 'Nombre dispositius'];
                        $array_visualitzar = [];
                        array_push($array_visualitzar,$header);
                        $grafic = true;
                        $labels = "";
                        $title = "Nombre de dispositius " . $estat . " - " . strtolower($nom_tipus_dispositiu) . " per SSTT";
                        $dades = "";
                        fputcsv($file, $header, ';'); 
                        foreach ($array_resultat as $row) {
                            $data = [
                                'Nombre de dispositius '. $estat .' per',
                                $row['id_sstt'], 
                                $row['nom_sstt'], 
                                $row['nom_tipus_dispositiu'],
                                $row['num_tiquets']
                            ];
                            fputcsv($file, $data, ';'); 
                            array_push($array_visualitzar,$data);
                            $labels .= "'" . $row['nom_sstt'] . "',";
                            $dades .= "'" . $row['num_tiquets'] . "',";
                        }
                        fclose($file);

                    }
                }


            } else if ($tipus_actor == "total") {

                if ($role != "desenvolupador") {
                    return redirect()->back();
                }


                if ($id_tipus_dispositiu == "sense") {

                    if ($id_estat == "tots") {

                        $array_resultat = $tiquet_model->countNombreDispositiusTotsEstatsSenseTipusTOTAL();
                        
                        $file_name = "nombre_dispositius_finalitzats_" . date('Ymd') . '.csv';
                        $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                        
                        $file = fopen($file_path, 'w');
                        fwrite($file, "\xEF\xBB\xBF");
                        $header = ['Dades', 'Nombre dispositius'];
                        $array_visualitzar = [];
                        array_push($array_visualitzar,$header);
                        fputcsv($file, $header, ';'); 
                        foreach ($array_resultat as $row) {
                            $data = [
                                'Nombre de dispositius finalitzats',
                                $row['num_tiquets']
                            ];
                            fputcsv($file, $data, ';'); 
                            array_push($array_visualitzar,$data);
                        }
                        fclose($file);
                        
                    } else {

                        $array_resultat = $tiquet_model->countNombreDispositiusEstatSenseTipusTOTAL($id_estat);
                        
                        $file_name = "nombre_dispositius_". $estat . "_" . date('Ymd') . '.csv';
                        $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                        
                        $file = fopen($file_path, 'w');
                        fwrite($file, "\xEF\xBB\xBF");
                        $header = ['Dades', 'Nombre dispositius'];
                        $array_visualitzar = [];
                        array_push($array_visualitzar,$header);
                        fputcsv($file, $header, ';'); 
                        foreach ($array_resultat as $row) {
                            $data = [
                                'Nombre de dispositius ' . $estat,
                                $row['num_tiquets']
                            ];
                            fputcsv($file, $data, ';'); 
                            array_push($array_visualitzar,$data);
                        }
                        fclose($file);
                        
                    }
                    
                } elseif ($id_tipus_dispositiu == "tots_separats") {

                    if ($id_estat == "tots") {

                        $array_resultat = $tiquet_model->countNombreDispositiusTotsEstatsTotsTipusTOTAL();
                        for ($i = 0; $i < sizeof($array_resultat); $i++) {
                            $array_resultat[$i]['nom_tipus_dispositiu'] = $tipus_dispositiu_model->obtenirTipusDispositiuPerId($array_resultat[$i]['id_tipus_dispositiu'])['nom_tipus_dispositiu'];
                        }
                        
                        $file_name = "nombre_dispositius_finalitzats_tipus_dispositiu_" . date('Ymd') . '.csv';
                        $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                        
                        $file = fopen($file_path, 'w');
                        fwrite($file, "\xEF\xBB\xBF");
                        $header = ['Dades','Nom tipus dispositiu','Nombre dispositius'];
                        $array_visualitzar = [];
                        array_push($array_visualitzar,$header);
                        $grafic = true;
                        $labels = "";
                        $title = "Nombre de dispositius finalitzats per tipus de dispositiu";
                        $dades = "";
                        fputcsv($file, $header, ';'); 
                        foreach ($array_resultat as $row) {
                            $data = [
                                'Nombre de dispositius finalitzats',
                                $row['nom_tipus_dispositiu'],
                                $row['num_tiquets']
                            ];
                            fputcsv($file, $data, ';'); 
                            array_push($array_visualitzar,$data);
                            $labels .= "'" . $row['nom_tipus_dispositiu'] . "',";
                            $dades .= "'" . $row['num_tiquets'] . "',";
                        }
                        fclose($file);

                    } else {

                        $array_resultat = $tiquet_model->countNombreDispositiusEstatTotsTipusTOTAL($id_estat);

                        for ($i = 0; $i < sizeof($array_resultat); $i++) {
                            $array_resultat[$i]['nom_tipus_dispositiu'] = $tipus_dispositiu_model->obtenirTipusDispositiuPerId($array_resultat[$i]['id_tipus_dispositiu'])['nom_tipus_dispositiu'];
                        }
                        
                        $file_name = "nombre_dispositius_". $estat ."_tipus_dispositiu_" . date('Ymd') . '.csv';
                        $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                        
                        $file = fopen($file_path, 'w');
                        fwrite($file, "\xEF\xBB\xBF");
                        $header = ['Dades', 'Nom tipus dispositiu', 'Nombre dispositius'];
                        $array_visualitzar = [];
                        array_push($array_visualitzar,$header);
                        $grafic = true;
                        $labels = "";
                        $title = "Nombre de dispositius " . $estat .' per tipus de dispositiu';
                        $dades = "";
                        fputcsv($file, $header, ';'); 
                        foreach ($array_resultat as $row) {
                            $data = [
                                'Nombre de dispositius ' . $estat,
                                $row['nom_tipus_dispositiu'],
                                $row['num_tiquets']
                            ];
                            fputcsv($file, $data, ';'); 
                            array_push($array_visualitzar,$data);
                            $labels .= "'" . $row['nom_tipus_dispositiu'] . "',";
                            $dades .= "'" . $row['num_tiquets'] . "',";
                        }
                        fclose($file);

                    }

                } else {

                    if ($id_estat == "tots") {
                        
                        $array_resultat = $tiquet_model->countNombreDispositiusTotsEstatsTipusTOTAL($id_tipus_dispositiu);
                        $nom_tipus_dispositiu = "";
                        for ($i = 0; $i < sizeof($array_resultat); $i++) {
                                $array_resultat[$i]['nom_tipus_dispositiu'] = $tipus_dispositiu_model->obtenirTipusDispositiuPerId($array_resultat[$i]['id_tipus_dispositiu'])['nom_tipus_dispositiu'];
                                $nom_tipus_dispositiu = $array_resultat[$i]['nom_tipus_dispositiu'];
                        }
                        
                        $file_name = "nombre_dispositius_finalitzats_". strtolower($nom_tipus_dispositiu) ."_" . date('Ymd') . '.csv';
                        $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                        
                        $file = fopen($file_path, 'w');
                        fwrite($file, "\xEF\xBB\xBF");
                        $header = ['Dades', 'Nom tipus dispositiu', 'Nombre dispositius'];
                        $array_visualitzar = [];
                        array_push($array_visualitzar,$header);
                        fputcsv($file, $header, ';'); 
                        foreach ($array_resultat as $row) {
                            $data = [
                                'Nombre de dispositius finalitzats',
                                $row['nom_tipus_dispositiu'],
                                $row['num_tiquets']
                            ];
                            fputcsv($file, $data, ';'); 
                            array_push($array_visualitzar,$data);
                        }
                        fclose($file);

                    } else {

                        $array_resultat = $tiquet_model->countNombreDispositiusEstatTipusTOTAL($id_estat, $id_tipus_dispositiu);
                        $nom_tipus_dispositiu = "";
                        for ($i = 0; $i < sizeof($array_resultat); $i++) {
                            $array_resultat[$i]['nom_tipus_dispositiu'] = $tipus_dispositiu_model->obtenirTipusDispositiuPerId($array_resultat[$i]['id_tipus_dispositiu'])['nom_tipus_dispositiu'];
                            $nom_tipus_dispositiu = $array_resultat[$i]['nom_tipus_dispositiu'];
                        }
                        
                        $file_name = "nombre_dispositius_". $estat ."_". strtolower($nom_tipus_dispositiu) ."_" . date('Ymd') . '.csv';
                        $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                        
                        $file = fopen($file_path, 'w');
                        fwrite($file, "\xEF\xBB\xBF");
                        $header = ['Dades', 'Nom tipus dispositiu', 'Nombre dispositius'];
                        $array_visualitzar = [];
                        array_push($array_visualitzar,$header);
                        fputcsv($file, $header, ';'); 
                        foreach ($array_resultat as $row) {
                            $data = [
                                'Nombre de dispositius '. $estat,
                                $row['nom_tipus_dispositiu'],
                                $row['num_tiquets']
                            ];
                            fputcsv($file, $data, ';'); 
                            array_push($array_visualitzar,$data);
                        }
                        fclose($file);

                    }
                }

            }


        } else if ($tipus_dades == "nombre_emesos") {

            if ($tipus_actor == "centre_emissor") {

                if ($id_tipus_dispositiu == "sense") {

                    $array_resultat = $tiquet_model->countTiquetsSenseTipus();
                    $array_eliminar = [];
                    for ($i = 0; $i < sizeof($array_resultat); $i++) {
                        if ($role == "admin_sstt") {
                            $pertany_sstt = $array_resultat[$i]['id_sstt'] == $id_sstt;
                            if ($pertany_sstt) {
                                $array_resultat[$i]['nom_centre'] = $centre_model->obtenirCentre($array_resultat[$i]['codi_centre_emissor'])['nom_centre'];
                            } else {
                                array_push($array_eliminar, $i);
                            }
                        } else if ($role == "desenvolupador") {
                            $array_resultat[$i]['nom_centre'] = $centre_model->obtenirCentre($array_resultat[$i]['codi_centre_emissor'])['nom_centre'];
                        }
                    }
                    for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                        array_splice($array_resultat,$array_eliminar[$j],1);
                    }
                    
                    $file_name = "nombre_tiquets_emesos_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades','Codi centre', 'Nom centre', 'Nombre dispositius'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Nombre de tiquets emesos per',
                            $row['codi_centre_emissor'], 
                            $row['nom_centre'], 
                            $row['num_tiquets']
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                    }
                    fclose($file);

                } else if ($id_tipus_dispositiu == "tots_separats") {

                    $array_resultat = $tiquet_model->countTiquetsTotsTipus();
                    $array_eliminar = [];
                    for ($i = 0; $i < sizeof($array_resultat); $i++) {
                        if ($role == "admin_sstt") {
                            $pertany_sstt = $array_resultat[$i]['id_sstt'] == $id_sstt;
                            if ($pertany_sstt) {
                                $array_resultat[$i]['nom_centre'] = $centre_model->obtenirCentre($array_resultat[$i]['codi_centre_emissor'])['nom_centre'];
                                $array_resultat[$i]['nom_tipus_dispositiu'] = $tipus_dispositiu_model->obtenirTipusDispositiuPerId($array_resultat[$i]['id_tipus_dispositiu'])['nom_tipus_dispositiu'];
                            } else {
                                
                                array_push($array_eliminar, $i);
                            }
                        } else if ($role == "desenvolupador") {
                            $array_resultat[$i]['nom_centre'] = $centre_model->obtenirCentre($array_resultat[$i]['codi_centre_emissor'])['nom_centre'];
                            $array_resultat[$i]['nom_tipus_dispositiu'] = $tipus_dispositiu_model->obtenirTipusDispositiuPerId($array_resultat[$i]['id_tipus_dispositiu'])['nom_tipus_dispositiu'];
                        }
                    }
                    for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                        array_splice($array_resultat,$array_eliminar[$j],1);
                    }
                    
                    $file_name = "nombre_tiquets_emesos_tipus_dispositiu_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades','Codi centre', 'Nom centre', 'Nom tipus dispositiu', 'Nombre dispositius'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    fwrite($file, "\xEF\xBB\xBF");
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Nombre de tiquets emesos per',
                            $row['codi_centre_emissor'], 
                            $row['nom_centre'], 
                            $row['nom_tipus_dispositiu'],
                            $row['num_tiquets']
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                    }
                    fclose($file);

                } else {

                    $array_resultat = $tiquet_model->countTiquetsTipus($id_tipus_dispositiu);
                    $nom_tipus_dispositiu = "";
                    $array_eliminar = [];
                    for ($i = 0; $i < sizeof($array_resultat); $i++) {
                        if ($role == "admin_sstt") {
                            $pertany_sstt = $array_resultat[$i]['id_sstt'] == $id_sstt;
                            if ($pertany_sstt) {
                                $array_resultat[$i]['nom_centre'] = $centre_model->obtenirCentre($array_resultat[$i]['codi_centre_emissor'])['nom_centre'];
                                $array_resultat[$i]['nom_tipus_dispositiu'] = $tipus_dispositiu_model->obtenirTipusDispositiuPerId($array_resultat[$i]['id_tipus_dispositiu'])['nom_tipus_dispositiu'];
                                $nom_tipus_dispositiu = $array_resultat[$i]['nom_tipus_dispositiu'];
                            } else {
                                
                                array_push($array_eliminar, $i);
                            }
                        } else if ($role == "desenvolupador") {
                            $array_resultat[$i]['nom_centre'] = $centre_model->obtenirCentre($array_resultat[$i]['codi_centre_emissor'])['nom_centre'];
                            $array_resultat[$i]['nom_tipus_dispositiu'] = $tipus_dispositiu_model->obtenirTipusDispositiuPerId($array_resultat[$i]['id_tipus_dispositiu'])['nom_tipus_dispositiu'];
                            $nom_tipus_dispositiu = $array_resultat[$i]['nom_tipus_dispositiu'];
                        }
                    }
                    for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                        array_splice($array_resultat,$array_eliminar[$j],1);
                    }
                    
                    $file_name = "nombre_tiquets_emesos_". strtolower($nom_tipus_dispositiu) ."_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades','Codi centre', 'Nom centre', 'Nom tipus dispositiu', 'Nombre dispositius'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Nombre de tiquets emesos per',
                            $row['codi_centre_emissor'], 
                            $row['nom_centre'], 
                            $row['nom_tipus_dispositiu'],
                            $row['num_tiquets']
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                    }
                    fclose($file);

                }

            } elseif ($tipus_actor == "poblacio") {

                if ($id_tipus_dispositiu == "sense") {

                    $array_resultat = $tiquet_model->countTiquetsSenseTipusPOBLACIO();
                    $array_eliminar = [];
                    for ($i = 0; $i < sizeof($array_resultat); $i++) {
                        if ($role == "admin_sstt") {
                            $pertany_sstt = $poblacio_model->getPoblacio($array_resultat[$i]['id_poblacio'])['id_sstt'] == $id_sstt;
                            if (!$pertany_sstt) {
                                array_push($array_eliminar, $i);
                            }
                        }
                    }
                    for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                        array_splice($array_resultat,$array_eliminar[$j],1);
                    }
                    
                    $file_name = "nombre_tiquets_emesos_poblacio_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades','Codi municipi', 'Nom municipi', 'Nombre dispositius'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Nombre de tiquets emesos per',
                            $row['id_poblacio'], 
                            $row['nom_poblacio'], 
                            $row['num_tiquets']
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                    }
                    fclose($file);

                } else if ($id_tipus_dispositiu == "tots_separats") {

                    $array_resultat = $tiquet_model->countTiquetsTotsTipusPOBLACIO();
                    $array_eliminar = [];
                    for ($i = 0; $i < sizeof($array_resultat); $i++) {
                        if ($role == "admin_sstt") {
                            $pertany_sstt = $poblacio_model->getPoblacio($array_resultat[$i]['id_poblacio'])['id_sstt'] == $id_sstt;
                            if (!$pertany_sstt) {
                                array_push($array_eliminar, $i);
                            }
                        }
                    }
                    for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                        array_splice($array_resultat,$array_eliminar[$j],1);
                    }
                    
                    $file_name = "nombre_tiquets_emesos_poblacio_tipus_dispositiu_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades','Codi municipi', 'Nom municipi', 'Nom tipus dispositiu', 'Nombre dispositius'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    fwrite($file, "\xEF\xBB\xBF");
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Nombre de tiquets emesos per',
                            $row['id_poblacio'], 
                            $row['nom_poblacio'], 
                            $row['nom_tipus_dispositiu'],
                            $row['num_tiquets']
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                    }
                    fclose($file);

                } else {

                    $array_resultat = $tiquet_model->countTiquetsTipusPOBLACIO($id_tipus_dispositiu);
                    $array_eliminar = [];
                    for ($i = 0; $i < sizeof($array_resultat); $i++) {
                        if ($role == "admin_sstt") {
                            $pertany_sstt = $poblacio_model->getPoblacio($array_resultat[$i]['id_poblacio'])['id_sstt'] == $id_sstt;
                            if (!$pertany_sstt) {
                                array_push($array_eliminar, $i);
                            }
                        }
                    }
                    for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                        array_splice($array_resultat,$array_eliminar[$j],1);
                    }
                    
                    $file_name = "nombre_tiquets_emesos_poblacio_tipus_dispositiu_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades','Codi municipi', 'Nom municipi', 'Nom tipus dispositiu', 'Nombre dispositius'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    fwrite($file, "\xEF\xBB\xBF");
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Nombre de tiquets emesos per',
                            $row['id_poblacio'], 
                            $row['nom_poblacio'], 
                            $row['nom_tipus_dispositiu'],
                            $row['num_tiquets']
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                    }
                    fclose($file);

                }

            } elseif ($tipus_actor == "comarca") {

                if ($id_tipus_dispositiu == "sense") {

                    $array_resultat = $tiquet_model->countTiquetsSenseTipusCOMARCA();
                    $array_eliminar = [];
                    for ($i = 0; $i < sizeof($array_resultat); $i++) {
                        if ($role == "admin_sstt") {
                            $pertany_sstt = $poblacio_model->getPoblacio($array_resultat[$i]['id_poblacio'])['id_sstt'] == $id_sstt;
                            if (!$pertany_sstt) {
                                array_push($array_eliminar, $i);
                            }
                        }
                    }
                    for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                        array_splice($array_resultat,$array_eliminar[$j],1);
                    }
                    
                    $file_name = "nombre_tiquets_emesos_comarca_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades','Codi comarca', 'Nom comarca', 'Nombre dispositius'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    $grafic = true;
                    $labels = "";
                    $title = "Nombre de tiquets emesos per comarca";
                    $dades = "";
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Nombre de tiquets emesos per',
                            $row['id_comarca'], 
                            $row['nom_comarca'], 
                            $row['num_tiquets']
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                        $labels .= "'" . $row['nom_comarca'] . "',";
                        $dades .= "'" . $row['num_tiquets'] . "',";
                    }
                    fclose($file);

                } else if ($id_tipus_dispositiu == "tots_separats") {

                    $array_resultat = $tiquet_model->countTiquetsTotsTipusCOMARCA();
                    $array_eliminar = [];
                    for ($i = 0; $i < sizeof($array_resultat); $i++) {
                        if ($role == "admin_sstt") {
                            $pertany_sstt = $poblacio_model->getPoblacio($array_resultat[$i]['id_poblacio'])['id_sstt'] == $id_sstt;
                            if (!$pertany_sstt) {
                                array_push($array_eliminar, $i);
                            }
                        }
                    }
                    for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                        array_splice($array_resultat,$array_eliminar[$j],1);
                    }
                    
                    $file_name = "nombre_tiquets_emesos_comarca_tipus_dispositiu_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades','Codi comarca', 'Nom comarca', 'Nom tipus dispositiu', 'Nombre dispositius'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Nombre de tiquets emesos per',
                            $row['id_comarca'], 
                            $row['nom_comarca'], 
                            $row['nom_tipus_dispositiu'],
                            $row['num_tiquets']
                        ];
                        fputcsv($file, $data, ';');
                        array_push($array_visualitzar,$data); 
                    }
                    fclose($file);

                } else {

                    $array_resultat = $tiquet_model->countTiquetsTipusCOMARCA($id_tipus_dispositiu);
                    $array_eliminar = [];
                    for ($i = 0; $i < sizeof($array_resultat); $i++) {
                        if ($role == "admin_sstt") {
                            $pertany_sstt = $poblacio_model->getPoblacio($array_resultat[$i]['id_poblacio'])['id_sstt'] == $id_sstt;
                            if (!$pertany_sstt) {
                                array_push($array_eliminar, $i);
                            }
                        }
                    }
                    for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                        array_splice($array_resultat,$array_eliminar[$j],1);
                    }
                    
                    $file_name = "nombre_tiquets_emesos_comarca_". strtolower($tipus_dispositiu_model->obtenirTipusDispositiuPerId($id_tipus_dispositiu)['nom_tipus_dispositiu']) ."_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades','Codi comarca', 'Nom comarca', 'Nom tipus dispositiu', 'Nombre dispositius'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    $grafic = true;
                    $labels = "";
                    $title = "Nombre de tiquets emesos per comarca - " . strtolower($tipus_dispositiu_model->obtenirTipusDispositiuPerId($id_tipus_dispositiu)['nom_tipus_dispositiu']);
                    $dades = "";
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Nombre de tiquets emesos per',
                            $row['id_comarca'], 
                            $row['nom_comarca'], 
                            $row['nom_tipus_dispositiu'],
                            $row['num_tiquets']
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                        $labels .= "'" . $row['nom_comarca'] . "',";
                        $dades .= "'" . $row['num_tiquets'] . "',";
                    }
                    fclose($file);

                }

            } elseif ($tipus_actor == "sstt") {

                if ($id_tipus_dispositiu == "sense") {

                    $array_resultat = $tiquet_model->countTiquetsSenseTipusSSTT();
                    $array_eliminar = [];
                    for ($i = 0; $i < sizeof($array_resultat); $i++) {
                        if ($role == "admin_sstt") {
                            $pertany_sstt = $array_resultat[$i]['id_sstt'] == $id_sstt;
                            if (!$pertany_sstt) {
                                array_push($array_eliminar, $i);
                            }
                        }
                    }
                    for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                        array_splice($array_resultat,$array_eliminar[$j],1);
                    }
                    
                    $file_name = "nombre_tiquets_emesos_sstt_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades','Codi SSTT', 'Nom SSTT', 'Nombre dispositius'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    $grafic = true;
                    $labels = "";
                    $title = "Nombre de tiquets emesos per SSTT";
                    $dades = "";
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Nombre de tiquets emesos per',
                            $row['id_sstt'], 
                            $row['nom_sstt'], 
                            $row['num_tiquets']
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                        $labels .= "'" . $row['nom_sstt'] . "',";
                        $dades .= "'" . $row['num_tiquets'] . "',";
                    }
                    fclose($file);

                } else if ($id_tipus_dispositiu == "tots_separats") {

                    $array_resultat = $tiquet_model->countTiquetsTotsTipusSSTT();
                    $array_eliminar = [];
                    for ($i = 0; $i < sizeof($array_resultat); $i++) {
                        if ($role == "admin_sstt") {
                            $pertany_sstt = $array_resultat[$i]['id_sstt'] == $id_sstt;
                            if (!$pertany_sstt) {
                                array_push($array_eliminar, $i);
                            }
                        }
                    }
                    for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                        array_splice($array_resultat,$array_eliminar[$j],1);
                    }
                    
                    $file_name = "nombre_tiquets_emesos_sstt_tipus_dispositiu_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades','Codi SSTT', 'Nom SSTT', 'Nom tipus dispositiu', 'Nombre dispositius'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Nombre de tiquets emesos per',
                            $row['id_sstt'], 
                            $row['nom_sstt'],
                            $row['nom_tipus_dispositiu'], 
                            $row['num_tiquets']
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                    }
                    fclose($file);

                } else {

                    $array_resultat = $tiquet_model->countTiquetsTipusSSTT($id_tipus_dispositiu);
                    $array_eliminar = [];
                    for ($i = 0; $i < sizeof($array_resultat); $i++) {
                        if ($role == "admin_sstt") {
                            $pertany_sstt = $array_resultat[$i]['id_sstt'] == $id_sstt;
                            if (!$pertany_sstt) {
                                array_push($array_eliminar, $i);
                            }
                        }
                    }
                    for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                        array_splice($array_resultat,$array_eliminar[$j],1);
                    }
                    
                    $file_name = "nombre_tiquets_emesos_sstt_". strtolower($tipus_dispositiu_model->obtenirTipusDispositiuPerId($id_tipus_dispositiu)['nom_tipus_dispositiu']) ."_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades','Codi SSTT', 'Nom SSTT', 'Nom tipus dispositiu', 'Nombre dispositius'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    $grafic = true;
                    $labels = "";
                    $title = "Nombre de tiquets emesos per SSTT - ". strtolower($tipus_dispositiu_model->obtenirTipusDispositiuPerId($id_tipus_dispositiu)['nom_tipus_dispositiu']);
                    $dades = "";
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Nombre de tiquets emesos per',
                            $row['id_sstt'], 
                            $row['nom_sstt'],
                            $row['nom_tipus_dispositiu'], 
                            $row['num_tiquets']
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                        $labels .= "'" . $row['nom_sstt'] . "',";
                        $dades .= "'" . $row['num_tiquets'] . "',";
                    }
                    fclose($file);

                }

            } elseif ($tipus_actor == "total") {

                if ($role != "desenvolupador") {
                    return redirect()->back();
                }

                if ($id_tipus_dispositiu == "sense") {

                    $array_resultat = $tiquet_model->countTiquetsSenseTipusTOTAL();
                    
                    $file_name = "nombre_tiquets_emesos_total_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades', 'Nombre dispositius'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Nombre de tiquets emesos per',
                            $row['num_tiquets']
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                    }
                    fclose($file);

                } else if ($id_tipus_dispositiu == "tots_separats") {

                    $array_resultat = $tiquet_model->countTiquetsTotsTipusTOTAL();
                    
                    $file_name = "nombre_tiquets_emesos_total_tipus_dispositiu_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades', 'Nom tipus dispositiu', 'Nombre dispositius'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    $grafic = true;
                    $labels = "";
                    $title = "Nombre de tiquets emesos per tipus de dispositius";
                    $dades = "";
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Nombre de tiquets emesos per',
                            $row['nom_tipus_dispositiu'], 
                            $row['num_tiquets']
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                        $labels .= "'" . $row['nom_tipus_dispositiu'] . "',";
                        $dades .= "'" . $row['num_tiquets'] . "',";
                    }
                    fclose($file);

                } else {

                    $array_resultat = $tiquet_model->countTiquetsTipusTOTAL($id_tipus_dispositiu);
                    
                    $file_name = "nombre_tiquets_emesos_total_". strtolower($tipus_dispositiu_model->obtenirTipusDispositiuPerId($id_tipus_dispositiu)['nom_tipus_dispositiu']) ."_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades', 'Nom tipus dispositiu', 'Nombre dispositius'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Nombre de tiquets emesos per',
                            $row['nom_tipus_dispositiu'], 
                            $row['num_tiquets']
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                    }
                    fclose($file);

                }

            }

        }  else if ($tipus_dades == "despeses") {

            if ($tipus_actor == "centre_emissor") {

                if ($id_tipus_dispositiu == "sense") {

                    $array_resultat = $tiquet_model->sumTiquetsSenseTipusCentreEmissor();
                    $array_eliminar = [];
                    for ($i = 0; $i < sizeof($array_resultat); $i++) {
                        if ($role == "admin_sstt") {
                            $pertany_sstt = $poblacio_model->getPoblacio($array_resultat[$i]['id_poblacio'])['id_sstt'] == $id_sstt;
                            if (!$pertany_sstt) {
                                array_push($array_eliminar, $i);
                            }
                        }
                    }
                    for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                        array_splice($array_resultat,$array_eliminar[$j],1);
                    }
                    
                    $file_name = "despeses_centre_emissor_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades','Codi centre', 'Nom centre', 'Diners (€)'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Despeses per',
                            $row['codi_centre_emissor'], 
                            $row['nom_centre'], 
                            str_replace('.', ',', $row['total_preu']),
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                    }
                    fclose($file);

                } else if ($id_tipus_dispositiu == "tots_separats") {

                    $array_resultat = $tiquet_model->sumTiquetsTotsTipusCentreEmissor();
                    $array_eliminar = [];
                    for ($i = 0; $i < sizeof($array_resultat); $i++) {
                        if ($role == "admin_sstt") {
                            $pertany_sstt = $poblacio_model->getPoblacio($array_resultat[$i]['id_poblacio'])['id_sstt'] == $id_sstt;
                            if (!$pertany_sstt) {
                                array_push($array_eliminar, $i);
                            }
                        }
                    }
                    for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                        array_splice($array_resultat,$array_eliminar[$j],1);
                    }
                    
                    $file_name = "despeses_centre_emissor_tipus_dispositiu_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades','Codi centre', 'Nom centre', 'Nom tipus dispositiu', 'Diners (€)'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Despeses per',
                            $row['codi_centre_emissor'], 
                            $row['nom_centre'], 
                            $row['nom_tipus_dispositiu'],
                            str_replace('.', ',', $row['total_preu']),
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                    }
                    fclose($file);

                } else {

                    $array_resultat = $tiquet_model->sumTiquetsTipusCentreEmissor($id_tipus_dispositiu);
                    $array_eliminar = [];
                    for ($i = 0; $i < sizeof($array_resultat); $i++) {
                        if ($role == "admin_sstt") {
                            $pertany_sstt = $poblacio_model->getPoblacio($array_resultat[$i]['id_poblacio'])['id_sstt'] == $id_sstt;
                            if (!$pertany_sstt) {
                                array_push($array_eliminar, $i);
                            }
                        }
                    }
                    for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                        array_splice($array_resultat,$array_eliminar[$j],1);
                    }
                    
                    $file_name = "despeses_centre_emissor_". strtolower($tipus_dispositiu_model->obtenirTipusDispositiuPerId($id_tipus_dispositiu)['nom_tipus_dispositiu']) ."_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades','Codi centre', 'Nom centre', 'Nom tipus dispositiu', 'Diners (€)'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Despeses per',
                            $row['codi_centre_emissor'], 
                            $row['nom_centre'], 
                            $row['nom_tipus_dispositiu'],
                            str_replace('.', ',', $row['total_preu']),
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                    }
                    fclose($file);

                }

            } elseif ($tipus_actor == "centre_reparador") {

                if ($id_tipus_dispositiu == "sense") {

                    $array_resultat = $tiquet_model->sumTiquetsSenseTipusCentreReparador();
                    $array_eliminar = [];
                    for ($i = 0; $i < sizeof($array_resultat); $i++) {
                        if ($role == "admin_sstt") {
                            $pertany_sstt = $poblacio_model->getPoblacio($array_resultat[$i]['id_poblacio'])['id_sstt'] == $id_sstt;
                            if (!$pertany_sstt) {
                                array_push($array_eliminar, $i);
                            }
                        }
                    }
                    for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                        array_splice($array_resultat,$array_eliminar[$j],1);
                    }
                    
                    $file_name = "despeses_centre_reparador_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades','Codi centre', 'Nom centre', 'Diners (€)'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Despeses per',
                            $row['codi_centre_reparador'], 
                            $row['nom_centre'], 
                            str_replace('.', ',', $row['total_preu']),
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                    }
                    fclose($file);

                } else if ($id_tipus_dispositiu == "tots_separats") {

                    $array_resultat = $tiquet_model->sumTiquetsTotsTipusCentreReparador();
                    $array_eliminar = [];
                    for ($i = 0; $i < sizeof($array_resultat); $i++) {
                        if ($role == "admin_sstt") {
                            $pertany_sstt = $poblacio_model->getPoblacio($array_resultat[$i]['id_poblacio'])['id_sstt'] == $id_sstt;
                            if (!$pertany_sstt) {
                                array_push($array_eliminar, $i);
                            }
                        }
                    }
                    for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                        array_splice($array_resultat,$array_eliminar[$j],1);
                    }
                    
                    $file_name = "despeses_centre_reparador_tipus_dispositiu_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades','Codi centre', 'Nom centre', 'Nom tipus dispositiu', 'Diners (€)'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Despeses per',
                            $row['codi_centre_reparador'], 
                            $row['nom_centre'], 
                            $row['nom_tipus_dispositiu'],
                            str_replace('.', ',', $row['total_preu']),
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                    }
                    fclose($file);

                } else {

                    $array_resultat = $tiquet_model->sumTiquetsTipusCentreReparador($id_tipus_dispositiu);
                    $array_eliminar = [];
                    for ($i = 0; $i < sizeof($array_resultat); $i++) {
                        if ($role == "admin_sstt") {
                            $pertany_sstt = $poblacio_model->getPoblacio($array_resultat[$i]['id_poblacio'])['id_sstt'] == $id_sstt;
                            if (!$pertany_sstt) {
                                array_push($array_eliminar, $i);
                            }
                        }
                    }
                    for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                        array_splice($array_resultat,$array_eliminar[$j],1);
                    }
                    
                    $file_name = "despeses_centre_reparador_". strtolower($tipus_dispositiu_model->obtenirTipusDispositiuPerId($id_tipus_dispositiu)['nom_tipus_dispositiu']) ."_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades','Codi centre', 'Nom centre', 'Nom tipus dispositiu', 'Diners (€)'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Despeses per',
                            $row['codi_centre_reparador'], 
                            $row['nom_centre'], 
                            $row['nom_tipus_dispositiu'],
                            str_replace('.', ',', $row['total_preu']),
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                    }
                    fclose($file);

                }

            } elseif ($tipus_actor == "poblacio") {

                if ($id_tipus_dispositiu == "sense") {

                    $array_resultat = $tiquet_model->sumTiquetsSenseTipusPoblacio();
                    $array_eliminar = [];
                    for ($i = 0; $i < sizeof($array_resultat); $i++) {
                        if ($role == "admin_sstt") {
                            $pertany_sstt = $poblacio_model->getPoblacio($array_resultat[$i]['id_poblacio'])['id_sstt'] == $id_sstt;
                            if (!$pertany_sstt) {
                                array_push($array_eliminar, $i);
                            }
                        }
                    }
                    for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                        array_splice($array_resultat,$array_eliminar[$j],1);
                    }
                    
                    $file_name = "despeses_poblacio_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades','Codi municipi', 'Nom municipi', 'Diners (€)'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Despeses per',
                            $row['id_poblacio'], 
                            $row['nom_poblacio'], 
                            str_replace('.', ',', $row['total_preu']),
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                    }
                    fclose($file);

                } else if ($id_tipus_dispositiu == "tots_separats") {
                    
                    $array_resultat = $tiquet_model->sumTiquetsTotsTipusPoblacio();
                    $array_eliminar = [];
                    for ($i = 0; $i < sizeof($array_resultat); $i++) {
                        if ($role == "admin_sstt") {
                            $pertany_sstt = $poblacio_model->getPoblacio($array_resultat[$i]['id_poblacio'])['id_sstt'] == $id_sstt;
                            if (!$pertany_sstt) {
                                array_push($array_eliminar, $i);
                            }
                        }
                    }
                    for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                        array_splice($array_resultat,$array_eliminar[$j],1);
                    }
                    
                    $file_name = "despeses_poblacio_tipus_dispositiu_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades','Codi municipi', 'Nom municipi', 'Nom tipus dispositiu', 'Diners (€)'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Despeses per',
                            $row['id_poblacio'], 
                            $row['nom_poblacio'], 
                            $row['nom_tipus_dispositiu'],
                            str_replace('.', ',', $row['total_preu']),
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                    }
                    fclose($file);

                } else {

                    $array_resultat = $tiquet_model->sumTiquetsTipusPoblacio($id_tipus_dispositiu);
                    $array_eliminar = [];
                    for ($i = 0; $i < sizeof($array_resultat); $i++) {
                        if ($role == "admin_sstt") {
                            $pertany_sstt = $poblacio_model->getPoblacio($array_resultat[$i]['id_poblacio'])['id_sstt'] == $id_sstt;
                            if (!$pertany_sstt) {
                                array_push($array_eliminar, $i);
                            }
                        }
                    }
                    for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                        array_splice($array_resultat,$array_eliminar[$j],1);
                    }
                    
                    $file_name = "despeses_poblacio_". strtolower($tipus_dispositiu_model->obtenirTipusDispositiuPerId($id_tipus_dispositiu)['nom_tipus_dispositiu']) ."_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades','Codi municipi', 'Nom municipi', 'Nom tipus dispositiu', 'Diners (€)'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Despeses per',
                            $row['id_poblacio'], 
                            $row['nom_poblacio'], 
                            $row['nom_tipus_dispositiu'],
                            str_replace('.', ',', $row['total_preu']),
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                    }
                    fclose($file);

                }

            } elseif ($tipus_actor == "comarca") {

                if ($id_tipus_dispositiu == "sense") {

                    $array_resultat = $tiquet_model->sumTiquetsSenseTipusComarca();
                    $array_eliminar = [];
                    for ($i = 0; $i < sizeof($array_resultat); $i++) {
                        if ($role == "admin_sstt") {
                            $pertany_sstt = $poblacio_model->getPoblacio($array_resultat[$i]['id_poblacio'])['id_sstt'] == $id_sstt;
                            if (!$pertany_sstt) {
                                array_push($array_eliminar, $i);
                            }
                        }
                    }
                    for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                        array_splice($array_resultat,$array_eliminar[$j],1);
                    }
                    
                    $file_name = "despeses_comarca_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades','Codi comarca', 'Nom comarca', 'Diners (€)'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    $grafic = true;
                    $labels = "";
                    $title = "Despeses per comarca";
                    $dades = "";
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Despeses per',
                            $row['id_comarca'], 
                            $row['nom_comarca'], 
                            str_replace('.', ',', $row['total_preu']),
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                        $labels .= "'" . $row['nom_comarca'] . "',";
                        $dades .= "'" . $row['total_preu'] . "',";
                    }
                    fclose($file);

                } else if ($id_tipus_dispositiu == "tots_separats") {
                    
                    $array_resultat = $tiquet_model->sumTiquetsTotsTipusComarca();
                    $array_eliminar = [];
                    for ($i = 0; $i < sizeof($array_resultat); $i++) {
                        if ($role == "admin_sstt") {
                            $pertany_sstt = $poblacio_model->getPoblacio($array_resultat[$i]['id_poblacio'])['id_sstt'] == $id_sstt;
                            if (!$pertany_sstt) {
                                array_push($array_eliminar, $i);
                            }
                        }
                    }
                    for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                        array_splice($array_resultat,$array_eliminar[$j],1);
                    }
                    
                    $file_name = "despeses_comarca_tipus_dispositiu_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades','Codi comarca', 'Nom comarca', 'Nom tipus dispositiu', 'Diners (€)'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Despeses per',
                            $row['id_comarca'], 
                            $row['nom_comarca'], 
                            $row['nom_tipus_dispositiu'],
                            str_replace('.', ',', $row['total_preu']),
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                    }
                    fclose($file);

                } else {

                    $array_resultat = $tiquet_model->sumTiquetsTipusComarca($id_tipus_dispositiu);
                    $array_eliminar = [];
                    for ($i = 0; $i < sizeof($array_resultat); $i++) {
                        if ($role == "admin_sstt") {
                            $pertany_sstt = $poblacio_model->getPoblacio($array_resultat[$i]['id_poblacio'])['id_sstt'] == $id_sstt;
                            if (!$pertany_sstt) {
                                array_push($array_eliminar, $i);
                            }
                        }
                    }
                    for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                        array_splice($array_resultat,$array_eliminar[$j],1);
                    }
                    
                    $file_name = "despeses_comarca_". strtolower($tipus_dispositiu_model->obtenirTipusDispositiuPerId($id_tipus_dispositiu)['nom_tipus_dispositiu']) ."_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades','Codi comarca', 'Nom comarca', 'Nom tipus dispositiu', 'Diners (€)'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    $grafic = true;
                    $labels = "";
                    $title = "Despeses per comarca - " . strtolower($tipus_dispositiu_model->obtenirTipusDispositiuPerId($id_tipus_dispositiu)['nom_tipus_dispositiu']);
                    $dades = "";
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Despeses per',
                            $row['id_comarca'], 
                            $row['nom_comarca'], 
                            $row['nom_tipus_dispositiu'],
                            str_replace('.', ',', $row['total_preu']),
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                        $labels .= "'" . $row['nom_comarca'] . "',";
                        $dades .= "'" . $row['total_preu'] . "',";
                    }
                    fclose($file);

                }

            } elseif ($tipus_actor == "sstt_emissor") {

                if ($id_tipus_dispositiu == "sense") {

                    $array_resultat = $tiquet_model->sumTiquetsSenseTipusSSTTEmissor();
                    $array_eliminar = [];
                    for ($i = 0; $i < sizeof($array_resultat); $i++) {
                        if ($role == "admin_sstt") {
                            $pertany_sstt = $array_resultat[$i]['id_sstt'] == $id_sstt;
                            if (!$pertany_sstt) {
                                array_push($array_eliminar, $i);
                            }
                        }
                    }
                    for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                        array_splice($array_resultat,$array_eliminar[$j],1);
                    }
                    
                    $file_name = "despeses_sstt_emissor_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades','Codi SSTT', 'Nom SSTT', 'Diners (€)'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    $grafic = true;
                    $labels = "";
                    $title = "Despeses per SSTT (emissor)";
                    $dades = "";
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Despeses per',
                            $row['id_sstt'], 
                            $row['nom_sstt'], 
                            str_replace('.', ',', $row['total_preu']),
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                        $labels .= "'" . $row['nom_sstt'] . "',";
                        $dades .= "'" . $row['total_preu'] . "',";
                    }
                    fclose($file);

                } else if ($id_tipus_dispositiu == "tots_separats") {

                    $array_resultat = $tiquet_model->sumTiquetsTotsTipusSSTTEmissor();
                    $array_eliminar = [];
                    for ($i = 0; $i < sizeof($array_resultat); $i++) {
                        if ($role == "admin_sstt") {
                            $pertany_sstt = $array_resultat[$i]['id_sstt'] == $id_sstt;
                            if (!$pertany_sstt) {
                                array_push($array_eliminar, $i);
                            }
                        }
                    }
                    for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                        array_splice($array_resultat,$array_eliminar[$j],1);
                    }
                    
                    $file_name = "despeses_sstt_emissor_tipus_dispositiu_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades','Codi SSTT', 'Nom SSTT', 'Nom tipus dispositiu', 'Diners (€)'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Despeses per',
                            $row['id_sstt'], 
                            $row['nom_sstt'], 
                            $row['nom_tipus_dispositiu'],
                            str_replace('.', ',', $row['total_preu']),
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                    }
                    fclose($file);
                    
                } else {

                    $array_resultat = $tiquet_model->sumTiquetsTipusSSTTEmissor($id_tipus_dispositiu);
                    $array_eliminar = [];
                    for ($i = 0; $i < sizeof($array_resultat); $i++) {
                        if ($role == "admin_sstt") {
                            $pertany_sstt = $array_resultat[$i]['id_sstt'] == $id_sstt;
                            if (!$pertany_sstt) {
                                array_push($array_eliminar, $i);
                            }
                        }
                    }
                    for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                        array_splice($array_resultat,$array_eliminar[$j],1);
                    }
                    
                    $file_name = "despeses_sstt_emissor_". strtolower($tipus_dispositiu_model->obtenirTipusDispositiuPerId($id_tipus_dispositiu)['nom_tipus_dispositiu']) ."_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades','Codi SSTT', 'Nom SSTT', 'Nom tipus dispositiu', 'Diners (€)'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    $grafic = true;
                    $labels = "";
                    $title = "Despeses per SSTT (emissor) - " . strtolower($tipus_dispositiu_model->obtenirTipusDispositiuPerId($id_tipus_dispositiu)['nom_tipus_dispositiu']);
                    $dades = "";
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Despeses per',
                            $row['id_sstt'], 
                            $row['nom_sstt'], 
                            $row['nom_tipus_dispositiu'],
                            str_replace('.', ',', $row['total_preu']),
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                        $labels .= "'" . $row['nom_sstt'] . "',";
                        $dades .= "'" . $row['total_preu'] . "',";
                    }
                    fclose($file);

                }

            } elseif ($tipus_actor == "sstt_reparador") {

                if ($id_tipus_dispositiu == "sense") {

                    $array_resultat = $tiquet_model->sumTiquetsSenseTipusSSTTReparador();
                    $array_eliminar = [];
                    for ($i = 0; $i < sizeof($array_resultat); $i++) {
                        if ($role == "admin_sstt") {
                            $pertany_sstt = $array_resultat[$i]['id_sstt'] == $id_sstt;
                            if (!$pertany_sstt) {
                                array_push($array_eliminar, $i);
                            }
                        }
                    }
                    for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                        array_splice($array_resultat,$array_eliminar[$j],1);
                    }
                    
                    $file_name = "despeses_sstt_reparador_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades','Codi SSTT', 'Nom SSTT', 'Diners (€)'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    $grafic = true;
                    $labels = "";
                    $title = "Despeses per SSTT (reparador)";
                    $dades = "";
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Despeses per',
                            $row['id_sstt'], 
                            $row['nom_sstt'], 
                            str_replace('.', ',', $row['total_preu']),
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                        $labels .= "'" . $row['nom_sstt'] . "',";
                        $dades .= "'" . $row['total_preu'] . "',";
                    }
                    fclose($file);

                } else if ($id_tipus_dispositiu == "tots_separats") {

                    $array_resultat = $tiquet_model->sumTiquetsTotsTipusSSTTReparador();
                    $array_eliminar = [];
                    for ($i = 0; $i < sizeof($array_resultat); $i++) {
                        if ($role == "admin_sstt") {
                            $pertany_sstt = $array_resultat[$i]['id_sstt'] == $id_sstt;
                            if (!$pertany_sstt) {
                                array_push($array_eliminar, $i);
                            }
                        }
                    }
                    for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                        array_splice($array_resultat,$array_eliminar[$j],1);
                    }
                    
                    $file_name = "despeses_sstt_reparador_tipus_dispositiu_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades','Codi SSTT', 'Nom SSTT', 'Nom tipus dispositiu', 'Diners (€)'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Despeses per',
                            $row['id_sstt'], 
                            $row['nom_sstt'], 
                            $row['nom_tipus_dispositiu'],
                            str_replace('.', ',', $row['total_preu']),
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                    }
                    fclose($file);
                    
                } else {

                    $array_resultat = $tiquet_model->sumTiquetsTipusSSTTReparador($id_tipus_dispositiu);
                    $array_eliminar = [];
                    for ($i = 0; $i < sizeof($array_resultat); $i++) {
                        if ($role == "admin_sstt") {
                            $pertany_sstt = $array_resultat[$i]['id_sstt'] == $id_sstt;
                            if (!$pertany_sstt) {
                                array_push($array_eliminar, $i);
                            }
                        }
                    }
                    for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                        array_splice($array_resultat,$array_eliminar[$j],1);
                    }
                    
                    $file_name = "despeses_sstt_reparador_". strtolower($tipus_dispositiu_model->obtenirTipusDispositiuPerId($id_tipus_dispositiu)['nom_tipus_dispositiu']) ."_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades','Codi SSTT', 'Nom SSTT', 'Nom tipus dispositiu', 'Diners (€)'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    $grafic = true;
                    $labels = "";
                    $title = "Despeses per SSTT (reparador) - " . strtolower($tipus_dispositiu_model->obtenirTipusDispositiuPerId($id_tipus_dispositiu)['nom_tipus_dispositiu']);
                    $dades = "";
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Despeses per',
                            $row['id_sstt'], 
                            $row['nom_sstt'], 
                            $row['nom_tipus_dispositiu'],
                            str_replace('.', ',', $row['total_preu']),
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                        $labels .= "'" . $row['nom_sstt'] . "',";
                        $dades .= "'" . $row['total_preu'] . "',";
                    }
                    fclose($file);

                }

            } elseif ($tipus_actor == "total") {

                if ($role != "desenvolupador") {
                    return redirect()->back();
                }

                if ($id_tipus_dispositiu == "sense") {

                    $array_resultat = $tiquet_model->sumTiquetsSenseTipusTOTAL();
                    
                    $file_name = "despeses_total_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades', 'Diners (€)'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Despeses per',
                            str_replace('.', ',', $row['total_preu']),
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                    }
                    fclose($file);

                } else if ($id_tipus_dispositiu == "tots_separats") {
                    
                    $array_resultat = $tiquet_model->sumTiquetsTotsTipusTOTAL();
                    
                    $file_name = "despeses_total_tipus_dispositiu_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades', 'Nom tipus dispositiu', 'Diners (€)'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    $grafic = true;
                    $labels = "";
                    $title = "Despesesper tipus de dispositiu";
                    $dades = "";
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Despeses per',
                            $row['nom_tipus_dispositiu'],
                            str_replace('.', ',', $row['total_preu']),
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                        $labels .= "'" . $row['nom_tipus_dispositiu'] . "',";
                        $dades .= "'" . $row['total_preu'] . "',";
                    }
                    fclose($file);

                } else {

                    $array_resultat = $tiquet_model->sumTiquetsTipusTOTAL($id_tipus_dispositiu);
                    
                    $file_name = "despeses_total_". strtolower($tipus_dispositiu_model->obtenirTipusDispositiuPerId($id_tipus_dispositiu)['nom_tipus_dispositiu']) ."_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades', 'Nom tipus dispositiu', 'Diners (€)'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Despeses per',
                            $row['nom_tipus_dispositiu'],
                            str_replace('.', ',', $row['total_preu']),
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                    }
                    fclose($file);

                }

            }

        } else if ($tipus_dades == "nombre_finalitzats_temps") {

            $estat = $this->request->getPost("estat");

            if ($estat == "finalitzats") {
                $id_estat = "tots";
            } elseif ($estat == "retornats") {
                $id_estat = 9;
            } elseif ($estat == "desguassats") {
                $id_estat = 11;
            } elseif ($estat == "rebutjats") {
                $id_estat = 10;
            }

            if ($tipus_actor == "centre_reparador") {

                if ($id_estat == "tots") {

                    $array_resultat = $tiquet_model->countNombreDispositiusTempsCentreReparador();
                    $array_eliminar = [];
                    for ($i = 0; $i < sizeof($array_resultat); $i++) {
                        if ($role == "admin_sstt") {
                            $pertany_sstt = $poblacio_model->getPoblacio($array_resultat[$i]['id_poblacio'])['id_sstt'] == $id_sstt;
                            if (!$pertany_sstt) {
                                array_push($array_eliminar, $i);
                            }
                        }
                    }
                    for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                        array_splice($array_resultat,$array_eliminar[$j],1);
                    }
                    
                    $file_name = "nombre_finalitzats_centre_reparador_temps_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades', 'Codi centre', 'Nom centre', 'Any', 'Mes', 'Nombre dispositius'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Nombre dispositius finalitzats per',
                            $row['codi_centre_reparador'], 
                            $row['nom_centre'], 
                            $row['any'], 
                            $row['mes'], 
                            $row['num_tiquets'],
                        ];
                        fputcsv($file, $data, ';');
                        array_push($array_visualitzar,$data); 
                    }
                    fclose($file);

                } else {

                    $array_resultat = $tiquet_model->countNombreDispositiusTempsCentreReparadorEstat($id_estat);
                    $array_eliminar = [];
                    for ($i = 0; $i < sizeof($array_resultat); $i++) {
                        if ($role == "admin_sstt") {
                            $pertany_sstt = $poblacio_model->getPoblacio($array_resultat[$i]['id_poblacio'])['id_sstt'] == $id_sstt;
                            if (!$pertany_sstt) {
                                array_push($array_eliminar, $i);
                            }
                        }
                    }
                    for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                        array_splice($array_resultat,$array_eliminar[$j],1);
                    }
                    
                    $file_name = "nombre_". $estat ."_centre_reparador_temps_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades', 'Codi centre', 'Nom centre', 'Any', 'Mes', 'Nombre dispositius'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Nombre dispositius '. $estat .' per',
                            $row['codi_centre_reparador'], 
                            $row['nom_centre'], 
                            $row['any'], 
                            $row['mes'], 
                            $row['num_tiquets'],
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                    }
                    fclose($file);

                }

            } else if ($tipus_actor == "sstt") {

                if ($id_estat == "tots") {

                    $array_resultat = $tiquet_model->countNombreDispositiusTempsSSTT();
                    $array_eliminar = [];
                    for ($i = 0; $i < sizeof($array_resultat); $i++) {
                        if ($role == "admin_sstt") {
                            $pertany_sstt = $poblacio_model->getPoblacio($array_resultat[$i]['id_poblacio'])['id_sstt'] == $id_sstt;
                            if (!$pertany_sstt) {
                                array_push($array_eliminar, $i);
                            }
                        }
                    }
                    for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                        array_splice($array_resultat,$array_eliminar[$j],1);
                    }
                    
                    $file_name = "nombre_finalitzats_sstt_temps_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades', 'Codi SSTT', 'Nom SSTT', 'Any', 'Mes', 'Nombre dispositius'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    if ($role == "admin_sstt") {
                        $grafic2 = true;
                        $labels = "";
                        $title = "Nombre de dispositius finalitzats";
                        $dades = "";
                        $temps = [];
                        $num_tiquets = [];
                    }
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Nombre dispositius finalitzats per',
                            $row['id_sstt'], 
                            $row['nom_sstt'], 
                            $row['any'], 
                            $row['mes'], 
                            $row['num_tiquets'],
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                        if ($role == "admin_sstt") {
                            array_push($temps, [$row['mes'], $row['any']]);
                            array_push($num_tiquets, $row['num_tiquets']);
                        }
                    }
                    fclose($file);

                } else {

                    $array_resultat = $tiquet_model->countNombreDispositiusTempsSSTTEstat($id_estat);
                    $array_eliminar = [];
                    for ($i = 0; $i < sizeof($array_resultat); $i++) {
                        if ($role == "admin_sstt") {
                            $pertany_sstt = $poblacio_model->getPoblacio($array_resultat[$i]['id_poblacio'])['id_sstt'] == $id_sstt;
                            if (!$pertany_sstt) {
                                array_push($array_eliminar, $i);
                            }
                        }
                    }
                    for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                        array_splice($array_resultat,$array_eliminar[$j],1);
                    }
                    
                    $file_name = "nombre_". $estat ."_sstt_temps_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades', 'Codi SSTT', 'Nom SSTT', 'Any', 'Mes', 'Nombre dispositius'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    if ($role == "admin_sstt") {
                        $grafic2 = true;
                        $labels = "";
                        $title = "Nombre de dispositius finalitzats";
                        $dades = "";
                        $temps = [];
                        $num_tiquets = [];
                    }
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Nombre dispositius '. $estat .' per',
                            $row['id_sstt'], 
                            $row['nom_sstt'], 
                            $row['any'], 
                            $row['mes'], 
                            $row['num_tiquets'],
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                        if ($role == "admin_sstt") {
                            array_push($temps, [$row['mes'], $row['any']]);
                            array_push($num_tiquets, $row['num_tiquets']);
                        }
                    }
                    fclose($file);

                }

            } else if ($tipus_actor == "total") {

                if ($role != "desenvolupador") {
                    return redirect()->back();
                }

                if ($id_estat == "tots") {

                    $array_resultat = $tiquet_model->countNombreDispositiusTempsTOTAL();
                
                    $file_name = "nombre_finalitzats_total_temps_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades', 'Any', 'Mes', 'Nombre dispositius'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    $grafic2 = true;
                    $labels = "";
                    $title = "Nombre de dispositius finalitzats";
                    $dades = "";
                    $temps = [];
                    $num_tiquets = [];
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Nombre dispositius finalitzats per',
                            $row['any'], 
                            $row['mes'], 
                            $row['num_tiquets'],
                        ];
                        fputcsv($file, $data, ';');
                        array_push($array_visualitzar,$data);
                        array_push($temps, [$row['mes'], $row['any']]);
                        array_push($num_tiquets, $row['num_tiquets']);
                    }
                    fclose($file);

                } else {

                    $array_resultat = $tiquet_model->countNombreDispositiusTempsTOTALEstat($id_estat);
                
                    $file_name = "nombre_". $estat ."_total_temps_" . date('Ymd') . '.csv';
                    $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                    
                    $file = fopen($file_path, 'w');
                    fwrite($file, "\xEF\xBB\xBF");
                    $header = ['Dades', 'Any', 'Mes', 'Nombre dispositius'];
                    $array_visualitzar = [];
                    array_push($array_visualitzar,$header);
                    $grafic2 = true;
                    $labels = "";
                    $title = "Nombre de dispositius " . $estat;
                    $dades = "";
                    $temps = [];
                    $num_tiquets = [];
                    fputcsv($file, $header, ';'); 
                    foreach ($array_resultat as $row) {
                        $data = [
                            'Nombre dispositius '. $estat .' per',
                            $row['any'], 
                            $row['mes'], 
                            $row['num_tiquets'],
                        ];
                        fputcsv($file, $data, ';'); 
                        array_push($array_visualitzar,$data);
                        array_push($temps, [$row['mes'], $row['any']]);
                        array_push($num_tiquets, $row['num_tiquets']);
                    }
                    fclose($file);

                }

            }
        
        } else if ($tipus_dades == "nombre_emesos_temps") {
        
            if ($tipus_actor == "centre_emissor") {

                $array_resultat = $tiquet_model->countTiquetsTempsCentreEmissor();
                $array_eliminar = [];
                for ($i = 0; $i < sizeof($array_resultat); $i++) {
                    if ($role == "admin_sstt") {
                        $pertany_sstt = $poblacio_model->getPoblacio($array_resultat[$i]['id_poblacio'])['id_sstt'] == $id_sstt;
                        if (!$pertany_sstt) {
                            array_push($array_eliminar, $i);
                        }
                    }
                }
                for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                    array_splice($array_resultat,$array_eliminar[$j],1);
                }
                
                $file_name = "nombre_tiquets_emesos_centre_emissor_temps_" . date('Ymd') . '.csv';
                $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                
                $file = fopen($file_path, 'w');
                fwrite($file, "\xEF\xBB\xBF");
                $header = ['Dades', 'Codi centre', 'Nom centre', 'Any', 'Mes', 'Nombre dispositius'];
                $array_visualitzar = [];
                array_push($array_visualitzar,$header);
                fputcsv($file, $header, ';'); 
                foreach ($array_resultat as $row) {
                    $data = [
                        'Nombre tiquets emesos per',
                        $row['codi_centre_emissor'], 
                        $row['nom_centre'], 
                        $row['any'], 
                        $row['mes'], 
                        $row['num_tiquets'],
                    ];
                    fputcsv($file, $data, ';'); 
                    array_push($array_visualitzar,$data);
                }
                fclose($file);

            } else if ($tipus_actor == "sstt") {

                $array_resultat = $tiquet_model->countTiquetsTempsSSTT();
                $array_eliminar = [];
                for ($i = 0; $i < sizeof($array_resultat); $i++) {
                    if ($role == "admin_sstt") {
                        $pertany_sstt = $poblacio_model->getPoblacio($array_resultat[$i]['id_poblacio'])['id_sstt'] == $id_sstt;
                        if (!$pertany_sstt) {
                            array_push($array_eliminar, $i);
                        }
                    }
                }
                for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                    array_splice($array_resultat,$array_eliminar[$j],1);
                }
                
                $file_name = "nombre_tiquets_emesos_sstt_temps_" . date('Ymd') . '.csv';
                $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                
                $file = fopen($file_path, 'w');
                fwrite($file, "\xEF\xBB\xBF");
                $header = ['Dades', 'Codi centre', 'Nom centre', 'Any', 'Mes', 'Nombre dispositius'];
                $array_visualitzar = [];
                array_push($array_visualitzar,$header);
                if ($role == "admin_sstt") {
                    $grafic2 = true;
                    $labels = "";
                    $title = "Nombre de tiquets emesos";
                    $dades = "";
                    $temps = [];
                    $num_tiquets = [];
                }
                fputcsv($file, $header, ';'); 
                foreach ($array_resultat as $row) {
                    $data = [
                        'Nombre tiquets emesos per',
                        $row['id_sstt'], 
                        $row['nom_sstt'], 
                        $row['any'], 
                        $row['mes'], 
                        $row['num_tiquets'],
                    ];
                    fputcsv($file, $data, ';'); 
                    array_push($array_visualitzar,$data);
                    if ($role == "admin_sstt") {
                        array_push($temps, [$row['mes'], $row['any']]);
                        array_push($num_tiquets, $row['num_tiquets']);
                    }
                }
                fclose($file);

            } else if ($tipus_actor == "total") {

                if ($role != "desenvolupador") {
                    return redirect()->back();
                }

                $array_resultat = $tiquet_model->countTiquetsTempsTOTAL();
                
                $file_name = "nombre_tiquets_emesos_total_temps_" . date('Ymd') . '.csv';
                $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                
                $file = fopen($file_path, 'w');
                fwrite($file, "\xEF\xBB\xBF");
                $header = ['Dades', 'Any', 'Mes', 'Nombre dispositius'];
                $array_visualitzar = [];
                array_push($array_visualitzar,$header);
                $grafic2 = true;
                $labels = "";
                $title = "Nombre de tiquets emesos";
                $dades = "";
                $temps = [];
                $num_tiquets = [];
                fputcsv($file, $header, ';'); 
                foreach ($array_resultat as $row) {
                    $data = [
                        'Nombre tiquets emesos per',
                        $row['any'], 
                        $row['mes'], 
                        $row['num_tiquets'],
                    ];
                    fputcsv($file, $data, ';'); 
                    array_push($array_visualitzar,$data);
                    array_push($temps, [$row['mes'], $row['any']]);
                    array_push($num_tiquets, $row['num_tiquets']);
                }
                fclose($file);

            }
        
        } else if ($tipus_dades == "despeses_temps") {
        
            if ($tipus_actor == "centre_reparador") {

                $array_resultat = $tiquet_model->sumTiquetsTempsCentreReparador();
                $array_eliminar = [];
                for ($i = 0; $i < sizeof($array_resultat); $i++) {
                    if ($role == "admin_sstt") {
                        $pertany_sstt = $poblacio_model->getPoblacio($array_resultat[$i]['id_poblacio'])['id_sstt'] == $id_sstt;
                        if (!$pertany_sstt) {
                            array_push($array_eliminar, $i);
                        }
                    }
                }
                for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                    array_splice($array_resultat,$array_eliminar[$j],1);
                }
                
                $file_name = "despeses_centre_reparador_temps_" . date('Ymd') . '.csv';
                $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                
                $file = fopen($file_path, 'w');
                fwrite($file, "\xEF\xBB\xBF");
                $header = ['Dades', 'Codi centre', 'Nom centre', 'Any', 'Mes', 'Diners (€)'];
                $array_visualitzar = [];
                array_push($array_visualitzar,$header);
                fputcsv($file, $header, ';'); 
                foreach ($array_resultat as $row) {
                    $data = [
                        'Despeses per',
                        $row['codi_centre_reparador'], 
                        $row['nom_centre'], 
                        $row['any'], 
                        $row['mes'], 
                        str_replace('.', ',', $row['total_preu']),
                    ];
                    fputcsv($file, $data, ';'); 
                    array_push($array_visualitzar,$data);
                }
                fclose($file);

            } else if ($tipus_actor == "sstt") {

                $array_resultat = $tiquet_model->sumTiquetsTempsSSTT();
                $array_eliminar = [];
                for ($i = 0; $i < sizeof($array_resultat); $i++) {
                    if ($role == "admin_sstt") {
                        $pertany_sstt = $poblacio_model->getPoblacio($array_resultat[$i]['id_poblacio'])['id_sstt'] == $id_sstt;
                        if (!$pertany_sstt) {
                            array_push($array_eliminar, $i);
                        }
                    }
                }
                for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                    array_splice($array_resultat,$array_eliminar[$j],1);
                }
                
                $file_name = "despeses_sstt_temps_" . date('Ymd') . '.csv';
                $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                
                $file = fopen($file_path, 'w');
                fwrite($file, "\xEF\xBB\xBF");
                $header = ['Dades', 'Codi SSTT', 'Nom SSTT', 'Any', 'Mes', 'Diners (€)'];
                $array_visualitzar = [];
                array_push($array_visualitzar,$header);
                if ($role == "admin_sstt") {
                    $grafic2 = true;
                    $labels = "";
                    $title = "Despeses";
                    $dades = "";
                    $temps = [];
                    $num_tiquets = [];
                }
                fputcsv($file, $header, ';'); 
                foreach ($array_resultat as $row) {
                    $data = [
                        'Despeses per',
                        $row['id_sstt'], 
                        $row['nom_sstt'], 
                        $row['any'], 
                        $row['mes'], 
                        str_replace('.', ',', $row['total_preu']),
                    ];
                    fputcsv($file, $data, ';'); 
                    array_push($array_visualitzar,$data);
                    if ($role == "admin_sstt") {
                        array_push($temps, [$row['mes'], $row['any']]);
                        array_push($num_tiquets, $row['total_preu']);
                    }
                }
                fclose($file);

            } else if ($tipus_actor == "total") {

                if ($role != "desenvolupador") {
                    return redirect()->back();
                }

                $array_resultat = $tiquet_model->sumTiquetsTempsTOTAL();
                
                $file_name = "despeses_total_temps_" . date('Ymd') . '.csv';
                $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                
                $file = fopen($file_path, 'w');
                fwrite($file, "\xEF\xBB\xBF");
                $header = ['Dades', 'Any', 'Mes', 'Diners (€)'];
                $array_visualitzar = [];
                array_push($array_visualitzar,$header);
                $grafic2 = true;
                $labels = "";
                $title = "Despeses";
                $dades = "";
                $temps = [];
                $num_tiquets = [];
                fputcsv($file, $header, ';'); 
                foreach ($array_resultat as $row) {
                    $data = [
                        'Despeses per',
                        $row['any'], 
                        $row['mes'], 
                        str_replace('.', ',', $row['total_preu']),
                    ];
                    fputcsv($file, $data, ';'); 
                    array_push($array_visualitzar,$data);
                    array_push($temps, [$row['mes'], $row['any']]);
                    array_push($num_tiquets, $row['total_preu']);
                }
                fclose($file);

            }
        
        }

    
        if ($mode == "descarregar") {

            header('Content-Description: File Transfer');
            header('Content-Type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename=' . basename($file_path));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_path));
    
            readfile($file_path);

        } else if ($mode == "visualitzar") {


            $taula = '<table class="table table-striped row-border dataTable no-footer" id="data-list-vista_inventari" style="width: 100%;" aria-describedby="data-list-vista_inventari_info">';

            for ($i = 0; $i < sizeof($array_visualitzar); $i++) {
                if ($i == 0) {
                    $taula .= "<thead><tr>";
                } else if ($i == 1) {
                    $taula .= "</thead><tbody><tr>";
                } else {
                    $taula .= "<tr>";
                }

                foreach ($array_visualitzar[$i] as $valor) {
                    if ($i == 0) {
                        $taula .= '<th class="sorting align-middle">';
                        $taula .= $valor;
                        $taula .= '</th>';
                    } else {
                        $taula .= '<td class="odd">';
                        $taula .= $valor;
                        $taula .= '</td>';
                    }
                }
                $taula .= "</tr>";
            }

            $taula .= '</tbody><table>';

            if ($grafic) {
                $data = [
                    'labels' => $labels,
                    'title' => $title,
                    'dades' => $dades
                ];
                session()->setFlashdata("grafic", $data);
            }

            if ($grafic2) {
                
                $nou_temps = [];
                $nou_num_tiquets = [];

                // Funció per incrementar un mes i gestionar el canvi d'any
                function incrementarMes($mes, $any) {
                    $mes++;
                    if ($mes > 12) {
                        $mes = 1;
                        $any++;
                    }
                    return [$mes, $any];
                }

                list($mes_inici, $any_inici) = $temps[0];
                list($mes_final, $any_final) = end($temps);

                $current_index = 0;
                $current_mes = $mes_inici;
                $current_any = $any_inici;

                while ($current_any < $any_final || ($current_any == $any_final && $current_mes <= $mes_final)) {

                    if ($current_index < count($temps) && $temps[$current_index] == [$current_mes, $current_any]) {
                        $nou_temps[] = $temps[$current_index];
                        $nou_num_tiquets[] = $num_tiquets[$current_index];
                        $current_index++;
                    } else {
                        $nou_temps[] = [$current_mes, $current_any];
                        $nou_num_tiquets[] = 0;
                    }
                    
                    list($current_mes, $current_any) = incrementarMes($current_mes, $current_any);
                }

                $labels_string = "";
                $dades_string = "";

                for ($i = 0; $i < sizeof($nou_temps); $i++) {
                    $labels_string .= "'" . $nou_temps[$i][0] . "-" . $nou_temps[$i][1] . "',";
                    $dades_string .= "'" . $nou_num_tiquets[$i] . "',";
                }

                $data = [
                    'labels' => $labels_string,
                    'title' => $title,
                    'dades' => $dades_string
                ];
                session()->setFlashdata("grafic2", $data);
            }


            session()->setFlashdata("taula", $taula);
            return redirect()->to(base_url('/dades'))->withInput();
        }

        
        unlink($file_path);

        if ($mode == "descarregar") {
            exit;
        }

    }

}
