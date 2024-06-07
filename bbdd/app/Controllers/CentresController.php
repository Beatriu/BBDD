<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class CentresController extends BaseController
{
    public function registreCentres()
    {
        
        return view('registres'. DIRECTORY_SEPARATOR .'registreCentres');
    }
}
