<?php

namespace App\Services\Saml\Tools;

use Exception;
use LightSaml\Credential\KeyHelper;
use LightSaml\Credential\X509Certificate;
use App\Exceptions\UnexpectedSignatureException;

class Signature {

    public static function validateSign($signature, $certificate)
    {
        try {

            $X509Certificate = new X509Certificate();
            
            $certificate = $X509Certificate->setData($certificate);

            $publicKey = KeyHelper::createPublicKey($certificate);

            $signature->validate($publicKey);
            
        } catch (Exception $e) {
            throw new UnexpectedSignatureException();
        }        
    }
}
