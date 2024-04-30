<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\LoginModel;
use App\Models\LoginInRolModel;
use App\Models\ProfessorModel;

class ValidaUsuarisFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {

        if ($arguments == null) {

            if ((session()->get('access_token') == null && session()->get('user_data') == null) || !isset(session()->get('user_data')['role'])) {
                return redirect()->to(base_url('login'));
            } else {
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
                            return redirect()->to(base_url('loginSelect'));
                        } 
                    }
                }
            }

        } else {
            if (!in_array(session()->get('nom'), $arguments)) {
                session()->setFlashdata('error', 'Failed! User access no permited');
                return redirect()->back();
            }
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
