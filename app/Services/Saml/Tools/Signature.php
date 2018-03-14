<?php

namespace App\Services\Saml\Tools;

use Exception;
use LightSaml\Credential\KeyHelper;
use App\Services\Saml\Entities\Message;
use LightSaml\Credential\X509Certificate;
use App\Exceptions\UnexpectedSignatureException;
use App\Exceptions\UnverifiedMessageSignatureException;

class Signature {

    public static function validateSignature($signature, $certificate)
    {
        try {

            $X509Certificate = new X509Certificate();
            
            $certificate = $X509Certificate->setData($certificate);

            $publicKey = KeyHelper::createPublicKey($certificate);

            $signature->validate($publicKey);
            
            return true;

        } catch (Exception $e) {
            throw new UnexpectedSignatureException();
        }        
    }

    public static function signatureHasBeenVerified(Message $messageEntity)
    {
        if (! $messageEntity->getSignatureStatus()) {
            throw new UnverifiedMessageSignatureException();
        }  

        return true;
    }    
}
