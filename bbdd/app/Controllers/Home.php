<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function login(): string
    {
        $locale = $this->request->getLocale();
        $data['title'] = "login";
        $data['locale'] = $locale;
        return view('logins\loginGeneral', $data);
    }
    public function loginSelect(): string
    {
        $data['title'] = "login";
        return view('logins\loginSelect', $data);
    }

    /**
     * Funció que ens dirigeix cap al formulari per crear un tiquet
     *
     * @author Blai Burgués Vicente
     */
    public function createTiquet(): string 
    {
        $data['title'] = lang('general_lang.formulari_tiquet');
        return view('formularis\formulariTiquet', $data);
    }
}
