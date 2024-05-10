<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CentreModel;
use App\Models\TipusDispositiuModel;
use App\Models\TiquetModel;
use CodeIgniter\HTTP\ResponseInterface;
use SIENSIS\KpaCrud\Libraries\KpaCrud;

class TiquetController extends BaseController
{
    public function viewTiquet($id_tiquet)
    {
        $tiquet_model = new TiquetModel();
        $centre_model = new CentreModel();
        $tipus_dispositiu_model = new TipusDispositiuModel();

        $actor = session()->get('user_data');
        $role = $actor['role'];

        $tiquet_existent = $tiquet_model->getTiquetById($id_tiquet);

        if ($tiquet_existent != null) {
            
            if ($role == "centre_emissor" || $role == "centre_reparador") {

                return redirect()->to(base_url('/registreTiquet'));
    
            } else {
    
                $data['title'] = "Veure Tiquet";
                $data['id_tiquet'] = $id_tiquet;
        
                $crud = new KpaCrud();
                $crud->setConfig('onlyView');
                $crud->setConfig([
                    "numerate" => false,
                    "add_button" => false,
                    "show_button" => false,
                    "recycled_button" => false,
                    "useSoftDeletes" => true,
                    "multidelete" => false,
                    "filterable" => false,
                    "editable" => false,
                    "removable" => false,
                    "paging" => false,
                    "numerate" => false,
                    "sortable" => true,
                    "exportXLS" => false,
                    "print" => false
                ]);
                $crud->setTable('vista_intervencio');
                $crud->setPrimaryKey('id_intervencio');
                $crud->setColumns([
                    'id_intervencio',
                    'nom_tipus_intervencio',
                    'descripcio_intervencio_limitada',
                    'correu_alumne',
                    'id_xtec'
                ]);
                $crud->setColumnsInfo([
                    'id_intervencio' => [
                        'name' => lang('intervencio.id_intervencio')
                    ],
                    'nom_tipus_intervencio' => [
                        'name' => lang('intervencio.nom_tipus_intervencio')
                    ],
                    'descripcio_intervencio_limitada' => [
                        'name' => lang('intervencio.descripcio_intervencio_limitada')
                    ],
                    'correu_alumne' => [
                        'name' => lang('alumne.correu_alumne')
                    ],
                    'id_xtec' => [
                        'name' => lang('intervencio.id_xtec')
                    ],
                ]);
                $crud->addWhere('id_tiquet', $id_tiquet);
        
                $tiquets_resultat = [];
                if ($role == "professor" || $role == "alumne") {
    
                    $tiquets_resultat = $tiquet_model->getTiquetByCodiCentreReparador($actor['codi_centre']);
    
    
                } else if ($role == "sstt" || $role == "admin_sstt") {
    
                    $tiquets = $tiquet_model->getTiquets();
    
                    for ($i = 0; $i < sizeof($tiquets); $i++) {
                        $id_sstt_tiquet =  $centre_model->obtenirCentre($tiquets[$i]['codi_centre_reparador'])['id_sstt'];
                        
                        if ($id_sstt_tiquet == $actor['id_sstt']) {
                            $nom_tipus_dispositiu = $tipus_dispositiu_model->getNomTipusDispositiu($tiquets[$i]['id_tipus_dispositiu'])['nom_tipus_dispositiu'];
                            $tiquets[$i]['nom_tipus_dispositiu'] = $nom_tipus_dispositiu;
                            array_push($tiquets_resultat, $tiquets[$i]);
                        }
                    }
                }
    
                
                $options_tiquets = "";
                for ($i = 0; $i < sizeof($tiquets_resultat); $i++) {
                    $options_tiquets .= "<option value=\"" . $tiquets_resultat[$i]['id_tiquet'] . " // " . $tiquets_resultat[$i]['nom_tipus_dispositiu'] . " // "  . $tiquets_resultat[$i]['codi_equip'] . "\">";
                    $options_tiquets .= $tiquets_resultat[$i]['codi_equip'] . " // " . $tiquets_resultat[$i]['nom_tipus_dispositiu'];
                    $options_tiquets .= "</option>";
                }
    
                $data['options_tiquets'] = $options_tiquets;
    
                $data['output'] = $crud->render();
                return view('tiquet' . DIRECTORY_SEPARATOR . 'vistaTiquet', $data);
    
            }

        } else {

            return redirect()->to('registreTiquet');

        }


    }

    public function viewTiquet_post() {
        $input = $this->request->getPost('tiquet_seleccionat');

        $id_tiquet = trim(explode('//', (string) $input)[0]);

        return redirect()->to(base_url('/vistaTiquet/' . $id_tiquet));
    }
}
