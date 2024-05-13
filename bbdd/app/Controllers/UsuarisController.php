<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AlumneModel;
use App\Models\LoginInRolModel;
use App\Models\LoginModel;
use App\Models\ProfessorModel;
use App\Models\CentreModel;
use App\Models\LlistaAdmesosModel;
use App\Models\RolModel;

class UsuarisController extends BaseController
{
    protected $helpers = ['form', 'file', 'filesystem'];

    public function login_post()
    {
        $data['title'] = "login";

        // Determinem les regles de validació
        $validationRules = [
            'sUser' => [
                'rules'  => 'required|max_length[32]',
                'errors' => [
                    'required' => lang('general_lang.nom_usuari_required'),
                    'max_length' => lang('general_lang.nom_usuari_required_max_length'),
                ],
            ],
            'sPssw' => [
                'rules'  => 'required|min_length[6]',
                'errors' => [
                    'required' => lang('general_lang.contrasenya_required'),
                    'min_length' => lang('general_lang.contrasenya_min_length'),
                ],
            ],
        ];

        if ($this->validate($validationRules)) { // En cas que es compleixin les regles de validació

            // Obtenim del formualri el nom d'usuari
            $nom_login = $this->request->getPost('sUser');

            // Obtenim l'usuari mitjançant aquest
            $login_model = new LoginModel;
            $login_in_rol_model = new LoginInRolModel();
            $rol_model = new RolModel();
            $login_obtingut = $login_model->obtenirLogin($nom_login);

            if ($login_obtingut != null) { // En cas que existeixi

                // Obtenim la contrasenya del formulari
                $contrasenya = $this->request->getPost('sPssw');

                if (gettype($contrasenya) == "string") {
                    $password = $contrasenya;
                }
                if (gettype($nom_login) == "string") {
                    $correu = $nom_login;
                }

                $hash = $login_obtingut['contrasenya'];

                if (password_verify($password, $hash) && $password != "" && $password != null) { // Verifiquem que la contrasenya coincideixi amb la de la base de dades i que existeixi

                    $session_data['mail'] = $nom_login;
                    $session_data['nom'] = explode("@", (string) $nom_login)[0];
                    $session_data['cognoms'] = "Cognom Exemple";
                    $session_data['domain'] = explode('@', $session_data['mail'])[1];

                    $id_login = $login_model->obtenirId($nom_login);
                    $id_role = $login_in_rol_model->obtenirRol($id_login);
                    $role = $rol_model->obtenirRol($id_role);
                    $session_data['role'] = $role;


                    if ($role != "alumne" && $role != "professor") {
                        $session_data['codi_centre'] = "no_codi";

                        if ($role == "sstt" || $role == "admin_sstt") {
                            $session_data['id_sstt'] = $login_model->obtenirIdSSTT($session_data['mail'])['id_sstt'];
                        }
                    }
                    
                    session()->set('user_data', $session_data);

                    return redirect()->to(base_url('/tiquets'));
                }
            }
        }

        return redirect()->back()->withInput();
    }

    public function login()
    {
        // Funció principal d'inici de sessió. Aquesta és la primera, la que carrega la vista inicial

        // Definim el títol de la pàgina i carreguem els models a utilitzar 
        $data['title'] = "login";
        $login_model = new LoginModel();
        $login_in_rol_model = new LoginInRolModel();
        $rol_model = new RolModel();
        $llista_admesos_model = new LlistaAdmesosModel();
        $centre_model = new CentreModel();
        $alumne_model = new AlumneModel();


        $client = new \Google\Client(); //Generem un client de google

        //LINIES CREDENCIALS
        $client->setRedirectUri('http://localhost:8080/login'); //Redirect Uri

        // Permisos/informació que demanem a l'usuari
        $client->addScope(\Google\Service\Oauth2::USERINFO_EMAIL);
        $client->addScope(\Google\Service\Oauth2::USERINFO_PROFILE);
        $client->addScope(\Google\Service\Oauth2::OPENID);
        $client->setAccessType('offline');

        if (isset($_GET["code"])) { // En cas que el paràmetre code estigui definit

            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']); // Es canvia el codi per un token autoritzat

            if (!isset($token["error"])) { //En cas que no hi hagi error en canviar el codi per un token autoritzat
                $client->setAccessToken($token['access_token']); // S'estableix el token a utilitzar per fer requests

                session()->set('access_token', $token['access_token']); // Es guarda en sessió el token

                $oauth2 = new \Google\Service\Oauth2($client); // Es defineix oauth2 pel client de google inicialitzat

                //Obtenim la informació de l'usuari
                $userInfo = $oauth2->userinfo->get(); 
                $session_data['mail']=$userInfo->getEmail();
                $session_data['nom']=$userInfo->getGivenName();

                
                if ($userInfo->getFamilyName() == null) { // En cas que no existeixi el cognom, l'extraiem per la comparació del nom i el nom complet
                    $nomComplet=$userInfo->getName();
                    $pos = strpos($nomComplet, $session_data['nom']);
                    
                    if ($pos !== false) {
                        $diferencia = substr($nomComplet, $pos + strlen($session_data['nom']));
                        $session_data['cognoms'] = $diferencia; // Esto imprimirá " Burgués"
                    }

                } else { // En cas que existeixi el cognom
                    $session_data['cognoms']=$userInfo->getFamilyName();
                    
                }

                $session_data['domain'] = explode('@', $session_data['mail'])[1]; // Obtenim el domini del correu

                session()->set('user_data', $session_data); // Guardem en sessió la informació obtinguda de l'usuari
            }
        }
        $login_button = '';

        if (!session()->get('access_token') && !session()->get('user_data')) { // En cas que no existeixi el token de sessió i la infromació de l'usuari guardades en sessió
            $login_button = '<a class = "btn btn-outline-dark" href="' . $client->createAuthUrl() . '"><i class="fa-brands fa-google me-2"></i>' . lang("crud.buttons.enter_google") . '</a>';
            $data['login_button'] = $login_button;

            return view('logins' . DIRECTORY_SEPARATOR . 'loginGeneral', $data); // Es retorna la vista bàsica d'inici de sessió
        } else { // En cas que estigui creada la sessió de token i la sessió de la infromació de l'usuari
            
            $mail = session()->get('user_data')['mail']; // Obtenim el correu guardat en sessió

            if ($login_model->obtenirLogin($mail) == null) { // En cas que el login no existeixi

                if (session()->get('user_data')['domain'] == "xtec.cat") { // En cas que sigui professor es registra a la taula LOGIN i a la taula LOGIN_IN_ROL
                    
                    $centre = $centre_model->obtenirCentrePerCorreu($mail);
                    if ($centre != null) {

                        $session_data = session()->get('user_data');
                        if ($centre['taller'] == 0) {
                            $session_data['role'] = "centre_emissor";
                            $session_data['codi_centre'] = $centre['codi_centre'];
                        } else if ($centre['taller'] == 1) {
                            $session_data['role'] = "centre_reparador";
                            $session_data['codi_centre'] = $centre['codi_centre'];
                        }
                        session()->set('user_data', $session_data);

                        return redirect()->to(base_url('/tiquets'));
                    } else {
                        return redirect()->to(base_url('/loginSelect'));
                    }

                } else { // En cas que sigui alumne es comprova que existeixi a la taula LOGIN i ALUMNE
    
                    session()->destroy();
                    return redirect()->back();
                }

            } else { // En cas que el login existeixi
                
                $session_data = session()->get('user_data'); //Carreguem la informació de l'usuari
                
                // Obtenim el rol de l'usuari
                $id_login = $login_model->obtenirId($mail);
                $id_rol = $login_in_rol_model->obtenirRol($id_login);
                $rol = $rol_model->obtenirRol($id_rol);
                $session_data['role'] = $rol;

                if (session()->get('user_data')['domain'] == "xtec.cat") { // En cas que sigui professor
                    $codi_centre = $llista_admesos_model->existeixProfessor($mail)['codi_centre'];
                    if ($codi_centre == null) {
                        $codi_centre = $centre_model->obtenirCentrePerCorreu($mail)['codi_centre'];
                    }
                    $session_data['codi_centre'] = $codi_centre;
                } else { // En cas que sigui alumne
                    $session_data['codi_centre'] = $alumne_model->getAlumneByCorreu($session_data['mail']);
                }

                session()->set('user_data', $session_data); //Guardem la informació de l'usuari

                return redirect()->to(base_url('/tiquets'));
            }

        }
    }


    public function loginSelect()
    {
        $login_model = new LoginModel();
        $rol_model = new RolModel();
        $llista_admesos_model = new LlistaAdmesosModel();
        $login_in_rol_model = new LoginInRolModel();
        $centre_model = new CentreModel();

        $session_data = session()->get('user_data');

        if ($session_data != null) {

            $mail = session()->get('user_data')['mail'];
            $login = $login_model->obtenirLogin($mail);

    
            if ($login != null || session()->get('user_data')['domain'] == "xtec.cat") {
    
                if (session()->get('user_data')['domain'] == "xtec.cat") {
    
                    $professor = $llista_admesos_model->existeixProfessor($mail);
    
                    if ($professor == null) {
    
                        $centre_model = new CentreModel();
                        $array_centres = $centre_model->obtenirCentres();
                        $options_centres = "";
                        for ($i = 0; $i < sizeof($array_centres); $i++) {
                            $options_centres .= "<option value=\"" . $array_centres[$i]['codi_centre'] . " - " . $array_centres[$i]['nom_centre'] . "\">";
                            $options_centres .= $array_centres[$i]['nom_centre'];
                            $options_centres .= "</option>";
                        }
    
                        $data['centres'] = $options_centres;
    
                        $data['title'] = "login";
                        return view('logins' . DIRECTORY_SEPARATOR . 'loginSelect', $data);
    
                    } else {
    
                        $centre = $centre_model->obtenirCentre($professor['codi_centre']);
                        $session_data = session()->get('user_data');
                        if ($centre['taller'] == 0) {
                            $session_data['role'] = "centre_emissor";
                        } else if ($centre['taller'] == 1) {
    
                            if ($centre['login'] != $professor['correu_professor']) {
                                $session_data['role'] = "professor";
                            } else {
                                $session_data['role'] = "centre_reparador";
                            }
    
                        }
                        $session_data['codi_centre'] = $professor['codi_centre'];
                        session()->set('user_data', $session_data);
    
                        return redirect()->to(base_url('/tiquets'));
                    }
    
                } else {
    
                    $id_login = $login_model->obtenirId($mail);
                    $id_rol = $login_in_rol_model->obtenirRol($id_login);
    
                    $session_data = session()->get('user_data');
                    $session_data['role'] = $rol_model->obtenirRol($id_rol);;
                    session()->set('user_data', $session_data);
    
                    return redirect()->to(base_url('/tiquets'));
                }
    
            } else {
                return redirect()->to(base_url('/login'));
            }
        } else {
            return redirect()->to(base_url('/login'));
        }

    }

    public function loginSelect_post()
    {
        $llista_admesos_model = new LlistaAdmesosModel();
        $professor_model = new ProfessorModel();
        $centre_model = new CentreModel();
        $login_model = new LoginModel();
        $login_in_rol_model = new LoginInRolModel();
        $rol_model = new RolModel();

        $codi_centre = $this->request->getPost('centre_seleccionat');
        $codi_centre = trim(explode('-', (string) $codi_centre)[0]);

        $nom = session()->get('user_data')['nom'];
        $cognoms = session()->get('user_data')['cognoms'];
        $correu = session()->get('user_data')['mail'];
        $id_xtec = explode('@', $correu)[0];


        $centre = $centre_model->obtenirCentre($codi_centre);

        $session_data = session()->get('user_data');
        if ($centre['taller'] == 0) { // En cas que l'usuari hagi estat associat a un centre no reparador és de rol centre_emissor

            $session_data['role'] = "centre_emissor";
            $rol = $rol_model->obtenirIdRol("centre_emissor");
            $llista_admesos_model->addLlistaAdmesos($correu, date("Y-m-d H:i:s"), $codi_centre);
            $login_model->addLogin($correu, null);
            $id_login = $login_model->obtenirId($correu);
            $login_in_rol_model->addLoginInRol($id_login, $rol);

        } else if ($centre['taller'] == 1) { // En cas que l'usuari hagi estat associat a un centre reparador

            if ($centre['login'] != $correu) { // En cas que el correu sigui diferent del correu del centre, és de rol professor

                $session_data['role'] = "professor";
                $rol = $rol_model->obtenirIdRol("professor");
                $llista_admesos_model->addLlistaAdmesos($correu, date("Y-m-d H:i:s"), $codi_centre);
                $professor_model->addProfessor($id_xtec, $nom, $cognoms, $correu, $codi_centre);
                $login_model->addLogin($correu, null);
                $id_login = $login_model->obtenirId($correu);
                $login_in_rol_model->addLoginInRol($id_login, $rol);

            } else { // En cas que el correu sigui el mateix el rol és centre_reparador
                $session_data['role'] = "centre_reparador";
            }

        }

        $session_data['codi_centre'] = $codi_centre;
        session()->set('user_data', $session_data);

        return redirect()->to(base_url('/tiquets'));
    }

    public function logout()
    {
        session()->destroy(); // Tanquem la sessió
        return redirect()->to(base_url('/login')); // Redirigim al login
    }
}