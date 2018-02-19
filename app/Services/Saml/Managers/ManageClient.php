<?php

namespace App\Services\Saml\Managers;

use App\Client;
use LightSaml\Model\Protocol\SamlMessage;
use App\Exceptions\MissingIssuerException;

class ManageClient {

	/**
	 * Retrieve client by the issuer of a SAML Message
	 * @param  \LightSaml\Model\Protocol\SamlMessage $message
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \App\Exceptions\MissingIssuerException
	 * @return \App\Client
	 */
	public function getByMessageIssuer(SamlMessage $message)
	{
		$entityId = $message->getIssuer()->getValue();

		if (! $entityId) {
			throw new MissingIssuerException();
		}

		return $this->getByEntityId($entityId);
	}

	/**
	 * Retrieve client by the entityId
	 * @param  string $entityId 
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
	 * @return \App\Client
	 */
	public function getByEntityId($entityId)
	{
		return Client::where('entity_id', $entityId)->firstOrFail();
	}
}
