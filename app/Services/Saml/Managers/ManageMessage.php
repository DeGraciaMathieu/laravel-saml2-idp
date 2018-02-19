<?php

namespace App\Services\Saml\Managers;

use Exception;
use App\Client;
use LightSaml\Model\Protocol\SamlMessage;
use App\Services\Saml\SamlServiceConstants;

class ManageMessage {

	public function save(SamlMessage $message)
	{
		return session()->put(SamlServiceConstants::SAVED_MESSAGE, $message);
	}

	public function getSaved()
	{
		return session()->get(SamlServiceConstants::SAVED_MESSAGE);
	}

	public function deleteSaved()
	{
		session()->forget(SamlServiceConstants::SAVED_MESSAGE);
	}
}
