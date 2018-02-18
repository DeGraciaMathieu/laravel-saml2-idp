<?php

namespace App\Services\Saml;

use LightSaml\Model\Protocol\SamlMessage;

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
