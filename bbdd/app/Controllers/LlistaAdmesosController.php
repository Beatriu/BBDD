<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CentreModel;
use App\Models\LlistaAdmesosModel;
use App\Models\LoginInRolModel;
use App\Models\LoginModel;
use App\Models\ProfessorModel;
use SIENSIS\KpaCrud\Libraries\KpaCrud;

class LlistaAdmesosController extends BaseController
{
    public function registreLlistaAdmesos($id_llista_admesos_desactivar = null)
    {
        $llista_admesos_model = new LlistaAdmesosModel();
        $centre_model = new CentreModel();

        $role = session()->get('user_data')['role'];
        $data['role'] = $role;

        if ($role != "desenvolupador" && $role != "admin_sstt") {
            return redirect()->to(base_url('/tiquets'));
        }

        // KPACRUD LLISTA ADMESOS
        $data['title'] = 'Professors';

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
        $crud->setTable('llista_admesos');
        $crud->setPrimaryKey('correu_professor');
        $crud->hideHeadLink([
            'js-bootstrap',
            'css-bootstrap',
        ]);
        $crud->setRelation('codi_centre', 'centre', 'codi_centre', 'nom_centre');
        $crud->setColumns([
            'correu_professor',
            'data_entrada',
            'codi_centre',
            'centre__nom_centre'
        ]);
        $crud->setColumnsInfo([
            'correu_professor' => [
                'name' => lang('tipus.correu_professor')
            ],
            'data_entrada' => [
                'name' => lang('tipus.data_entrada')
            ],
            'codi_centre' => [
                'name' => lang('tipus.codi_centre')
            ],
            'centre__nom_centre' => [
                'name' => lang('tipus.nom_centre')
            ],
        ]);
        $crud->addItemLink('delete', 'fa-trash', base_url('professor/desactivar'), 'Desactivar Professor');
        $crud->addWhere('actiu', "1");

        $data['output'] = $crud->render();

        $data['llista_admesos_desactivar'] = null;
        $data['eliminar_tots'] = null;
        if ($id_llista_admesos_desactivar != null) {

            if ($id_llista_admesos_desactivar == "tots") {
                $data['eliminar_tots'] = "tots";
            } else {
                $llista_admesos_desactivar = $llista_admesos_model->existeixProfessor($id_llista_admesos_desactivar);
            
                if ($llista_admesos_desactivar != null) {
                    $data['llista_admesos_desactivar'] = $llista_admesos_desactivar;
                }
            }

        }

        $array_centres = $centre_model->obtenirCentres();
        
        $data['centres'] = "";
        for ($i = 0; $i < sizeof($array_centres); $i++){
            //if ($array_centres[$i]['actiu'] == "1") {
                $data['centres'] .= "<option value = \"" . $array_centres[$i]['codi_centre'] . " - " . $array_centres[$i]['nom_centre'] . "\">";
                $data['centres'] .=  $array_centres[$i]['nom_centre'] . "</option>";
            //}
        }

        $data['tipus_pantalla'] = "llista_admesos";
        return view('registres' . DIRECTORY_SEPARATOR . 'registreLlistaAdmesos', $data);
    }

    public function crearLlistaAdmesos_post()
    {
        $llista_admesos_model = new LlistaAdmesosModel();
        $centre_model = new CentreModel();
        $professor_model = new ProfessorModel();
        $login_model = new LoginModel();
        $login_in_rol_model = new LoginInRolModel();

        $actor = session()->get('user_data');
        $role = $actor['role'];

        if ($role != "desenvolupador" && $role != "admin_sstt") {
            return redirect()->to(base_url('/tiquets'));
        }

        $correu_professor = $this->request->getPost("correu_professor");
        $data_entrada = $this->request->getPost("data_entrada");
        $codi_centre = $this->request->getPost("codi_centre");
        $codi_centre = trim(explode('-', (string) $codi_centre)[0]);

        if ($codi_centre != null && $centre_model->obtenirCentre($codi_centre) == null) {
            $msg = lang('alertes.centre_no_existeix');
            session()->setFlashdata('centre_no_existeix', $msg);
            return redirect()->back()->withInput();
        }

        $llista_admesos_no_existeix = $llista_admesos_model->existeixProfessor($correu_professor) == null;
        if ($correu_professor != null && $correu_professor != "" && $llista_admesos_no_existeix) {
            
            $llista_admesos_model->addLlistaAdmesos($correu_professor, $data_entrada, $codi_centre);
            
            $id_xtec = explode('@', (string) $correu_professor)[0];
            if ($professor_model->obtenirProfessor($correu_professor) == null) {
                $professor_model->addProfessor($id_xtec, $id_xtec, "SENSE", $correu_professor, $codi_centre);
            } else {
                $professor_model->editarProfessorCodiCentre($id_xtec, $codi_centre);
            }

            $login_model->addLogin($correu_professor, null);
            $id_login = $login_model->obtenirId($correu_professor);
            $login_in_rol_model->addLoginInRol($id_login, 2);
            
            $msg = lang("alertes.llista_admesos_creat");
            session()->setFlashdata("llista_admesos_creat", $msg);
        } elseif ($correu_professor == null || $correu_professor == "") {
            $msg = lang("alertes.llista_admesos_buit");
            session()->setFlashdata("llista_admesos_buit", $msg);
            return redirect()->back()->withInput();
        } else if (!$llista_admesos_no_existeix) {
            $msg = lang("alertes.llista_admesos_existeix");
            session()->setFlashdata("llista_admesos_existeix", $msg);
            return redirect()->back()->withInput();
        }

        $data['tipus_pantalla'] = "llista_admesos";
        return redirect()->to(base_url('/professor'));
    }

    public function desactivarLlistaAdmesos($correu_professor) {

        $llista_admesos_model = new LlistaAdmesosModel();
        $login_in_rol_model = new LoginInRolModel();
        $login_model = new LoginModel();

        $actor = session()->get('user_data');
        $role = $actor['role'];

        if ($role != "desenvolupador" && $role != "admin_sstt") {
            return redirect()->to(base_url('/tiquets'));
        }

        $llista_admesos_desactivar = $llista_admesos_model->existeixProfessor($correu_professor);
        if ($llista_admesos_desactivar != null) {
            $llista_admesos_model->esborrarLlistaAdmesos($correu_professor);
            $msg = lang("alertes.llista_admesos_esborrat");
            session()->setFlashdata("llista_admesos_esborrat", $msg);

            $id_login = $login_model->obtenirId($correu_professor);
            $login_in_rol_model->deleteLoginInRol($id_login);
            $login_model->deleteLogin($id_login);
        }

        $data['tipus_pantalla'] = "llista_admesos";
        return redirect()->to(base_url('/professor'));
    }

    public function desactivarTotsLlistaAdmesos()
    {
        $actor = session()->get('user_data');
        $role = $actor['role'];

        if ($role != "desenvolupador" && $role != "admin_sstt") {
            return redirect()->to(base_url('/tiquets'));
        }

        $llista_admesos_model = new LlistaAdmesosModel();
        $login_in_rol_model = new LoginInRolModel();
        $login_model = new LoginModel();


        $array_professors = $llista_admesos_model->obtenirLlistaAdmesos();

        for ($i = 0; $i < sizeof($array_professors); $i++) {
            $correu_professor = $array_professors[$i]['correu_professor'];
            $llista_admesos_desactivar = $llista_admesos_model->existeixProfessor($correu_professor);
            if ($llista_admesos_desactivar != null) {
                $llista_admesos_model->esborrarLlistaAdmesos($correu_professor);
                $msg = lang("alertes.llista_admesos_esborrat");
                session()->setFlashdata("llista_admesos_esborrat", $msg);
    
                $id_login = $login_model->obtenirId($correu_professor);
                $login_in_rol_model->deleteLoginInRol($id_login);
                $login_model->deleteLogin($id_login);
            }
        }




        $data['tipus_pantalla'] = "llista_admesos";
        return redirect()->to(base_url('/professor'));
    }
}
