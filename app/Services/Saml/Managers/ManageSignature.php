<?php

namespace App\Services\Saml\Managers;

use Exception;
use App\Client;
use LightSaml\Credential\X509Certificate;
use LightSaml\Model\Protocol\SamlMessage;
use App\Exceptions\UnexpectedSignatureException;
use LightSaml\Credential\KeyHelper;

class ManageSignature {

	public function validate(SamlMessage $message, Client $client)
	{
		try {

			$X509Certificate = new X509Certificate();

			$certificate = $X509Certificate->setData($client->certificate);

			$publicKey = KeyHelper::createPublicKey($certificate);

			$signature = $message->getSignature();
			
			$signature->validate($publicKey);
			
		} catch (Exception $e) {
			throw new UnexpectedSignatureException();
		}
	}
}
