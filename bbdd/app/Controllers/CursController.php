<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CursModel;
use App\Models\IntervencioModel;
use SIENSIS\KpaCrud\Libraries\KpaCrud;

class CursController extends BaseController
{
    public function registreCurs($id_curs_desactivar = null)
    {
        $curs_model = new CursModel();

        $role = session()->get('user_data')['role'];
        $data['role'] = $role;

        if ($role != "desenvolupador") {
            return redirect()->to(base_url('/tiquets'));
        }

        // KPACRUD CURS
        $data['title'] = 'Curs';

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
        $crud->setTable('curs');
        $crud->setPrimaryKey('id_curs');
        $crud->hideHeadLink([
            'js-bootstrap',
            'css-bootstrap',
        ]);
        $crud->setColumns([
            'id_curs',
            'curs',
            'cicle',
            'titol',
        ]);
        $crud->setColumnsInfo([
            'id_curs' => [
                'name' => lang('tipus.id_curs')
            ],
            'curs' => [
                'name' => lang('tipus.curs')
            ],
            'cicle' => [
                'name' => lang('tipus.cicle')
            ],
            'titol' => [
                'name' => lang('tipus.titol')
            ],
        ]);
        $crud->addItemLink('delete', 'fa-trash', base_url('tipus/curs/desactivar'), 'Desactivar Curs');
        $crud->addWhere('actiu', "1");

        $data['output'] = $crud->render();

        $data['tipus_dispositiu_desactivar'] = null;
        $data['tipus_inventari_desactivar'] = null;
        $data['tipus_intervencio_desactivar'] = null;
        $data['curs_desactivar'] = null;
        $data['poblacio_desactivar'] = null;
        $data['comarca_desactivar'] = null;
        if ($id_curs_desactivar != null) {

            $curs_desactivar = $curs_model->obtenirCursosPerId($id_curs_desactivar);
            
            if ($curs_desactivar != null) {
                $data['curs_desactivar'] = $curs_desactivar;
            }
        }

        $data['tipus_pantalla'] = "curs";
        return view('registres' . DIRECTORY_SEPARATOR . 'registreTipus', $data);
    }

    public function crearCurs_post()
    {
        $curs_model = new CursModel();

        $actor = session()->get('user_data');
        $role = $actor['role'];

        if ($role != "desenvolupador") {
            return redirect()->to(base_url('/tiquets'));
        }

        // TODO Revisar aquesta part
        $curs = $this->request->getPost("curs");
        $cicle = $this->request->getPost("cicle");
        $titol = $this->request->getPost("titol");

        if ($curs == "" || $curs == null || $titol == "" || $titol == null || $cicle == "" || $cicle == null) {
            return redirect()->back()->withInput();
        }

        $curs_no_existeix = $curs_model->obtenirCursosPerCursTitolCicle($cicle, $titol, $curs) == null;

        if ($curs != null && $curs != "" && $curs_no_existeix) {
            $curs_model->addCurs($cicle, $titol, $curs);
            $msg = lang("alertes.curs_creat");
            session()->setFlashdata("curs_creat", $msg);
        } elseif ($curs == null || $curs == "") {
            $msg = lang("alertes.curs_buit");
            session()->setFlashdata("curs_buit", $msg);
            return redirect()->back()->withInput();
        } else if (!$curs_no_existeix) {

            $curs_existent = $curs_model->obtenirCursosPerCursTitolCicle($cicle, $titol, $curs)[0];
            if ($curs_existent['actiu'] == "1") {
                $msg = lang("alertes.curs_existeix");
                session()->setFlashdata("curs_existeix", $msg);
                return redirect()->back()->withInput();
            } else {
                $msg = lang("alertes.curs_activat");
                session()->setFlashdata("curs_activat", $msg);
                $curs_model->editarCursActiu($curs_existent['id_curs'], "1");
            }

        }

        $data['tipus_pantalla'] = "curs";
        return redirect()->to(base_url('/tipus/curs'));
    }

    public function desactivarCurs($id_curs) {

        $curs_model = new CursModel();
        $intervencio_model = new IntervencioModel();

        $actor = session()->get('user_data');
        $role = $actor['role'];

        if ($role != "desenvolupador") {
            return redirect()->to(base_url('/tiquets'));
        }

        $curs_desactivar = $curs_model->obtenirCursosPerId($id_curs);
            
        if ($curs_desactivar != null) {

            if ($intervencio_model->obtenirIntervencioCurs($id_curs) != null) {
                $curs_model->editarCursActiu($id_curs, "0");
                $msg = lang("alertes.curs_desactivat");
                session()->setFlashdata("curs_desactivat", $msg);
            } else {
                $curs_model->esborrarCurs($id_curs);
                $msg = lang("alertes.curs_esborrat");
                session()->setFlashdata("curs_esborrat", $msg);
            }

        }

        $data['tipus_pantalla'] = "curs";
        return redirect()->to(base_url('/tipus/curs'));
    }
}
