<?php

namespace App\Services\Saml\Tools;

use App\Services\Saml\Entities\Message as MessageEntity;
use App\Services\Saml\SamlServiceConstants;

class Message {

	public static function save(MessageEntity $message)
	{
		session()->put(SamlServiceConstants::SAVED_MESSAGE, $message);
	}

	public static function getSaved()
	{
		return session()->get(SamlServiceConstants::SAVED_MESSAGE);
	}

	public static function deleteSaved()
	{
		session()->forget(SamlServiceConstants::SAVED_MESSAGE);
	}

	public static function hasSaved()
	{
		return session()->has(SamlServiceConstants::SAVED_MESSAGE);
	}	
}
