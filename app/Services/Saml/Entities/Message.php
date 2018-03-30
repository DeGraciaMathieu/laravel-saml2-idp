<?php

namespace App\Services\Saml\Entities;

use LightSaml\Model\Protocol\SamlMessage;

class Message {

	protected $message;
	protected $validSignature = false;

	public function __construct(SamlMessage $message)
	{
		$this->message = $message;
	}

	public function getSignature()
	{
		return $this->message->getSignature();
	}

	public function getIssuer()
	{
		return $this->message->getIssuer()->getValue();
	}

	public function getId()
	{
		return $this->message->getId();		
	}

	public function getRelayState()
	{
		return $this->message->getRelayState();		
	}	

	public function getDestination()
	{
		return $this->message->getDestination();		
	}	

	public function verifiedSignature($status)
	{
		$this->validSignature = $status;
	}	

	public function getSignatureStatus()
	{
		return (bool) $this->validSignature;
	}	
}
