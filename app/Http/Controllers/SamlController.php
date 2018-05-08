<?php

namespace App\Http\Controllers;

use SamlService;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SamlController extends Controller
{
    public function consumeRequest(Request $request)
    {
        $message = SamlService::consumeAuthnRequest($request);

        SamlService::checkMessageSignature($message);

        $message->verifiedSignature(true);

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
