<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AlumneModel;
use App\Models\CentreModel;
use App\Models\ComarcaModel;
use SIENSIS\KpaCrud\Libraries\KpaCrud;
use App\Models\TiquetModel;
use App\Models\EstatModel;
use App\Models\IntervencioModel;
use App\Models\LoginInRolModel;
use App\Models\LoginModel;
use App\Models\PoblacioModel;
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
        $session_filtre = session()->get('filtresAlumnes');
        $data['session_filtre'] = $session_filtre;


        if ($correu_alumne_eliminar != null) {

            $alumne = $alumne_model->getAlumneByCorreu($correu_alumne_eliminar);

            if ($alumne != null) {

                $codi_centre_alumne = $alumne['codi_centre'];
                $id_sstt_alumne = $centre_model->obtenirCentre($codi_centre_alumne)['id_sstt'];

                if (($role == "professor" && $codi_centre_alumne == $actor['codi_centre']) || ($role == "admin_sstt" && $id_sstt_alumne == $actor['id_sstt']) || ($role == "desenvolupador")) {

                    //Preguntar a la bbdd quin tiquet es i retornar l'array del tiquet.
                    $data['correu_alumne_eliminar'] = $correu_alumne_eliminar;
                    session()->setFlashdata("correu_alumne_eliminar", $alumne_model->getAlumneByCorreu($correu_alumne_eliminar));
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
            $role = session()->get('user_data')['role'];
            $data['role'] = $role;
            $data['centre_reparador'] = $this->selectorCentreReparador($role, $actor);
            $data['poblacio'] = $this->selectorPoblacio($role, $actor);
            $data['comarca'] = $this->selectorComarca($role, $actor);


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
                "paging" => true,
                "numerate" => false,
                "sortable" => true,
                "exportXLS" => true,
                "print" => false
            ]);
            $crud->hideHeadLink([
                'js-bootstrap',
                'css-bootstrap',
            ]);
            $crud->setTable('vista_alumne');
            $crud->setPrimaryKey('correu_alumne');
            $crud->addItemLink('edit', 'fa-pencil', base_url('alumnes/editar'), 'Editar Tiquet');
            $crud->addItemLink('delete', 'fa-trash', base_url('alumnes/esborrar'), 'Eliminar Tiquet');


            if ($role == "professor") {

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
                    'nom_poblacio',
                    'nom_comarca'
                ]);
                $crud->setColumnsInfo([
                    'correu_alumne' => [
                        'name' => lang('alumne.correu_alumne')
                    ],
                    'nom_centre' => [
                        'name' => lang('alumne.nom_centre'),
                    ],
                    'nom_poblacio' => [
                        'name' => lang('inventari.nom_poblacio')
                    ],
                    'nom_comarca' => [
                        'name' => lang('inventari.nom_comarca')
                    ]
                ]);
                $crud->addWhere('id_sstt', session()->get('user_data')['id_sstt']);
            } else if ("desenvolupador") {

                $crud->setColumns([
                    'correu_alumne',
                    'nom_centre',
                    'nom_poblacio',
                    'nom_comarca'
                ]);
                $crud->setColumnsInfo([
                    'correu_alumne' => [
                        'name' => lang('alumne.correu_alumne')
                    ],
                    'nom_centre' => [
                        'name' => lang('alumne.nom_centre'),
                    ],
                    'nom_poblacio' => [
                        'name' => lang('inventari.nom_poblacio')
                    ],
                    'nom_comarca' => [
                        'name' => lang('inventari.nom_comarca')
                    ]
                ]);
            }
            $crud->addWhere('actiu', 1);


            if (is_array($session_filtre)) {

                if (isset($session_filtre['nom_centre_reparador'])) {
                    $model_centre = new CentreModel();
                    $centre_reparador_escollit = $model_centre->obtenirCentre($session_filtre['nom_centre_reparador'][0]);
                    $data['centre_reparador_escollit'] = $centre_reparador_escollit;

                    $crud->addWhere('codi_centre', $session_filtre['nom_centre_reparador'][0], true);
                }
                if (isset($session_filtre['nom_poblacio'])) {
                    $model_poblacio = new PoblacioModel();
                    $poblacio_escollida = $model_poblacio->getPoblacio($session_filtre['nom_poblacio'][0], true);
                    $data['poblacio_escollida'] = $poblacio_escollida['nom_poblacio'];
                    $crud->addWhere('id_poblacio', $poblacio_escollida['id_poblacio'], true);
                }
                if (isset($session_filtre['nom_comarca'])) {
                    $model_comarca = new ComarcaModel();
                    $comarca_escollida = $model_comarca->obtenirComarca($session_filtre['nom_comarca'][0], true);
                    $data['comarca_escollida'] = $comarca_escollida['nom_comarca'];

                    $crud->addWhere('id_comarca', $comarca_escollida['id_comarca'], true);
                }
            }





            $data['output'] = $crud->render();
            $data['uri'] = $this->request->getPath();
            return view('registres' . DIRECTORY_SEPARATOR . 'registreAlumnes', $data);
        }
    }


    public function crearAlumne()
    {

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

    public function crearAlumne_post()
    {

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
                    $msg = lang('alertes.flash_data_create_alumne');
                    session()->setFlashdata('afegirAlumne', $msg);
                } else if ($role == "admin_sstt" || $role == "desenvolupador") {

                    $codi_centre = $this->request->getPost('centre');
                    $codi_centre = trim(explode('-', (string) $codi_centre)[0]);

                    $id_sstt_post = $centre_model->obtenirCentre($codi_centre)['id_sstt'];

                    if (session()->get('user_data')['id_sstt'] == $id_sstt_post) {
                        $alumne_model->addAlumne($correu_alumne, $codi_centre);
                        $login_model->addLogin($correu_alumne, null);
                        $login_in_rol->addLoginInRol($login_model->obtenirId($correu_alumne), $rol_model->obtenirIdRol("alumne"));
                        $msg = lang('alertes.flash_data_create_alumne');
                        session()->setFlashdata('afegirAlumne', $msg);
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

    public function eliminarAlumne($correu_alumne_eliminar)
    {

        $alumne_model = new AlumneModel();
        $centre_model = new CentreModel();

        $actor = session()->get('user_data');
        $role = $actor['role'];

        $alumne = $alumne_model->getAlumneByCorreu($correu_alumne_eliminar);
        $codi_centre_alumne = $alumne['codi_centre'];
        $id_sstt_alumne = $centre_model->obtenirCentre($codi_centre_alumne)['id_sstt'];

        if (($role == "professor" && $codi_centre_alumne == $actor['codi_centre']) || ($role == "admin_sstt" && $id_sstt_alumne == $actor['id_sstt']) || ($role == "desenvolupador")) {

            $alumne_model->editarAlumneActiu($correu_alumne_eliminar, 0);
            $msg = lang('alertes.flash_data_delete_alumne') . $correu_alumne_eliminar;
            session()->setFlashdata('eliminarAlumne', $msg);
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

                $alumne_post = $alumne_model->getAlumneByCorreu($correu_alumne_post);

                if ($alumne_post) {
                    $alumne_model->editarAlumneActiu($correu_alumne_post, 1);
                } else {
                    $alumne_model->addAlumne($correu_alumne_post, $alumne_editar['codi_centre']);
                    $login_model->addLogin($correu_alumne_post, null);
                    $login_in_rol->addLoginInRol($login_model->obtenirId($correu_alumne_post), $rol_model->obtenirIdRol("alumne"));
                }

                $intervencio_model->editarIntervencioCorreuNou($correu_alumne_editar, $correu_alumne_post);
                $alumne_model->editarAlumneActiu($correu_alumne_editar, 0);

                $msg = lang('alertes.flash_data_update_alumne');
                session()->setFlashdata('afegirAlumne', $msg);

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
                        $msg = lang('alertes.flash_data_update_alumne');
                        session()->setFlashdata('afegirAlumne', $msg);
                        $intervencions = $intervencio_model->obtenirIdIntervencioAlumne($correu_alumne_editar); // Obtenim els id de les intervencions de l'alumne
                        for ($i = 0; $i < sizeof($intervencions); $i++) {
                            $intervencio_model->editarIntervencioCorreuNou($intervencions[$i]['id_intervencio'], $correu_alumne_post); // Assignem les intervencions al nou alumne
                        }

                        $alumne_model->editarAlumneActiu($correu_alumne_editar, 0);
                        $msg = lang('alertes.flash_data_delete_alumne') . $correu_alumne_editar;
                        session()->setFlashdata('eliminarAlumne', $msg);
                        $id_login = $login_model->obtenirId($correu_alumne_editar);
                        //$login_in_rol->deleteLoginInRol($id_login);
                        //$login_model->deleteLogin($id_login);

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

    public function selectorCentreReparador($role, $actor)
    {
        $centre_model = new CentreModel();
        $array_centres = $centre_model->obtenirCentres();
        $options_tipus_dispositius_reparadors = "";

        for ($i = 0; $i < sizeof($array_centres); $i++) {
            if ($array_centres[$i]['taller'] == 1) {
                if (($role == "sstt" || $role == "admin_sstt") && $array_centres[$i]['id_sstt'] == $actor['id_sstt']) {
                    $options_tipus_dispositius_reparadors .= "<option value=\"" . $array_centres[$i]['codi_centre'] . " - " . $array_centres[$i]['nom_centre'] . "\">";
                    $options_tipus_dispositius_reparadors .= $array_centres[$i]['nom_centre'];
                    $options_tipus_dispositius_reparadors .= "</option>";
                } else if ($role == "desenvolupador") {
                    $options_tipus_dispositius_reparadors .= "<option value=\"" . $array_centres[$i]['codi_centre'] . " - " . $array_centres[$i]['nom_centre'] . "\">";
                    $options_tipus_dispositius_reparadors .= $array_centres[$i]['nom_centre'];
                    $options_tipus_dispositius_reparadors .= "</option>";
                }
            }
        }

        return $options_tipus_dispositius_reparadors;
    }

    public function selectorPoblacio($role, $actor)
    {
        $poblacio_model = new PoblacioModel();
        $array_poblacions = $poblacio_model->obtenirPoblacions();
        $options_poblacions = "";
        //dd($actor);
        for ($i = 0; $i < sizeof($array_poblacions); $i++) {
            //if (($role == "sstt" || $role == "admin_sstt") && $array_poblacions[$i]['id_sstt'] == $actor['id_sstt']) {
            //} else if ($role == "desenvolupador") {
            //}
            $options_poblacions .= "<option value=\"" . $array_poblacions[$i]['id_poblacio'] . " - " . $array_poblacions[$i]['nom_poblacio'] . "\">";
            $options_poblacions .= $array_poblacions[$i]['nom_poblacio'];
            $options_poblacions .= "</option>";
        }

        return $options_poblacions;
    }

    public function selectorComarca($role, $actor)
    {
        $comarca_model = new ComarcaModel();
        $array_comarques = $comarca_model->obtenirComarques();
        $options_comarques = "";
        //dd($array_comarques);
        for ($i = 0; $i < sizeof($array_comarques); $i++) {
            //if (($role == "sstt" || $role == "admin_sstt") && $array_poblacions[$i]['id_sstt'] == $actor['id_sstt']) {
            //} else if ($role == "desenvolupador") {
            //}
            $options_comarques .= "<option value=\"" . $array_comarques[$i]['id_comarca'] . " - " . $array_comarques[$i]['nom_comarca'] . "\">";
            $options_comarques .= $array_comarques[$i]['nom_comarca'];
            $options_comarques .= "</option>";
        }

        return $options_comarques;
    }

    public function filtrePost()
    {
        $centre_model = new CentreModel();
        $poblacio_model = new PoblacioModel();
        $comarca_model = new ComarcaModel();

        $session = session();
        $sessio_filtres = $session->get('filtresAlumnes');

        $eliminar = $this->request->getPost('submit_eliminar_filtres');

        if ($eliminar !== null) {
            $session->remove('filtresAlumnes');
        } else {

            if ($sessio_filtres == null) {
                $filtres = [];
                $session->set('filtresAlumnes', $filtres);
            }

            $dades = $this->request->getPost();

            if (isset($dades['selector_tipus_dispositiu'])) {
                $array_tipus_dispositiu = [];
                $tipus_dispositiu_seleccionat = $dades['selector_tipus_dispositiu'];
                $tipus_dispositiu = trim(explode('-', (string) $tipus_dispositiu_seleccionat)[0]);
                array_push($array_tipus_dispositiu, $tipus_dispositiu);
                $session->push('filtresAlumnes', ['tipus_dispositiu' => $array_tipus_dispositiu]);
            }
            if (isset($dades['nom_centre_reparador_list']) && $dades['nom_centre_reparador_list'] !== '') {

                $array_centre_reparador = [];
                $nom_centre_reparador = $dades['nom_centre_reparador_list'];
                $centre_reparador = trim(explode('-', (string) $nom_centre_reparador)[0]);

                // TODO Bea ficar alerta
                if ($centre_reparador != null && $centre_model->obtenirCentre($centre_reparador) == null) {
                    return redirect()->back()->withInput();
                }

                array_push($array_centre_reparador, $centre_reparador);
                $session->push('filtresAlumnes', ['nom_centre_reparador' => $array_centre_reparador]);
            }
            if (isset($dades['data_creacio']) &&  $dades['data_creacio'] !== '') {
                $array_data_creacio = [];
                $data_creacio = $dades['data_creacio'];
                array_push($array_data_creacio, $data_creacio);
                $session->push('filtresAlumnes', ['data_creacio' => $array_data_creacio]);
            }
            if (isset($dades['nom_poblacio_list']) && $dades['nom_poblacio_list'] !== '') {

                $array_poblacio = [];
                $nom_poblacio = $dades['nom_poblacio_list'];
                $poblacio = trim(explode('-', (string) $nom_poblacio)[0]);

                // TODO Bea ficar alerta
                if ($poblacio != null && $poblacio_model->getPoblacio($poblacio) == null) {
                    return redirect()->back()->withInput();
                }

                array_push($array_poblacio, $poblacio);
                $session->push('filtresAlumnes', ['nom_poblacio' => $array_poblacio]);
            }
            if (isset($dades['nom_comarca_list']) && $dades['nom_comarca_list'] !== '') {

                $array_comarca = [];
                $nom_comarca = $dades['nom_comarca_list'];
                $comarca = trim(explode('-', (string) $nom_comarca)[0]);

                // TODO Bea ficar alerta
                if ($comarca != null && $comarca_model->obtenirComarca($comarca) == null) {
                    return redirect()->back()->withInput();
                }

                array_push($array_comarca, $comarca);
                $session->push('filtresAlumnes', ['nom_comarca' => $array_comarca]);
            }
        }
        return redirect()->back()->withInput();
    }

    public function eliminarFiltre()
    {
        $filtre_eliminar = $this->request->getPost();
        $filtre_session = session()->get('filtresAlumnes');
        $eliminar = $this->request->getPost('submit_eliminar_filtres');

        if ($eliminar !== null) {
            session()->remove('filtresAlumnes');
        }
        if ($filtre_eliminar['operacio'] === 'Dispositiu') {
            unset($filtre_session['tipus_dispositiu']);
            session()->set('filtresAlumnes', $filtre_session);
        }
        if ($filtre_eliminar['operacio'] === 'Estat') {
            unset($filtre_session['estat']);
            session()->set('filtresAlumnes', $filtre_session);
        }
        if ($filtre_eliminar['operacio'] == 'Centre_emissor') {
            unset($filtre_session['nom_centre_emissor']);
            session()->set('filtresAlumnes', $filtre_session);
        }
        if ($filtre_eliminar['operacio'] == 'Centre_reparador') {
            unset($filtre_session['nom_centre_reparador']);
            session()->set('filtresAlumnes', $filtre_session);
        }
        if ($filtre_eliminar['operacio'] == 'data_creacio') {
            unset($filtre_session['data_creacio']);
            session()->set('filtresAlumnes', $filtre_session);
        }
        if (count($filtre_session) == 0) {
            session()->remove('filtresAlumnes');
        }
        if ($filtre_eliminar['operacio'] == 'Poblacio') {
            unset($filtre_session['nom_poblacio']);
            session()->set('filtresAlumnes', $filtre_session);
        }
        if ($filtre_eliminar['operacio'] == 'Comarca') {
            unset($filtre_session['nom_comarca']);
            session()->set('filtresAlumnes', $filtre_session);
        }

        return redirect()->back();
    }
}
