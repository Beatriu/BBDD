<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\IntervencioModel;
use App\Models\TipusIntervencioModel;
use App\Models\TipusInventariModel;
use SIENSIS\KpaCrud\Libraries\KpaCrud;

class TipusIntervencioController extends BaseController
{
    public function registreTipusIntervencio($id_tipus_intervencio_desactivar = null)
    {
        $tipus_intervencio_model = new TipusIntervencioModel();

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
        $crud->setTable('tipus_intervencio');
        $crud->setPrimaryKey('id_tipus_intervencio');
        $crud->hideHeadLink([
            'js-bootstrap',
            'css-bootstrap',
        ]);
        $crud->setColumns([
            'id_tipus_intervencio',
            'nom_tipus_intervencio',
        ]);
        $crud->setColumnsInfo([
            'id_tipus_intervencio' => [
                'name' => lang('tipus.id_tipus_intervencio')
            ],
            'nom_tipus_intervencio' => [
                'name' => lang('tipus.nom_tipus_intervencio')
            ],
        ]);
        $crud->addItemLink('delete', 'fa-trash', base_url('tipus/intervencio/desactivar'), 'Desactivar Tipus Intervencio');
        $crud->addWhere('actiu', "1");

        $data['output'] = $crud->render();

        $data['tipus_inventari_desactivar'] = null;
        $data['tipus_intervencio_desactivar'] = null;
        if ($id_tipus_intervencio_desactivar != null) {

            $tipus_intervencio_desactivar = $tipus_intervencio_model->obtenirNomTipusIntervencio($id_tipus_intervencio_desactivar);
            
            if ($tipus_intervencio_desactivar != null) {
                $data['tipus_intervencio_desactivar'] = $tipus_intervencio_desactivar;
            }
        }

        $data['tipus_pantalla'] = "tipus_intervencio";
        return view('registres' . DIRECTORY_SEPARATOR . 'registreTipus', $data);
    }

    public function crearTipusIntervencio_post()
    {
        $tipus_intervencio_model = new TipusIntervencioModel();

        $actor = session()->get('user_data');
        $role = $actor['role'];

        if ($role != "desenvolupador") {
            return redirect()->to(base_url('/tiquets'));
        }

        $tipus_intervencio = $this->request->getPost("tipus_intervencio");
        $tipus_intervencio_no_existeix = $tipus_intervencio_model->obtenirTipusIntervencioPerNom($tipus_intervencio) == null;

        if ($tipus_intervencio != null && $tipus_intervencio != "" && $tipus_intervencio_no_existeix) {
            $tipus_intervencio_model->addTipusIntervencio($tipus_intervencio);
        } elseif ($tipus_intervencio == null || $tipus_intervencio == "") {
            $msg = lang("alertes.tipus_intervencio_buit");
            session()->setFlashdata("tipus_intervencio_buit", $msg);
            return redirect()->back()->withInput();
        } else if (!$tipus_intervencio_no_existeix) {
            $msg = lang("alertes.tipus_intervencio_existeix");
            session()->setFlashdata("tipus_intervencio_existeix", $msg);
            return redirect()->back()->withInput();
        }

        $data['tipus_pantalla'] = "tipus_intervencio";
        return redirect()->to(base_url('/tipus/intervencio'));
    }

    public function desactivarTipusIntervencio($id_tipus_intervencio) {

        $tipus_intervencio_model = new TipusIntervencioModel();
        $intervencio_model = new IntervencioModel();

        $actor = session()->get('user_data');
        $role = $actor['role'];

        if ($role != "desenvolupador") {
            return redirect()->to(base_url('/tiquets'));
        }

        $tipus_intervencio_desactivar = $tipus_intervencio_model->obtenirNomTipusIntervencio($id_tipus_intervencio);
            
        if ($tipus_intervencio_desactivar != null) {

            if ($intervencio_model->obtenirInventariTipusInventari($id_tipus_intervencio) != null) {
                $tipus_intervencio_model->editarTipusInventariActiu($id_tipus_intervencio, "0");
                $msg = lang("alertes.tipus_intervencio_desactivat");
                session()->setFlashdata("tipus_intervencio_desactivat", $msg);
            } else {
                $tipus_intervencio_model->esborrarTipusInventari($id_tipus_intervencio);
                $msg = lang("alertes.tipus_intervencio_esborrat");
                session()->setFlashdata("tipus_intervencio_esborrat", $msg);
            }

        }

        $data['tipus_pantalla'] = "tipus_intervencio";
        return redirect()->to(base_url('/tipus/intervencio'));
    }
}
