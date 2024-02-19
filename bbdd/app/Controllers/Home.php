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
}
