<?php

namespace App\Http\Controllers;

use SamlService;
use App\Http\Requests;
use Illuminate\Http\Request;

class SamlController extends Controller
{
    public function consumeRequest(Request $request)
    {
        return SamlService::consume($request);
    }

    public function proceedConnexion(Requests\ProceedConnexionRequest $request)
    {
        if ($message = SamlService::getSavedmessage()) {

            SamlService::deleteSavedmessage();

            return SamlService::consumeMessage($message);
        }

        return redirect('home');
    }
}
