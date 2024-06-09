<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CentreModel;
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
            
            $data['tipus_actor_nombre_emesos'] = "\"<option value='centre_emissor' >". lang('dades.centre_emissor') ."</option><option value='poblacio' >". lang('dades.poblacio') ."</option><option  value='sstt' >". lang('dades.sstt') ."</option>\"";

            $data['tipus_actor_despeses'] = "\"<option value='centre_emissor' >". lang('dades.centre_emissor') ."</option><option value='centre_reparador' >". lang('dades.centre_reparador') ."</option><option value='poblacio' >". lang('dades.poblacio') ."</option><option  value='sstt_emissor' >". lang('dades.sstt_emissor') ."</option><option value='sstt_reparador' >". lang('dades.sstt_reparador') ."</option>\"";
        } else if ($role == "desenvolupador") {
            $data['tipus_actor_nombre_finalitzats'] = "\"<option value='centre_reparador' >". lang('dades.centre_reparador') ."</option><option  value='sstt' >". lang('dades.sstt') ."</option><option  value='total' >". lang('dades.total') ."</option>\"";
            
            $data['tipus_actor_nombre_emesos'] = "\"<option value='centre_emissor' >". lang('dades.centre_emissor') ."</option><option value='poblacio' >". lang('dades.poblacio') ."</option><option value='comarca' >". lang('dades.comarca') ."</option><option  value='sstt' >". lang('dades.sstt') ."</option><option  value='total' >". lang('dades.total') ."</option>\"";

            $data['tipus_actor_despeses'] = "\"<option value='centre_emissor' >". lang('dades.centre_emissor') ."</option><option value='centre_reparador' >". lang('dades.centre_reparador') ."</option><option value='poblacio' >". lang('dades.poblacio') ."</option><option value='comarca' >". lang('dades.comarca') ."</option><option  value='sstt_emissor' >". lang('dades.sstt_emissor') ."</option><option value='sstt_reparador' >". lang('dades.sstt_reparador') ."</option><option  value='total' >". lang('dades.total') ."</option>\"";
        }

        return view('registres' . DIRECTORY_SEPARATOR . 'registreDades', $data);
    }

    public function descarregarDades()
    {
        $tiquet_model = new TiquetModel();
        $centre_model = new CentreModel();
        $tipus_dispositiu_model = new TipusDispositiuModel();
        $sstt_model = new SSTTModel();

        $actor = session()->get('user_data');
        $role = $actor['role'];

        if ($role == "admin_sstt") {
            $id_sstt = $actor['id_sstt'];
        }

        if ($role != "desenvolupador" && $role != "admin_sstt") {
            return redirect()->to(base_url('/tiquets'));
        }

        $tipus_dades = $this->request->getPost("tipus_dades");

        if ($tipus_dades == "nombre_finalitzats") {
            $estat = $this->request->getPost("estat");
            $tipus_actor = $this->request->getPost("tipus_actor");
            $tipus_dispositius = $this->request->getPost("tipus_dispositiu");

            if ($estat == "finalitzats") {
                $id_estat = "tots";
            } elseif ($estat == "retornats") {
                $id_estat = 9;
            } elseif ($estat == "desguassats") {
                $id_estat = 11;
            } elseif ($estat == "rebutjats") {
                $id_estat = 10;
            }
            
            if ($tipus_dispositius == "sense") {
                $id_tipus_dispositiu = "sense";
            } elseif ($tipus_dispositius == "tots_separats") {
                $id_tipus_dispositiu = "tots_separats";
            } else {
                $id_tipus_dispositiu = $tipus_dispositius;
            }


            if ($tipus_actor == "centre_reparador") {

                if ($id_tipus_dispositiu == "sense") {

                    if ($id_estat == "tots") {

                        $array_resultat = $tiquet_model->countNombreDispositiusTotsEstatsSenseTipus();
                        $array_eliminar = [];
                        for ($i = 0; $i < sizeof($array_resultat); $i++) {
                            if ($role == "admin_sstt") {
                                $pertany_sstt = $array_resultat[$i]['id_sstt'] == $id_sstt;
                                if ($pertany_sstt) {
                                    $array_resultat[$i]['nom_centre'] = $centre_model->obtenirCentre($array_resultat[$i]['codi_centre_reparador'])['nom_centre'];
                                } else {
                                    //array_splice($array_net,$i,1);
                                    array_push($array_eliminar, $i);
                                }
                            } else if ($role == "desenvolupador") {
                                $array_resultat[$i]['nom_centre'] = $centre_model->obtenirCentre($array_resultat[$i]['codi_centre_reparador'])['nom_centre'];
                            }
                        }
                        for ($j = 0; $j < sizeof($array_eliminar); $j++) {
                            array_splice($array_resultat,$array_eliminar[$j],1);
                        }
                        
                        $file_name = "nombre_dispositius_finalitzats_" . date('Ymd') . '.csv';
                        $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                        
                        $file = fopen($file_path, 'w');
                        $header = ['Dades','Codi centre', 'Nom centre', 'Nombre dispositius'];
                        fputcsv($file, $header, ';'); 
                        foreach ($array_resultat as $row) {
                            $data = [
                                'Nombre de dispositius finalitzats per',
                                $row['codi_centre_reparador'], 
                                $row['nom_centre'], 
                                $row['num_tiquets']
                            ];
                            fputcsv($file, $data, ';'); 
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
                                    //array_splice($array_net,$i,1);
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
                        $header = ['Dades','Codi centre', 'Nom centre', 'Nombre dispositius'];
                        fputcsv($file, $header, ';'); 
                        foreach ($array_resultat as $row) {
                            $data = [
                                'Nombre de dispositius ' . $estat . ' per',
                                $row['codi_centre_reparador'], 
                                $row['nom_centre'], 
                                $row['num_tiquets']
                            ];
                            fputcsv($file, $data, ';'); 
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
                                    //array_splice($array_net,$i,1);
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
                        $header = ['Dades','Codi centre', 'Nom centre', 'Nom tipus dispositiu', 'Nombre dispositius'];
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
                                    //array_splice($array_net,$i,1);
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
                        $header = ['Dades','Codi centre', 'Nom centre', 'Nom tipus dispositiu', 'Nombre dispositius'];
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
                                    //array_splice($array_net,$i,1);
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
                        $header = ['Dades','Codi centre', 'Nom centre', 'Nom tipus dispositiu', 'Nombre dispositius'];
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
                                    //array_splice($array_net,$i,1);
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
                        $header = ['Dades','Codi centre', 'Nom centre', 'Nom tipus dispositiu', 'Nombre dispositius'];
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
                                    //array_splice($array_net,$i,1);
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
                        $header = ['Dades','Codi SSTT', 'Nom SSTT', 'Nombre dispositius'];
                        fputcsv($file, $header, ';'); 
                        foreach ($array_resultat as $row) {
                            $data = [
                                'Nombre de dispositius finalitzats per',
                                $row['id_sstt'], 
                                $row['nom_sstt'], 
                                $row['num_tiquets']
                            ];
                            fputcsv($file, $data, ';'); 
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
                                    //array_splice($array_net,$i,1);
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
                        $header = ['Dades','Codi SSTT', 'Nom SSTT', 'Nombre dispositius'];
                        fputcsv($file, $header, ';'); 
                        foreach ($array_resultat as $row) {
                            $data = [
                                'Nombre de dispositius ' . $estat . ' per',
                                $row['id_sstt'], 
                                $row['nom_sstt'], 
                                $row['num_tiquets']
                            ];
                            fputcsv($file, $data, ';'); 
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
                                    //array_splice($array_net,$i,1);
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
                        $header = ['Dades','Codi SSTT', 'Nom SSTT', 'Nom tipus dispositiu', 'Nombre dispositius'];
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
                                    //array_splice($array_net,$i,1);
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
                        $header = ['Dades','Codi SSTT', 'Nom SSTT', 'Nom tipus dispositiu', 'Nombre dispositius'];
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
                                    //array_splice($array_net,$i,1);
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
                        $header = ['Dades','Codi SSTT', 'Nom SSTT', 'Nom tipus dispositiu', 'Nombre dispositius'];
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
                                    //array_splice($array_net,$i,1);
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
                        $header = ['Dades','Codi SSTT', 'Nom SSTT', 'Nom tipus dispositiu', 'Nombre dispositius'];
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
                        $header = ['Dades', 'Nombre dispositius'];
                        fputcsv($file, $header, ';'); 
                        foreach ($array_resultat as $row) {
                            $data = [
                                'Nombre de dispositius finalitzats',
                                $row['num_tiquets']
                            ];
                            fputcsv($file, $data, ';'); 
                        }
                        fclose($file);
                        
                    } else {

                        $array_resultat = $tiquet_model->countNombreDispositiusEstatSenseTipusTOTAL($id_estat);
                        
                        $file_name = "nombre_dispositius_". $estat . "_" . date('Ymd') . '.csv';
                        $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;
                        
                        $file = fopen($file_path, 'w');
                        $header = ['Dades', 'Nombre dispositius'];
                        fputcsv($file, $header, ';'); 
                        foreach ($array_resultat as $row) {
                            $data = [
                                'Nombre de dispositius ' . $estat,
                                $row['num_tiquets']
                            ];
                            fputcsv($file, $data, ';'); 
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
                        $header = ['Dades','Nom tipus dispositiu','Nombre dispositius'];
                        fputcsv($file, $header, ';'); 
                        foreach ($array_resultat as $row) {
                            $data = [
                                'Nombre de dispositius finalitzats',
                                $row['nom_tipus_dispositiu'],
                                $row['num_tiquets']
                            ];
                            fputcsv($file, $data, ';'); 
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
                        $header = ['Dades', 'Nom tipus dispositiu', 'Nombre dispositius'];
                        fputcsv($file, $header, ';'); 
                        foreach ($array_resultat as $row) {
                            $data = [
                                'Nombre de dispositius ' . $estat,
                                $row['nom_tipus_dispositiu'],
                                $row['num_tiquets']
                            ];
                            fputcsv($file, $data, ';'); 
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
                        $header = ['Dades', 'Nom tipus dispositiu', 'Nombre dispositius'];
                        fputcsv($file, $header, ';'); 
                        foreach ($array_resultat as $row) {
                            $data = [
                                'Nombre de dispositius finalitzats',
                                $row['nom_tipus_dispositiu'],
                                $row['num_tiquets']
                            ];
                            fputcsv($file, $data, ';'); 
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
                        $header = ['Dades', 'Nom tipus dispositiu', 'Nombre dispositius'];
                        fputcsv($file, $header, ';'); 
                        foreach ($array_resultat as $row) {
                            $data = [
                                'Nombre de dispositius '. $estat,
                                $row['nom_tipus_dispositiu'],
                                $row['num_tiquets']
                            ];
                            fputcsv($file, $data, ';'); 
                        }
                        fclose($file);

                    }
                }

            }


        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename=' . basename($file_path));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));
        
        readfile($file_path);
        
        unlink($file_path);
        exit;
    }

}
