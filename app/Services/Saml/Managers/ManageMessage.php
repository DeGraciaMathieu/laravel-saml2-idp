<?php

namespace App\Services\Saml\Managers;

use Exception;
use App\Client;
use LightSaml\Model\Protocol\SamlMessage;
use App\Services\Saml\SamlServiceConstants;

class ManageMessage {

    /**
    * Save the SAML Message
    * @param  \LightSaml\Model\Protocol\SamlMessage $message
    */
	public function save(SamlMessage $message)
	{
		session()->put(SamlServiceConstants::SAVED_MESSAGE, $message);
	}

    /**
    * Get the saved SAML Message
    * @return  \LightSaml\Model\Protocol\SamlMessage $message
    */
	public function getSaved()
	{
		return session()->get(SamlServiceConstants::SAVED_MESSAGE);
	}

    /**
    * Delete the saved SAML Message
    * @return void
    */
	public function deleteSaved()
	{
		session()->forget(SamlServiceConstants::SAVED_MESSAGE);
	}
}
