<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ComarcaModel;
use App\Models\PoblacioModel;
use SIENSIS\KpaCrud\Libraries\KpaCrud;

class ComarcaController extends BaseController
{
    public function registreComarca($id_comarca_desactivar = null)
    {
        $comarca_model = new ComarcaModel();

        $role = session()->get('user_data')['role'];
        $data['role'] = $role;

        if ($role != "desenvolupador") {
            return redirect()->to(base_url('/tiquets'));
        }

        // KPACRUD COMARCA
        $data['title'] = 'Comarques';

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
        $crud->setTable('comarca');
        $crud->setPrimaryKey('id_comarca');
        $crud->hideHeadLink([
            'js-bootstrap',
            'css-bootstrap',
        ]);
        $crud->setColumns([
            'id_comarca',
            'nom_comarca',
        ]);
        $crud->setColumnsInfo([
            'id_comarca' => [
                'name' => lang('tipus.id_comarca')
            ],
            'nom_comarca' => [
                'name' => lang('tipus.nom_comarca')
            ],
        ]);
        $crud->addItemLink('delete', 'fa-trash', base_url('tipus/comarca/desactivar'), 'Desactivar Comarca');
        $crud->addWhere('actiu', "1");

        $data['output'] = $crud->render();

        $data['tipus_dispositiu_desactivar'] = null;
        $data['tipus_inventari_desactivar'] = null;
        $data['tipus_intervencio_desactivar'] = null;
        $data['curs_desactivar'] = null;
        $data['poblacio_desactivar'] = null;
        $data['comarca_desactivar'] = null;
        if ($id_comarca_desactivar != null) {

            $comarca_desactivar = $comarca_model->obtenirComarcaPerId($id_comarca_desactivar);
            
            if ($comarca_desactivar != null) {
                $data['comarca_desactivar'] = $comarca_desactivar;
            }
        }

        $data['tipus_pantalla'] = "comarca";
        return view('registres' . DIRECTORY_SEPARATOR . 'registreTipus', $data);
    }

    public function crearComarca_post()
    {
        $comarca_model = new ComarcaModel();

        $actor = session()->get('user_data');
        $role = $actor['role'];

        if ($role != "desenvolupador") {
            return redirect()->to(base_url('/tiquets'));
        }

        $id_comarca = $this->request->getPost("id_comarca");
        $comarca = $this->request->getPost("nom_comarca");
        $comarca_no_existeix = $comarca_model->obtenirComarcaPerIdNom($id_comarca, $comarca) == null;
        if ($comarca != null && $comarca != "" && $comarca_no_existeix) {
            
            if ($id_comarca != null && $id_comarca != "" && $comarca_model->obtenirComarcaPerId($id_comarca) == null) {
                $comarca_model->addComarca($id_comarca, $comarca);
                $msg = lang("alertes.comarca_creat");
                session()->setFlashdata("comarca_creat", $msg);
            } else {
                $msg = lang("alertes.comarca_existeix");
                session()->setFlashdata("comarca_existeix", $msg);
                return redirect()->back()->withInput();
            }

        } elseif ($comarca == null || $comarca == "") {
            $msg = lang("alertes.comarca_buit");
            session()->setFlashdata("comarca_buit", $msg);
            return redirect()->back()->withInput();
        } else if (!$comarca_no_existeix) {

            $comarca_existent = $comarca_model->obtenirComarcaPerIdNom($id_comarca, $comarca);
            if ($comarca_existent['actiu'] == "1") {
                $msg = lang("alertes.comarca_existeix");
                session()->setFlashdata("comarca_existeix", $msg);
                return redirect()->back()->withInput();
            } else {
                $msg = lang("alertes.comarca_activat");
                session()->setFlashdata("comarca_activat", $msg);
                $comarca_model->editarComarcaActiu($comarca_existent['id_comarca'], "1");
            }

        }

        $data['tipus_pantalla'] = "comarca";
        return redirect()->to(base_url('/tipus/comarca'));
    }

    public function desactivarComarca($id_comarca) {

        $comarca_model = new ComarcaModel();
        $poblacio_model = new PoblacioModel();

        $actor = session()->get('user_data');
        $role = $actor['role'];

        if ($role != "desenvolupador") {
            return redirect()->to(base_url('/tiquets'));
        }

        $comarca_desactivar = $comarca_model->obtenirComarcaPerId($id_comarca);
            
        if ($comarca_desactivar != null) {

            if ($poblacio_model->obtenirPoblacioComarca($id_comarca) != null) {
                $comarca_model->editarComarcaActiu($id_comarca, "0");
                $msg = lang("alertes.comarca_desactivat");
                session()->setFlashdata("comarca_desactivat", $msg);
            } else {
                $comarca_model->esborrarComarca($id_comarca);
                $msg = lang("alertes.comarca_esborrat");
                session()->setFlashdata("comarca_esborrat", $msg);
            }

        }

        $data['tipus_pantalla'] = "comarca";
        return redirect()->to(base_url('/tipus/comarca'));
    }
}
