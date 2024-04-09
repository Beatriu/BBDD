<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        
        // $session = \Config\Services::session();

        // $this->$session = \Config\Services::session();

       //Lang by User


         // FER IF: SI HI HA UNA $SESSION CREADA DEL LANG,
         // CANVIAR L'IDIOMA A L'ESPECIFICAT EN AQUELLA VARIABLE 
         // -> SET LOCALE AMB EL VALOR QUE HI HAGI EN AQUELLA $SESSION

         if (isset(session()->language)) {
            $this->request->setlocale(session()->language);
        } else {
            $this->request->setlocale('ca');
        }

        //Fi Lang by User
    }

    public function canviLanguage() {
        if (session()->language == 'ca') {
            $this->request->setlocale('es');
        } else if (session()->language == 'es') {
            $this->request->setlocale('ca');
        }
    }
}
