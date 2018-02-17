<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

class SamlController extends Controller
{
    public function consumeRequest(Request $request)
    {
        return app('App\Services\Saml')->consume($request);
    }

    public function proceedConnexion(Requests\ProceedConnexionRequest $request)
    {
        if ($message = app('App\Services\Saml')->getSavedmessage()) {

            app('App\Services\Saml')->deleteSavedmessage();

            return app('App\Services\Saml')->consumeMessage($message);
        }

        return redirect('home');
    }
}
