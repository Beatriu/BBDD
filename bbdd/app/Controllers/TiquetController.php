<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class TiquetController extends BaseController
{
    public function viewTiquet($id)
    {
        return view('tiquet' . DIRECTORY_SEPARATOR . 'vistaTiquet');
    }
}
