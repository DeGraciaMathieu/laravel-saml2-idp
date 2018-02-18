<?php

namespace App\Services\Saml;

use App\Client;
use LightSaml\Model\Protocol\SamlMessage;

class ManageClient {

	public function getByIssuer($message)
	{
		$entityId = $message->getIssuer()->getValue();

		return $this->getByEntityId($entityId);
	}

	public function getByEntityId($entityId)
	{
		return Client::where('entity_id', $entityId)->firstOrFail();
	}
}
