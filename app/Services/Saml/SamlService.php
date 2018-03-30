<?php

namespace App\Services\Saml;

use Illuminate\Http\Request;
use App\Services\Saml\Tools;
use App\Services\Saml\Entities\Message;

class SamlService {

    /**
     * Consumes a saml request
     * @param  \Illuminate\Http\Request $request
     * @return \App\Services\Saml\Entities\Message
     */
    public function consumeAuthnRequest(Request $request)
    {
        $message = Tools\Request::deserializeAuthnRequest($request);

        return new Entities\Message($message);
    }

    /**
     * Verify the signature of a message
     * @param  \App\Services\Saml\Entities\Message $messageEntity
     * @return void
     */
    public function checkMessageSignature(Message $messageEntity)
    {
        $signature = $messageEntity->getSignature();

        $client = Tools\Client::getByEntityId($messageEntity->getIssuer());

        Tools\Signature::validateSignature($signature, $client->certificate);
    }

    /**
     * Sends a Saml response from a message
     * @param  \App\Services\Saml\Entities\Message $messageEntity
     * @return \LightSaml\Binding\SamlPostResponse
     */
    public function proceedSamlResponse(Message $messageEntity)
    {
        Tools\Signature::signatureHasBeenVerified($messageEntity);

        $client = Tools\Client::getByEntityId($messageEntity->getIssuer());

        $response = Tools\Response::prepare($messageEntity, $client);

        return Tools\Response::proceed($response);
    }

    /**
     * Keep a message in session
     * @param  \App\Services\Saml\Entities\Message $messageEntity
     * @return void
     */
    public function keepMessage(Message $messageEntity)
    {
        Tools\Message::save($messageEntity);
    }

    /**
     * Retrieve and delete a message saved in session
     * @return \App\Services\Saml\Entities\Message
     */
    public function retrievePendingMessage()
    {
        $messageEntity = $this->getPendingMessage();

        Tools\Message::deleteSaved();

        return $messageEntity;
    }    

    /**
     * Check if a message is saved in session
     * @return boolean
     */
    public function samlRequestIsPending()
    {
        return Tools\Message::hasSaved();
    }

    /**
     * Get the endpoint of a message saved in session
     * @throws \App\Exceptions\NeitherSessionMessage
     * @return [type] [description]
     */
    public function getPendingSamlRequestEndpoint()
    {
        $messageEntity = $this->getPendingMessage(); 

        if (! $messageEntity) {
            throw new NeitherSessionMessage();
        }

        $client = Tools\Client::getByEntityId($messageEntity->getIssuer());

        return $client->endpoint;
    }

    /**
    * Retrieve a message saved in session 
    * @return \App\Services\Saml\Entities\Message
    */
    protected function getPendingMessage()
    {
        return Tools\Message::getSaved();
    }  
}
