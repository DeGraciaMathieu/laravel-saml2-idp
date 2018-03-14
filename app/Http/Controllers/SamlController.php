<?php

namespace App\Http\Controllers;

use SamlService;
use App\Http\Requests;
use Illuminate\Http\Request;

class SamlController extends Controller
{
    public function consumeRequest(Request $request)
    {
        $message = SamlService::consumeAuthnRequest($request);

        SamlService::checkMessageSignature($message);

        SamlService::keepMessage($message);

        if (auth()->guest()) {
            return redirect()->route('login');
        }

        return SamlService::proceedSamlResponse($message);       
    }

    public function proceedConnexion(Requests\ProceedConnexionRequest $request)
    {   
        if ($message = SamlService::retrievePendingMessage()) {

            return SamlService::proceedSamlResponse($message);
        }

        return redirect('home');
    }
}
