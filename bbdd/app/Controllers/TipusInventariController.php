<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\InventariModel;
use App\Models\TipusInventariModel;
use SIENSIS\KpaCrud\Libraries\KpaCrud;

class TipusInventariController extends BaseController
{
    public function registreTipusInventari($id_tipus_inventari_desactivar = null)
    {
        $tipus_inventari_model = new TipusInventariModel();

        $role = session()->get('user_data')['role'];
        $data['role'] = $role;

        if ($role != "desenvolupador") {
            return redirect()->to(base_url('/tiquets'));
        }

        // KPACRUD INVENTARI TIPUS INVENTARI
        $data['title'] = 'Tipus Inventari';

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
        $crud->setTable('tipus_inventari');
        $crud->setPrimaryKey('id_tipus_inventari');
        $crud->hideHeadLink([
            'js-bootstrap',
            'css-bootstrap',
        ]);
        $crud->setColumns([
            'id_tipus_inventari',
            'nom_tipus_inventari',
        ]);
        $crud->setColumnsInfo([
            'id_tipus_inventari' => [
                'name' => lang('inventari.id_inventari')
            ],
            'nom_tipus_inventari' => [
                'name' => lang('inventari.nom_tipus_inventari')
            ],
        ]);
        $crud->addItemLink('delete', 'fa-trash', base_url('tipus/inventari/desactivar'), 'Desactivar Tipus Inventari');
        $crud->addWhere('actiu', "1");

        $data['output'] = $crud->render();

        $data['tipus_inventari_desactivar'] = null;
        $data['tipus_intervencio_desactivar'] = null;
        if ($id_tipus_inventari_desactivar != null) {

            $tipus_inventari_desactivar = $tipus_inventari_model->obtenirTipusInventariPerId($id_tipus_inventari_desactivar);
            
            if ($tipus_inventari_desactivar != null) {
                $data['tipus_inventari_desactivar'] = $tipus_inventari_desactivar;
            }
        }

        $data['tipus_pantalla'] = "tipus_inventari";
        return view('registres' . DIRECTORY_SEPARATOR . 'registreTipus', $data);
    }

    public function crearTipusInventari_post()
    {
        $tipus_inventari_model = new TipusInventariModel();

        $actor = session()->get('user_data');
        $role = $actor['role'];

        if ($role != "desenvolupador") {
            return redirect()->to(base_url('/tiquets'));
        }

        $tipus_inventari = $this->request->getPost("tipus_inventari");
        $tipus_inventari_no_existeix = $tipus_inventari_model->obtenirTipusInventariPerNom($tipus_inventari) == null;
        if ($tipus_inventari != null && $tipus_inventari != "" && $tipus_inventari_no_existeix) {
            $tipus_inventari_model->addTipusInventari($tipus_inventari);
            $msg = lang("alertes.tipus_inventari_creat");
            session()->setFlashdata("tipus_inventari_creat", $msg);
        } elseif ($tipus_inventari == null || $tipus_inventari == "") {
            $msg = lang("alertes.tipus_inventari_buit");
            session()->setFlashdata("tipus_inventari_buit", $msg);
            return redirect()->back()->withInput();
        } else if (!$tipus_inventari_no_existeix) {

            $tipus_inventari_existent = $tipus_inventari_model->obtenirTipusInventariPerNom($tipus_inventari);
            if ($tipus_inventari_existent['actiu'] == "1") {
                $msg = lang("alertes.tipus_inventari_existeix");
                session()->setFlashdata("tipus_inventari_existeix", $msg);
                return redirect()->back()->withInput();
            } else {
                $msg = lang("alertes.tipus_inventari_activat");
                session()->setFlashdata("tipus_inventari_activat", $msg);
                $tipus_inventari_model->editarTipusInventariActiu($tipus_inventari_existent['id_tipus_inventari'], "1");
            }

        }

        $data['tipus_pantalla'] = "tipus_inventari";
        return redirect()->to(base_url('/tipus/inventari'));
    }

    public function desactivarTipusInventari($id_tipus_inventari) {

        $tipus_inventari_model = new TipusInventariModel();
        $inventari_model = new InventariModel();

        $actor = session()->get('user_data');
        $role = $actor['role'];

        if ($role != "desenvolupador") {
            return redirect()->to(base_url('/tiquets'));
        }

        $tipus_inventari_desactivar = $tipus_inventari_model->obtenirTipusInventariPerId($id_tipus_inventari);
            
        if ($tipus_inventari_desactivar != null) {

            if ($inventari_model->obtenirInventariTipusInventari($id_tipus_inventari) != null) {
                $tipus_inventari_model->editarTipusInventariActiu($id_tipus_inventari, "0");
                $msg = lang("alertes.tipus_inventari_desactivat");
                session()->setFlashdata("tipus_inventari_desactivat", $msg);
            } else {
                $tipus_inventari_model->esborrarTipusInventari($id_tipus_inventari);
                $msg = lang("alertes.tipus_inventari_esborrat");
                session()->setFlashdata("tipus_inventari_esborrat", $msg);
            }

        }

        $data['tipus_pantalla'] = "tipus_inventari";
        return redirect()->to(base_url('/tipus/inventari'));
    }
}
