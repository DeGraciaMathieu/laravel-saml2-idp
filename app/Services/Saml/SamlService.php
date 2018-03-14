<?php

namespace App\Services\Saml;

use App\Client;
use Illuminate\Http\Request;
use LightSaml\Model\Protocol\SamlMessage;
use App\Services\Saml\Managers\ManageClient;
use App\Services\Saml\Managers\ManageRequest;
use App\Services\Saml\Managers\ManageMessage;
use App\Services\Saml\Managers\ManageResponse;
use App\Services\Saml\Managers\ManageSignature;
use App\Services\Saml\Tools;
use App\Services\Saml\Entities\Message;

class SamlService {

    public function consumeAuthnRequest(Request $request)
    {
        $message = Tools\Request::deserializeAuthnRequest($request);

        return new Entities\Message($message);
    }

    public function checkMessageSignature(Message $messageEntity)
    {
        $signature = $messageEntity->getSignature();

        $client = Tools\Client::getByEntityId($messageEntity->getIssuer());

        return Tools\Signature::validateSign($signature, $client->certificate);
    }

    public function proceedSamlResponse(Message $messageEntity)
    {
        $client = Tools\Client::getByEntityId($messageEntity->getIssuer());

        $response = Tools\Response::prepare($messageEntity, $client);

        return Tools\Response::proceed($response);
    }

    public function keepMessage(Message $messageEntity)
    {
        Tools\Message::save($messageEntity);
    }

    public function retrievePendingMessage()
    {
        $messageEntity = $this->getPendingMessage();

        Tools\Message::deleteSaved();

        return $messageEntity;
    }    

    public function samlRequestIsPending()
    {
        return Tools\Message::hasSaved();
    }

    public function getPendingSamlRequestEndpoint()
    {
        $messageEntity = $this->getPendingMessage(); 

        if (! $messageEntity) {
            throw new NoPendingSamlRequestException();
        }

        $client = Tools\Client::getByEntityId($messageEntity->getIssuer());

        return $client->endpoint;
    }

    /**
    * @return \App\Services\Saml\Entities\Message
    */
    protected function getPendingMessage()
    {
        return Tools\Message::getSaved();
    }  
}
