<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LoginInRolModel;
use App\Models\LoginModel;
use App\Models\ProfessorModel;
use App\Models\CentreModel;
use App\Models\TipusDispositiuModel;

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
            $login_obtingut = $login_model->obtenirLogin($nom_login);

            if ($login_obtingut != null) { // En cas que existeixi

                // Obtenim la contrasenya del formulari
                $contrasenya = $this->request->getPost('sPssw');
                

                if (gettype($contrasenya) == "string") {
                    $password = $contrasenya;
                }

                $hash =$login_obtingut['contrasenya'];

                if (password_verify($password, $hash) && $password != "" && $password != null) { // Verifiquem que la contrasenya coincideixi amb la de la base de dades

                    $session_data['mail'] = $nom_login;
                    $session_data['nom'] = explode("@", (string) $nom_login)[0];
                    $session_data['cognoms']= "Cognom Exemple";
                    $session_data['domain'] = explode('@', $session_data['mail'])[1];
    
                    session()->set('user_data', $session_data);

                    if ($session_data['domain'] == "xtec.cat") {
                        return redirect()->to(base_url('/loginSelect'));
                    } else {
                        return redirect()->to(base_url('/registreTiquetProfessor'));
                    }
                    
                }

            }
        }

        return view('logins\loginGeneral', $data);
    }

    public function login()
    {
        $data['title'] = "login";

        $client = new \Google\Client();

        // LÍNIES AMB CREDENCIALS

        $client->setRedirectUri('http://localhost:8080/login'); //Define your Redirect Uri

        $client->addScope(\Google\Service\Oauth2::USERINFO_EMAIL);
        $client->addScope(\Google\Service\Oauth2::USERINFO_PROFILE);
        $client->addScope(\Google\Service\Oauth2::OPENID);
        $client->setAccessType('offline');

        if (isset($_GET["code"])) {
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

            if (!isset($token["error"])) {
                $client->setAccessToken($token['access_token']);

                session()->set('access_token', $token['access_token']);

                $oauth2 =new \Google\Service\Oauth2($client);

                $userInfo = $oauth2->userinfo->get();

                $session_data['mail']=$userInfo->getEmail();
                $session_data['nom']=$userInfo->getGivenName();
                $data['cognoms']=$userInfo->getFamilyName();
                $session_data['nomComplet']=$userInfo->getName();

                $session_data['domain'] = explode('@', $session_data['mail'])[1];

                session()->set('user_data', $session_data);
            }
        }
        $login_button = '';
       
        if (!session()->get('access_token') && !session()->get('user_data')) {
            $login_button = '<a class = "btn btn-outline-dark" href="' . $client->createAuthUrl() . '"><i class="fa-brands fa-google me-2"></i>' . lang("crud.buttons.enter_google") . '</a>';
            $data['login_button'] = $login_button;
            return view('logins/loginGeneral', $data);
        } else {
            $login_model = new LoginModel();
            $mail = session()->get('user_data')['mail'];

            if ($login_model->obtenirLogin($mail) == null) {
                $login_model->insertarLogin($mail);

                $id_login = $login_model->obtenirId($mail);
                $login_in_rol_model = new LoginInRolModel();

                if (session()->get('user_data')['domain'] == "xtec.cat") {
                    $login_in_rol_model->addLoginInRol($id_login, 2);
                } else {
                    $login_in_rol_model->addLoginInRol($id_login, 1);
                }
                
            }

            if (isset(session()->get('user_data')['domain'])) {
                if (session()->get('user_data')['domain'] == "xtec.cat") {
                    return redirect()->to(base_url('/loginSelect'));
                } else {
                    return redirect()->to(base_url('/registreTiquetProfessor'));
                }

            } else {
                return redirect()->to(base_url('/registreTiquetProfessor'));
            }
            
        }
    }


    public function loginSelect()
    {
        $login_model = new LoginModel();
        $mail = session()->get('user_data')['mail'];

        $login = $login_model->obtenirLogin($mail);
        
        if ($login != null) {

            $login_in_rol_model = new LoginInRolModel();
            $id_rol = $login_in_rol_model->obtenirRol($login['id_login']);

            if ($id_rol == 2) {
                $professor_model = new ProfessorModel();
                $professor = $professor_model->obtenirProfessor($mail);

                if ($professor == null) {
                    $centre_model = new CentreModel();
                    $array_centres = $centre_model->obtenirCentres();
                    $array_centres_noms = [];
            
                    $options_tipus_dispositius = "";
                    for ($i = 0; $i < sizeof($array_centres); $i++) {
                        $options_tipus_dispositius .= "<option value=" . ($i+1) . ">";
                        $options_tipus_dispositius .= $array_centres[$i]['nom_centre'];
                        $options_tipus_dispositius .= "</option>";
                        $array_centres_tot[$i] = $array_centres[$i];
                    }

                    session()->setFlashdata('array_centres_tot', $array_centres_tot);
            
                    $data['centres'] = $options_tipus_dispositius;

                    $data['title'] = "login";
                    return view('logins\loginSelect', $data);
                } else {
                    return redirect()->to(base_url('/registreTiquetProfessor'));
                }

            } else {
                return redirect()->to(base_url('/registreTiquetProfessor'));
            }
            
        } else {
            return redirect()->to(base_url('/login'));
        }

    }

    public function loginSelect_post()
    {
        $array_centres_tot = session()->getFlashdata('array_centres_tot');
        $centre_seleccionat_value = $this->request->getPost('centre_seleccionat');
        $centre_seleccionat = $array_centres_tot[$centre_seleccionat_value - 1];
        $codi_centre = $centre_seleccionat['codi_centre'];

        $professor_model = new ProfessorModel();

        
        $nom = session()->get('user_data')['nom'];
        $cognoms = session()->get('user_data')['cognoms'];
        $correu = session()->get('user_data')['mail'];
        $id_xtec = explode('@', $correu)[0];

        $professor_model->addProfessor($id_xtec, $nom, $cognoms, $correu, $codi_centre);

        return redirect()->to(base_url('/registreTiquetProfessor'));
    }

    /**
     * Funció que ens dirigeix cap al formulari per crear un tiquet
     *
     * @author Blai Burgués Vicente
     */
    public function createTiquet(): string 
    {
        $tipus_dispositius = new TipusDispositiuModel;
        $array_tipus_dispositius = $tipus_dispositius->getTipusDispositius();
        $array_tipus_dispositius_nom = [];

        $options_tipus_dispositius = "";
        for ($i = 0; $i < sizeof($array_tipus_dispositius); $i++) {
            $options_tipus_dispositius .= "<option value=" . ($i+1) . ">";
            $options_tipus_dispositius .= $array_tipus_dispositius[$i]['nom_tipus_dispositiu'];
            $options_tipus_dispositius .= "</option>";
            $array_tipus_dispositius_nom[$i] = $array_tipus_dispositius[$i]['nom_tipus_dispositiu'];
        }

        $data['tipus_dispositius'] = $options_tipus_dispositius;
        $data['json_tipus_dispositius'] = json_encode($array_tipus_dispositius_nom);



        // TREURE AIXÒ
        session()->set(['codi_centre' => '25008443']);
        $codi_centre = session()->get('codi_centre');

        $centre = new CentreModel;
        $data['nom_persona_contacte_centre'] = $centre->obtenirNomResponsable($codi_centre);
        $data['correu_persona_contacte_centre'] = $centre->obtenirCorreuResponsable($codi_centre);

        $data['title'] = lang('general_lang.formulari_tiquet');
        return view('formularis\formulariTiquet', $data);
    }

    public function logout()
    {
        session()->destroy(); // Tanquem la sessió
        return redirect()->to(base_url('/login')); // Redirigim al login
    }
}
