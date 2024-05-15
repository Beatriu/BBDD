<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AlumneModel;
use App\Models\CentreModel;
use SIENSIS\KpaCrud\Libraries\KpaCrud;
use App\Models\TiquetModel;
use App\Models\EstatModel;
use App\Models\IntervencioModel;
use App\Models\LoginInRolModel;
use App\Models\LoginModel;
use App\Models\RolModel;

class AlumnesController extends BaseController
{
    protected $helpers = ['form'];
    
    public function registreAlumnes($correu_alumne_eliminar = null)
    {

        $alumne_model = new AlumneModel();
        $centre_model = new CentreModel();
        $actor = session()->get('user_data');
        $role = $actor['role'];
        $data['correu_alumne_eliminar'] = null;
        $data['no_permisos'] = null;
        $data['no_permisos'] = session()->getFlashdata('alumne_no_permisos');


        if ($correu_alumne_eliminar != null) {

            $alumne = $alumne_model->getAlumneByCorreu($correu_alumne_eliminar);
            
            if ($alumne != null) {

                $codi_centre_alumne = $alumne['codi_centre'];
                $id_sstt_alumne = $centre_model->obtenirCentre($codi_centre_alumne)['id_sstt'];
        
                if (($role == "professor" && $codi_centre_alumne == $actor['codi_centre']) || ($role == "admin_sstt" && $id_sstt_alumne == $actor['id_sstt']) || ($role == "desenvolupador")) {
        
                    //Preguntar a la bbdd quin tiquet es i retornar l'array del tiquet.
                    $data['correu_alumne_eliminar'] = $correu_alumne_eliminar;
                    session()->setFlashdata("correu_alumne_eliminar",$alumne_model->getAlumneByCorreu($correu_alumne_eliminar));
        
                } else {
                    $data['no_permisos'] = "alumne.no_permisos";
                }

            } else {
                $data['no_permisos'] = "alumne.no_existeix";
            }


        }


        if ($role == "alumne" || $role == "centre_emissor" || $role == "centre_reparador" || $role == "sstt") {

            return redirect()->to(base_url('/tiquets'));

        } else {
            $codi_centre = session()->get('user_data')['codi_centre'];

            $data['title'] = 'Tiquets SSTT';

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
                "exportXLS" => true,
                "print" => false
            ]);
            $crud->setTable('vista_alumne');
            $crud->setPrimaryKey('correu_alumne');
            $crud->addItemLink('edit', 'fa-pencil', base_url('alumnes/editar'), 'Editar Tiquet');
            $crud->addItemLink('delete', 'fa-trash', base_url('alumnes/esborrar'), 'Eliminar Tiquet');
            

            if ($role == "professor"){

                $crud->setColumns([
                    'correu_alumne',
                ]);
                $crud->setColumnsInfo([
                    'correu_alumne' => [
                        'name' => lang('alumne.correu_alumne')
                    ],
                ]);
                $crud->addWhere('codi_centre', $codi_centre);

            } else if ($role == "admin_sstt") {

                $crud->setColumns([
                    'correu_alumne',
                    'nom_centre',
                ]);
                $crud->setColumnsInfo([
                    'correu_alumne' => [
                        'name' => lang('alumne.correu_alumne')
                    ],
                    'nom_centre' => [
                        'name' => lang('alumne.nom_centre'),
                    ]
                ]);
                $crud->addWhere('id_sstt', session()->get('user_data')['id_sstt']);

            } else if ("desenvolupador") {

                $crud->setColumns([
                    'correu_alumne',
                    'nom_centre',
                ]);
                $crud->setColumnsInfo([
                    'correu_alumne' => [
                        'name' => lang('alumne.correu_alumne')
                    ],
                    'nom_centre' => [
                        'name' => lang('alumne.nom_centre'),
                    ]
                ]);

            }
            $crud->addWhere('actiu', 1);
    
            $data['output'] = $crud->render();
            $data['uri'] = $this->request->getPath();
            return view('registres' . DIRECTORY_SEPARATOR . 'registreAlumnes', $data);
        }

    }


    public function crearAlumne() {

        $centre_model = new CentreModel();
        $data['title'] = lang('alumne.formulari_alumne');
        $data['afegir_alumne_error'] = session()->getFlashdata('afegir_alumne_error');

        $role = session()->get('user_data')['role'];
        $data['role'] = $role;

        if ($role == "professor") {

            return view('formularis' . DIRECTORY_SEPARATOR . 'formulariAfegirAlumne', $data);

        } else if ($role == "admin_sstt" || $role == "desenvolupador") {

            $id_sstt = session()->get('user_data')['id_sstt'] = session()->get('user_data')['id_sstt'];

            $array_centres = $centre_model->obtenirCentres();
            $options_centres = "";

            for ($i = 0; $i < sizeof($array_centres); $i++) {
                if ($array_centres[$i]['id_sstt'] == $id_sstt) {
                    $options_centres .= "<option value=\"" . $array_centres[$i]['codi_centre'] . " - " . $array_centres[$i]['nom_centre'] . "\">";
                    $options_centres .= $array_centres[$i]['nom_centre'];
                    $options_centres .= "</option>";
                }

            }

            $data['centres'] = $options_centres;

            return view('formularis' . DIRECTORY_SEPARATOR . 'formulariAfegirAlumne', $data);
        } else {
            return redirect()->to(base_url('/tiquets'));
        }

    }

    public function crearAlumne_post() {

        $alumne_model = new AlumneModel();
        $centre_model = new CentreModel();
        $login_model = new LoginModel();
        $login_in_rol = new LoginInRolModel();
        $rol_model = new RolModel();

        $role = session()->get('user_data')['role'];

        if ($role == "professor") {
            $validationRules = [
                'correu_alumne' => [
                    'rules'  => 'required|max_length[32]',
                    'errors' => [
                        'required' => lang('alumne.correu_alumne_required'),
                        'max_length' => lang('alumne.correu_alumne_max'),
                    ],
                ],
            ];
        } else if ($role == "admin_sstt" || $role == "desenvolupador") {
            $validationRules = [
                'correu_alumne' => [
                    'rules'  => 'required|max_length[32]',
                    'errors' => [
                        'required' => lang('alumne.correu_alumne_required'),
                        'max_length' => lang('alumne.correu_alumne_max'),
                    ],
                ],
                'centre' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => lang('alumne.centre_required'),
                    ]
                ],
            ];
        }

        if ($this->validate($validationRules)) {

            $correu_alumne = $this->request->getPost("correu_alumne");
            $alumne = $alumne_model->getAlumneByCorreu($correu_alumne);

            if ($alumne == null) {

                if ($role == "professor") {

                    $codi_centre = session()->get('user_data')['codi_centre'];
                    $alumne_model->addAlumne($correu_alumne, $codi_centre);
                    $login_model->addLogin($correu_alumne, null);
                    $login_in_rol->addLoginInRol($login_model->obtenirId($correu_alumne), $rol_model->obtenirIdRol("alumne"));
    
                } else if ($role == "admin_sstt" || $role == "desenvolupador") {
    
                    $codi_centre = $this->request->getPost('centre');
                    $codi_centre = trim(explode('-', (string) $codi_centre)[0]);

                    $id_sstt_post = $centre_model->obtenirCentre($codi_centre)['id_sstt'];

                    if (session()->get('user_data')['id_sstt'] == $id_sstt_post) {
                        $alumne_model->addAlumne($correu_alumne, $codi_centre);
                        $login_model->addLogin($correu_alumne, null);
                        $login_in_rol->addLoginInRol($login_model->obtenirId($correu_alumne), $rol_model->obtenirIdRol("alumne"));
                    } else {
                        session()->setFlashdata('afegir_alumne_error', 'alumne.codi_no_sstt');
                        return redirect()->back()->withInput();
                    }
    
                }

            } else {

                if ($alumne['actiu'] == 0) {
                    $alumne_model->editarAlumneActiu($alumne['correu_alumne'], 1);
                } else {
                    session()->setFlashdata('afegir_alumne_error', 'alumne.alumne_existeix');
                    return redirect()->back()->withInput();
                }

            }

            return redirect()->to(base_url('/alumnes'));
            
        } else {

            session()->setFlashdata('afegir_alumne_error', 'alumne.afegir_no_validat');
            return redirect()->back()->withInput();
        }

    }

    public function eliminarAlumne($correu_alumne_eliminar) {

        $alumne_model = new AlumneModel();
        $centre_model = new CentreModel();

        $actor = session()->get('user_data');
        $role = $actor['role'];

        $alumne = $alumne_model->getAlumneByCorreu($correu_alumne_eliminar);
        $codi_centre_alumne = $alumne['codi_centre'];
        $id_sstt_alumne = $centre_model->obtenirCentre($codi_centre_alumne)['id_sstt'];

        if (($role == "professor" && $codi_centre_alumne == $actor['codi_centre']) || ($role == "admin_sstt" && $id_sstt_alumne == $actor['id_sstt']) || ($role == "desenvolupador")) {

            $alumne_model->editarAlumneActiu($correu_alumne_eliminar, 0);

        } else {
            session()->setFlashdata('alumne_no_permisos', "alumne.no_permisos");
        }

        return redirect()->to(base_url('/alumnes'));
    }

    public function editarAlumne($correu_alumne_editar)
    {
        $alumne_model = new AlumneModel();
        $centre_model = new CentreModel();
        $data['title'] = lang('alumne.formulari_alumne');
        $data['afegir_alumne_error'] = session()->getFlashdata('afegir_alumne_error');
        $data['editar_alumne_error'] = null;

        $actor = session()->get('user_data');
        $role = $actor['role'];
        $data['role'] = $role;

        if (session()->getFlashdata('editar_alumne_error') != null) {
            $data['editar_alumne_error'] = session()->getFlashdata('editar_alumne_error');
            $data['correu_editar'] = session()->getFlashdata('correu_editar');
        }


        $alumne = $alumne_model->getAlumneByCorreu($correu_alumne_editar);

        if ($alumne != null) {

            $codi_centre_alumne = $alumne['codi_centre'];
            $id_sstt_alumne = $centre_model->obtenirCentre($codi_centre_alumne)['id_sstt'];

            if (($role == "professor" && $codi_centre_alumne == $actor['codi_centre']) || ($role == "admin_sstt" && $id_sstt_alumne == $actor['id_sstt']) || ($role == "desenvolupador")) {

                $data['correu_alumne'] = $correu_alumne_editar;
                session()->setFlashdata('correu_alumne_editar', $correu_alumne_editar);

                if ($role == "admin_sstt" || $role == "desenvolupador") {

                    $id_sstt = session()->get('user_data')['id_sstt'] = session()->get('user_data')['id_sstt'];
        
                    $array_centres = $centre_model->obtenirCentres();
                    $options_centres = "";
        
                    for ($i = 0; $i < sizeof($array_centres); $i++) {
                        if ($array_centres[$i]['id_sstt'] == $id_sstt) {

                            if ($array_centres[$i]['codi_centre'] != $codi_centre_alumne) {
                                $options_centres .= "<option value=\"" . $array_centres[$i]['codi_centre'] . " - " . $array_centres[$i]['nom_centre'] . "\">";
                                $options_centres .= $array_centres[$i]['nom_centre'];
                                $options_centres .= "</option>";
                            } else {
                                $data['codi_centre'] = $array_centres[$i]['codi_centre'] . " - " . $array_centres[$i]['nom_centre'];
                                $options_centres .= "<option value=\"" . $array_centres[$i]['codi_centre'] . " - " . $array_centres[$i]['nom_centre'] . "\" selected>";
                                $options_centres .= $array_centres[$i]['nom_centre'];
                                $options_centres .= "</option>";
                            }
                            
                        }
        
                    }
        
                    $data['centres'] = $options_centres;
                
                }

                return view('formularis' . DIRECTORY_SEPARATOR . 'formulariEditarAlumne', $data);
    
            } else {

                session()->setFlashdata('alumne_no_permisos', 'alumne.no_permisos');
                return redirect()->to(base_url('/alumnes'));

            }
    
    
        }

    }

    public function editarAlumne_post()
    {
        $alumne_model = new AlumneModel();
        $centre_model = new CentreModel();
        $intervencio_model = new IntervencioModel();
        $login_model = new LoginModel();
        $login_in_rol = new LoginInRolModel();
        $rol_model = new RolModel();

        $correu_alumne_editar = session()->get('correu_alumne_editar');

        $actor = session()->get('user_data');
        $role = $actor['role'];

        $alumne_editar = $alumne_model->getAlumneByCorreu($correu_alumne_editar);

        if ($alumne_editar != null) {

            $correu_alumne_post = $this->request->getPost('correu_alumne');
            $alumne_post = $alumne_model->getAlumneByCorreu($correu_alumne_post);

            //if ($alumne_post == null) {
                $codi_centre_alumne = $alumne_editar['codi_centre'];
                $id_sstt_alumne = $centre_model->obtenirCentre($codi_centre_alumne)['id_sstt'];

                if ($role == "professor" && $codi_centre_alumne == $actor['codi_centre']) {
                    
                    $alumne_model->addAlumne($correu_alumne_post, $alumne_editar['codi_centre']);
                    $login_model->addLogin($correu_alumne_post, null);
                    $login_in_rol->addLoginInRol($login_model->obtenirId($correu_alumne_post), $rol_model->obtenirIdRol("alumne"));
                    $intervencio_model->editarIntervencioCorreuNou($correu_alumne_editar, $correu_alumne_post);
                    $alumne_model->deleteAlumneByCorreu($correu_alumne_editar);
                    $id_login = $login_model->obtenirId($correu_alumne_editar);
                    $login_in_rol->deleteLoginInRol($id_login);
                    $login_model->deleteLogin($id_login);
                    return redirect()->to(base_url('/alumnes'));
                    
                } else if (($role == "admin_sstt" && $id_sstt_alumne == $actor['id_sstt']) || ($role == "desenvolupador")) {
                    
                    $codi_centre_post = $this->request->getPost('centre');
                    $codi_centre_post = trim(explode('-', (string) $codi_centre_post)[0]);

                    $id_sstt_post = $centre_model->obtenirCentre($codi_centre_post)['id_sstt'];

                    if ($id_sstt_alumne == $id_sstt_post) { // Comprovem que l'identificador del sstt que volem assignar a l'alumne sigui el mateix que el de l'actor

                        if ($correu_alumne_editar != $correu_alumne_post) { // En cas que el correu original i el nou siguin diferents
                            
                            $alumne_model->addAlumne($correu_alumne_post, $codi_centre_post); // Creem un alumne nou
                            $login_model->addLogin($correu_alumne_post, null);
                            $login_in_rol->addLoginInRol($login_model->obtenirId($correu_alumne_post), $rol_model->obtenirIdRol("alumne"));

                            $intervencions = $intervencio_model->obtenirIdIntervencioAlumne($correu_alumne_editar); // Obtenim els id de les intervencions de l'alumne
                            for ($i = 0; $i < sizeof($intervencions); $i++){
                                $intervencio_model->editarIntervencioCorreuNou($intervencions[$i]['id_intervencio'], $correu_alumne_post); // Assignem les intervencions al nou alumne
                            }
                
                            $alumne_model->deleteAlumneByCorreu($correu_alumne_editar); // Eliminem l'alumne antic
                            $id_login = $login_model->obtenirId($correu_alumne_editar);
                            $login_in_rol->deleteLoginInRol($id_login);
                            $login_model->deleteLogin($id_login);

                        } else { // En cas que els correu original i el nou siguin iguals, només cal editar el codi centre
                     
                            $alumne_model->editarAlumneCodiCentre($correu_alumne_editar, $codi_centre_post);
                        }

                        return redirect()->to(base_url('/alumnes'));

                    } else {
                        session()->setFlashdata('correu_editar', $correu_alumne_editar);
                        session()->setFlashdata('editar_alumne_error', 'alumne.codi_no_sstt');
        
                        return redirect()->back()->withInput();
                    }
                    
                }
            /*} else {
                session()->setFlashdata('correu_editar', $correu_alumne_editar);
                session()->setFlashdata('editar_alumne_error', 'alumne.alumne_existeix');

                return redirect()->back()->withInput();
            }*/

        } else {
            session()->setFlashdata('correu_editar', $correu_alumne_editar);
            session()->setFlashdata('editar_alumne_error', 'alumne.no_existeix');
            
            return redirect()->back()->withInput();
        }
    }
}
