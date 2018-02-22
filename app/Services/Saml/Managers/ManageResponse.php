<?php

namespace App\Services\Saml\Managers;

use App\Client;
use LightSaml\Helper;
use LightSaml\ClaimTypes;
use LightSaml\SamlConstants;
use LightSaml\Model\Protocol\Status;
use LightSaml\Model\Assertion\Issuer;
use LightSaml\Binding\BindingFactory;
use LightSaml\Model\Assertion\NameID;
use LightSaml\Model\Assertion\Subject;
use LightSaml\Model\Protocol\Response;
use LightSaml\Model\Assertion\Assertion;
use LightSaml\Model\Assertion\Attribute;
use LightSaml\Model\Protocol\StatusCode;
use LightSaml\Model\Protocol\SamlMessage;
use LightSaml\Model\Assertion\Conditions;
use LightSaml\Model\Assertion\AuthnContext;
use LightSaml\Context\Profile\MessageContext;
use LightSaml\Model\Assertion\AuthnStatement;
use LightSaml\Model\Assertion\AttributeStatement;
use LightSaml\Model\Assertion\AudienceRestriction;

class ManageResponse {

    /**
    * Get the saved SAML Message
    * @param \LightSaml\Model\Protocol\SamlMessage $message
    * @param \App\Client $client
    * @return
    */
    public function prepare(SamlMessage $message, Client $client)
    {
        $response = new Response();

        $this->setBasicInformations($response);
        $this->setRequestInformations($response, $message);
        $this->setClientInformations($response, $client);
        $this->setSignature($response);
        $this->setAssertions($response);

        return $response;
    }   

    /**
    * 
    * @param \LightSaml\Model\Protocol\Response &$response
    */
    protected function setBasicInformations(&$response)
    {
        $response->setID(Helper::generateID());
        $response->setIssueInstant(new \DateTime());
        $response->setIssuer(new Issuer('idp_issuer'));
        $response->setStatus(new Status(new StatusCode(SamlConstants::STATUS_SUCCESS)));;
    }

    /**
    * 
    * @param \LightSaml\Model\Protocol\Response &$response
    * @param \LightSaml\Model\Protocol\SamlMessage $message
    */
    protected function setRequestInformations(&$response, $message)
    {
        $response->setInResponseTo($message->getID());
        $response->setRelayState($message->getRelayState());
    }

    /**
    * 
    * @param \LightSaml\Model\Protocol\Response &$response
    * @param \App\Client $client
    */
    protected function setClientInformations(&$response, $client)
    {
        $response->setDestination($client->endpoint);        
    }

    /**
    * 
    * @param \LightSaml\Model\Protocol\Response &$response
    */
    protected function setSignature(&$response)
    {
        //
    }

    /**
    * 
    * @param \LightSaml\Model\Protocol\Response &$response
    */
    protected function setAssertions(&$response)
    {
        $response->addAssertion($assertion = new Assertion());

        $assertion->setConditions(
            (new Conditions())
                ->setNotBefore(new \DateTime())
                ->setNotOnOrAfter(new \DateTime('+1 MINUTE'))
                ->addItem(new AudienceRestriction(['client.entity_id']))
        );

        $assertion->addItem(
            (new AttributeStatement())
                ->addAttribute((new Attribute(ClaimTypes::PPID, auth()->user()->uuid))->setFriendlyName('uuid'))
        );

        $assertion->addItem(
            (new AttributeStatement())
                ->addAttribute((new Attribute(ClaimTypes::EMAIL_ADDRESS, auth()->user()->email))->setFriendlyName('email'))
        );

        $assertion->addItem(
            (new AuthnStatement())
                ->setAuthnInstant(new \DateTime('-10 MINUTE'))
                ->setSessionIndex('_some_session_index')
                ->setAuthnContext((new AuthnContext())->setAuthnContextClassRef(SamlConstants::AUTHN_CONTEXT_PASSWORD_PROTECTED_TRANSPORT))
        );
    }

    /**
    * 
    * @param \LightSaml\Model\Protocol\SamlMessage $message
    */
    public function build($message)
    {
        $bindingFactory = new BindingFactory();
        $postBinding = $bindingFactory->create(SamlConstants::BINDING_SAML2_HTTP_POST);
        $messageContext = new MessageContext();
        $messageContext->setMessage($message)->asResponse();

        return $postBinding->send($messageContext);
    }      
}
