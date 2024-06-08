<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PoblacioModel;
use App\Models\CentreModel;
use App\Models\ComarcaModel;
use App\Models\SSTTModel;
use SIENSIS\KpaCrud\Libraries\KpaCrud;

class PoblacioController extends BaseController
{
    public function registrePoblacio($id_poblacio_desactivar = null)
    {
        $poblacio_model = new PoblacioModel();
        $comarca_model = new ComarcaModel();
        $sstt_model = new SSTTModel();

        $role = session()->get('user_data')['role'];
        $data['role'] = $role;

        if ($role != "desenvolupador") {
            return redirect()->to(base_url('/tiquets'));
        }

        // KPACRUD POBLACIÓ
        $data['title'] = 'Població';

        $crud = new KpaCrud();
        $crud->setConfig('onlyView');
        $crud->setConfig([
            "numerate" => false,
            "add_button" => false,
            "show_button" => false,
            "recycled_button" => false,
            "useSoftDeletes" => true,
            "multidelete" => false,
            "filterable" => true,
            "editable" => false,
            "removable" => false,
            "paging" => false,
            "numerate" => false,
            "sortable" => true,
            "exportXLS" => false,
            "print" => false
        ]);
        $crud->setTable('poblacio');
        $crud->setPrimaryKey('id_poblacio');
        $crud->hideHeadLink([
            'js-bootstrap',
            'css-bootstrap',
        ]);
        $crud->setRelation('id_comarca', 'comarca', 'id_comarca', 'nom_comarca');
        $crud->setRelation('id_sstt', 'sstt', 'id_sstt', 'nom_sstt');
        $crud->setColumns([
            'id_poblacio',
            'codi_postal',
            'nom_poblacio',
            'comarca__nom_comarca',
            'sstt__nom_sstt',
        ]);
        $crud->setColumnsInfo([
            'id_poblacio' => [
                'name' => lang('tipus.id_poblacio')
            ],
            'codi_postal' => [
                'name' => lang('tipus.codi_postal')
            ],
            'nom_poblacio' => [
                'name' => lang('tipus.nom_poblacio')
            ],
            'comarca__nom_comarca' => [
                'name' => lang('tipus.nom_comarca')
            ],
            'sstt__nom_sstt' => [
                'name' => lang('tipus.nom_sstt'),
            ]
        ]);
        
        $crud->addItemLink('delete', 'fa-trash', base_url('tipus/poblacio/desactivar'), 'Desactivar Població');
        $crud->addWhere('poblacio.actiu', "1");


        $data['output'] = $crud->render();

        $data['tipus_dispositiu_desactivar'] = null;
        $data['tipus_inventari_desactivar'] = null;
        $data['tipus_intervencio_desactivar'] = null;
        $data['curs_desactivar'] = null;
        $data['poblacio_desactivar'] = null;
        $data['comarca_desactivar'] = null;
        if ($id_poblacio_desactivar != null) {

            $poblacio_desactivar = $poblacio_model->getPoblacio($id_poblacio_desactivar);
            
            if ($poblacio_desactivar != null) {
                $data['poblacio_desactivar'] = $poblacio_desactivar;
            }
        }

        $array_comarques = $comarca_model->obtenirComarques();
        
        $data['comarques'] = "";
        for ($i = 0; $i < sizeof($array_comarques); $i++){
            if ($array_comarques[$i]['actiu'] == "1") {
                $data['comarques'] .= "<option value = \"" . $array_comarques[$i]['id_comarca'] . " - " . $array_comarques[$i]['nom_comarca'] . "\">";
                $data['comarques'] .=  $array_comarques[$i]['nom_comarca'] . "</option>";
            }
        }

        $array_sstt = $sstt_model->obtenirSSTT();
        $data['sstt'] = "";
        for ($i = 0; $i < sizeof($array_sstt); $i++){
            //if ($array_sstt[$i]['actiu'] == "1") {
                $data['sstt'] .= "<option value = \"" . $array_sstt[$i]['id_sstt'] . " - " . $array_sstt[$i]['nom_sstt'] . "\">";
                $data['sstt'] .=  $array_sstt[$i]['nom_sstt'] . "</option>";
            //}
        }
        

        $data['tipus_pantalla'] = "poblacio";
        return view('registres' . DIRECTORY_SEPARATOR . 'registreTipus', $data);
    }

    public function crearPoblacio_post()
    {
        $poblacio_model = new PoblacioModel();
        $comarca_model = new ComarcaModel();

        $actor = session()->get('user_data');
        $role = $actor['role'];

        if ($role != "desenvolupador") {
            return redirect()->to(base_url('/tiquets'));
        }

        $id_poblacio = $this->request->getPost("id_poblacio");
        $codi_postal = $this->request->getPost("codi_postal");
        $poblacio = $this->request->getPost("nom_poblacio");
        $id_comarca = $this->request->getPost("id_comarca");
        $id_sstt = $this->request->getPost("id_sstt");

        $id_comarca = trim(explode('-', (string) $id_comarca)[0]);
        if ($id_comarca != null && $comarca_model->obtenirComarca($id_comarca) == null) {
            $msg = lang('alertes.no_existeix_comarca');
            session()->setFlashdata('no_existeix_comarca', $msg);
            return redirect()->back()->withInput();
        }

        $id_sstt = trim(explode('-', (string) $id_sstt)[0]);
        if ($id_comarca != null && $comarca_model->obtenirComarca($id_comarca) == null) {
            $msg = lang('alertes.no_existeix_sstt');
            session()->setFlashdata('no_existeix_sstt', $msg);
            return redirect()->back()->withInput();
        }

        $poblacio_no_existeix = $poblacio_model->obtenirPoblacioPerIdCodiPoblacio($id_poblacio, $codi_postal, $poblacio) == null;
        if ($poblacio != null && $poblacio != "" && $poblacio_no_existeix) {

            if ($id_poblacio != null && $id_poblacio != "" && $poblacio_model->getPoblacio($id_poblacio) == null && $poblacio_model->obtenirPoblacioCodiPostal($codi_postal) == null) {
                $poblacio_model->addPoblacio($id_poblacio, $codi_postal, $poblacio, $id_comarca, $id_sstt);
                $msg = lang("alertes.poblacio_creat");
                session()->setFlashdata("poblacio_creat", $msg);
            } else {
                $msg = lang("alertes.poblacio_existeix");
                session()->setFlashdata("poblacio_existeix", $msg);
                return redirect()->back()->withInput();
            }

        } elseif ($poblacio == null || $poblacio == "") {
            $msg = lang("alertes.poblacio_buit");
            session()->setFlashdata("poblacio_buit", $msg);
            return redirect()->back()->withInput();
        } else if (!$poblacio_no_existeix) {

            $poblacio_existent = $poblacio_model->obtenirPoblacioPerIdCodiPoblacio($id_poblacio, $codi_postal, $poblacio);
            if ($poblacio_existent['actiu'] == "1") {
                $msg = lang("alertes.poblacio_existeix");
                session()->setFlashdata("poblacio_existeix", $msg);
                return redirect()->back()->withInput();
            } else {
                $msg = lang("alertes.poblacio_activat");
                session()->setFlashdata("poblacio_activat", $msg);
                $poblacio_model->editarPoblacioActiu($poblacio_existent['id_poblacio'], "1");
            }

        }

        $data['tipus_pantalla'] = "poblacio";
        return redirect()->to(base_url('/tipus/poblacio'));
    }

    public function desactivarPoblacio($id_poblacio) {

        $poblacio_model = new PoblacioModel();
        $centre_model = new CentreModel();

        $actor = session()->get('user_data');
        $role = $actor['role'];

        if ($role != "desenvolupador") {
            return redirect()->to(base_url('/tiquets'));
        }

        $poblacio_desactivar = $poblacio_model->getPoblacio($id_poblacio);
            
        if ($poblacio_desactivar != null) {

            if ($centre_model->obtenirCentrePoblacio($id_poblacio) != null) {
                $poblacio_model->editarPoblacioActiu($id_poblacio, "0");
                $msg = lang("alertes.poblacio_desactivat");
                session()->setFlashdata("poblacio_desactivat", $msg);
            } else {
                $poblacio_model->esborrarPoblacio($id_poblacio);
                $msg = lang("alertes.poblacio_esborrat");
                session()->setFlashdata("poblacio_esborrat", $msg);
            }

        }

        $data['tipus_pantalla'] = "poblacio";
        return redirect()->to(base_url('/tipus/poblacio'));
    }
}
