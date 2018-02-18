<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

class SamlController extends Controller
{
    public function consumeRequest(Request $request)
    {
        return app('App\Services\Saml\SamlService')->consume($request);
    }

    public function proceedConnexion(Requests\ProceedConnexionRequest $request)
    {
        if ($message = app('App\Services\Saml\SamlService')->getSavedmessage()) {

            app('App\Services\Saml\SamlService')->deleteSavedmessage();

            return app('App\Services\Saml\SamlService')->consumeMessage($message);
        }

        return redirect('home');
    }
}
