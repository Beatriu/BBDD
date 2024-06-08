<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TiquetModel;
use App\Models\TipusDispositiuModel;
use SIENSIS\KpaCrud\Libraries\KpaCrud;

class TipusDispositiuController extends BaseController
{
    public function registreTipusDispositiu($id_tipus_dispositiu_desactivar = null)
    {
        $tipus_dispositiu_model = new TipusDispositiuModel();

        $role = session()->get('user_data')['role'];
        $data['role'] = $role;

        if ($role != "desenvolupador") {
            return redirect()->to(base_url('/tiquets'));
        }

        // KPACRUD TIPUS DISPOSITIU
        $data['title'] = 'Tipus Dispositiu';

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
        $crud->setTable('tipus_dispositiu');
        $crud->setPrimaryKey('id_tipus_dispositiu');
        $crud->hideHeadLink([
            'js-bootstrap',
            'css-bootstrap',
        ]);
        $crud->setColumns([
            'id_tipus_dispositiu',
            'nom_tipus_dispositiu',
        ]);
        $crud->setColumnsInfo([
            'id_tipus_dispositiu' => [
                'name' => lang('tipus.id_tipus_dispositiu')
            ],
            'nom_tipus_dispositiu' => [
                'name' => lang('tipus.nom_tipus_dispositiu')
            ],
        ]);
        $crud->addItemLink('delete', 'fa-trash', base_url('tipus/dispositiu/desactivar'), 'Desactivar Tipus Dispositiu');
        $crud->addWhere('actiu', "1");

        $data['output'] = $crud->render();

        $data['tipus_dispositiu_desactivar'] = null;
        $data['tipus_inventari_desactivar'] = null;
        $data['tipus_intervencio_desactivar'] = null;
        $data['curs_desactivar'] = null;
        $data['poblacio_desactivar'] = null;
        $data['comarca_desactivar'] = null;
        if ($id_tipus_dispositiu_desactivar != null) {

            $tipus_dispositiu_desactivar = $tipus_dispositiu_model->obtenirTipusDispositiuPerId($id_tipus_dispositiu_desactivar);
            
            if ($tipus_dispositiu_desactivar != null) {
                $data['tipus_dispositiu_desactivar'] = $tipus_dispositiu_desactivar;
            }
        }

        $data['tipus_pantalla'] = "tipus_dispositiu";
        return view('registres' . DIRECTORY_SEPARATOR . 'registreTipus', $data);
    }

    public function crearTipusDispositiu_post()
    {
        $tipus_dispositiu_model = new TipusDispositiuModel();

        $actor = session()->get('user_data');
        $role = $actor['role'];

        if ($role != "desenvolupador") {
            return redirect()->to(base_url('/tiquets'));
        }

        $tipus_dispositiu = $this->request->getPost("tipus_dispositiu");
        $tipus_dispositiu_no_existeix = $tipus_dispositiu_model->obtenirTipusDispositiuPerNom($tipus_dispositiu) == null;
        if ($tipus_dispositiu != null && $tipus_dispositiu != "" && $tipus_dispositiu_no_existeix) {
            $tipus_dispositiu_model->addTipusDispositiu($tipus_dispositiu);
            $msg = lang("alertes.tipus_dispositiu_creat");
            session()->setFlashdata("tipus_dispositiu_creat", $msg);
        } elseif ($tipus_dispositiu == null || $tipus_dispositiu == "") {
            $msg = lang("alertes.tipus_dispositiu_buit");
            session()->setFlashdata("tipus_dispositiu_buit", $msg);
            return redirect()->back()->withInput();
        } else if (!$tipus_dispositiu_no_existeix) {

            $tipus_dispositiu_existent = $tipus_dispositiu_model->obtenirTipusDispositiuPerNom($tipus_dispositiu);
            if ($tipus_dispositiu_existent['actiu'] == "1") {
                $msg = lang("alertes.tipus_dispositiu_existeix");
                session()->setFlashdata("tipus_dispositiu_existeix", $msg);
                return redirect()->back()->withInput();
            } else {
                $msg = lang("alertes.tipus_dispositiu_activat");
                session()->setFlashdata("tipus_dispositiu_activat", $msg);
                $tipus_dispositiu_model->editarTipusDispositiuActiu($tipus_dispositiu_existent['id_tipus_dispositiu'], "1");
            }

        }

        $data['tipus_pantalla'] = "tipus_dispositiu";
        return redirect()->to(base_url('/tipus/dispositiu'));
    }

    public function desactivarTipusDispositiu($id_tipus_dispositiu) {

        $tipus_dispositiu_model = new TipusDispositiuModel();
        $tiquet_model = new TiquetModel();

        $actor = session()->get('user_data');
        $role = $actor['role'];

        if ($role != "desenvolupador") {
            return redirect()->to(base_url('/tiquets'));
        }

        $tipus_dispositiu_desactivar = $tipus_dispositiu_model->obtenirTipusDispositiuPerId($id_tipus_dispositiu);
            
        if ($tipus_dispositiu_desactivar != null) {

            if ($tiquet_model->obtenirTiquetTipusDispositiu($id_tipus_dispositiu) != null) {
                $tipus_dispositiu_model->editarTipusDispositiuActiu($id_tipus_dispositiu, "0");
                $msg = lang("alertes.tipus_dispositiu_desactivat");
                session()->setFlashdata("tipus_dispositiu_desactivat", $msg);
            } else {
                $tipus_dispositiu_model->esborrarTipusDispositiu($id_tipus_dispositiu);
                $msg = lang("alertes.tipus_dispositiu_esborrat");
                session()->setFlashdata("tipus_dispositiu_esborrat", $msg);
            }

        }

        $data['tipus_pantalla'] = "tipus_dispositiu";
        return redirect()->to(base_url('/tipus/dispositiu'));
    }
}
