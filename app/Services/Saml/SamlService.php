<?php

namespace App\Services\Saml;

use Illuminate\Http\Request;
use LightSaml\Model\Protocol\SamlMessage;

class SamlService {

	protected $manageRequest;
	protected $manageResponse;
	protected $manageMessage;

	public function __construct(ManageRequest $manageRequest, ManageResponse $manageResponse, ManageMessage $manageMessage)
	{
		$this->manageRequest = $manageRequest;
		$this->manageResponse = $manageResponse;
		$this->manageMessage = $manageMessage;
	}

	public function consume(Request $request)
	{
		$message = $this->manageRequest->deserialize($request);

		$this->manageMessage->save($message);

		return $this->consumeMessage($message);
	}

	public function consumeMessage(SamlMessage $message)
	{
		// validate sign

        if (auth()->guest()) {
            return redirect()->route('login');
        }
        
		$response = $this->manageResponse->prepare($message);

		return $this->manageResponse->build($response);
	}

	public function getSavedmessage()
	{
		return $this->manageMessage->getSaved();
	}

	public function deleteSavedmessage()
	{
		$this->manageMessage->deleteSaved();
	}	
}
