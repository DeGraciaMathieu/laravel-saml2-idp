<?php

namespace App\Services\Saml;

use App\Client;
use Illuminate\Http\Request;
use LightSaml\Model\Protocol\SamlMessage;
use App\Services\Saml\Managers\ManageClient;
use App\Services\Saml\Managers\ManageRequest;
use App\Services\Saml\Managers\ManageMessage;
use App\Services\Saml\Managers\ManageResponse;
use App\Services\Saml\Managers\manageSignature;

class SamlService {

    protected $manageRequest;
    protected $manageResponse;
    protected $manageMessage;
    protected $manageClient;
    protected $manageSignature;

    public function __construct(ManageRequest $manageRequest, ManageResponse $manageResponse, ManageMessage $manageMessage, ManageClient $manageClient, ManageSignature $manageSignature)
    {
        $this->manageRequest = $manageRequest;
        $this->manageResponse = $manageResponse;
        $this->manageMessage = $manageMessage;
        $this->manageClient = $manageClient;
        $this->manageSignature = $manageSignature;
    }

    /**
     * Consume SAML AuthnRequest
     * @param  Request $request [description]
     * @throws \App\Exceptions\UnexpectedSignatureException
     * @return [type]           [description]
     */
    public function consume(Request $request)
    {
        $message = $this->manageRequest->deserializeAuthnRequest($request);

        $client = $this->getClientByMessage($message);

        $this->manageSignature->validate($message, $client);

        $this->manageMessage->save($message);

        return $this->consumeMessage($message, $client);
    }

    /**
     * [consumeMessage description]
     * @param  \LightSaml\Model\Protocol\SamlMessage $message
     * @param  \App\Client $client
     * @return [type]               [description]
     */
    public function consumeMessage(SamlMessage $message, Client $client)
    {
        if (auth()->guest()) {
            return redirect()->route('login');
        }

        $response = $this->manageResponse->prepare($message, $client);

        return $this->manageResponse->build($response);
    }

    /**
     * [getSavedmessage description]
     * @return void
     */
    public function getSavedmessage()
    {
        return $this->manageMessage->getSaved();
    }

    /**
     * [getSavedmessage description]
     * @return void
     */
    public function deleteSavedMessage()
    {
        $this->manageMessage->deleteSaved();
    }	

    /**
     * [getClientByMessage description]
     * @param  \LightSaml\Model\Protocol\SamlMessage $message
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \App\Exceptions\MissingIssuerException
     * @return \App\Client
     */
    public function getClientByMessage(SamlMessage $message)
    {
        return $this->manageClient->getByMessageIssuer($message);
    }
}
