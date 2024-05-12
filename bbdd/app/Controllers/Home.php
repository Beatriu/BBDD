<?php

namespace App\Controllers;

use App\Models\CentreModel;
use App\Models\EstatModel;
use App\Models\TipusDispositiuModel;
use App\Models\TiquetModel;

class Home extends BaseController
{
    protected $helpers = ['form', 'file', 'filesystem'];

    public function index() {
        return redirect()->to(base_url('/login'));
    }

    public function canviLanguage() {
        if (session()->language == 'ca') {
            $this->request->setlocale('es');
            session()->language = 'es';
        } else if (session()->language == 'es') {
            $this->request->setlocale('ca');
            session()->language = 'ca';
        }

        return redirect()->back()->withInput();
    }

}
