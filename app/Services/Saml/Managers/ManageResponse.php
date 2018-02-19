<?php

namespace App\Services\Saml\Managers;

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
use LightSaml\Model\Assertion\Conditions;
use LightSaml\Model\Assertion\AuthnContext;
use LightSaml\Context\Profile\MessageContext;
use LightSaml\Model\Assertion\AuthnStatement;
use LightSaml\Model\Assertion\AttributeStatement;
use LightSaml\Model\Assertion\AudienceRestriction;

class ManageResponse {

    public function prepare($message, $client)
    {
        $response = new Response();

        $this->setBasicInformations($response);
        $this->setRequestInformations($response, $message);
        $this->setClientInformations($response, $client);
        $this->setSignature($response);
        $this->setAssertions($response);

        return $response;
    }   

    protected function setBasicInformations(&$response)
    {
        $response->setID(Helper::generateID());
        $response->setIssueInstant(new \DateTime());
        $response->setIssuer(new Issuer('idp_issuer'));
        $response->setStatus(new Status(new StatusCode(SamlConstants::STATUS_SUCCESS)));;
    }

    protected function setRequestInformations(&$response, $message)
    {
        $response->setInResponseTo($message->getID());
        $response->setRelayState($message->getRelayState());
    }

    protected function setClientInformations(&$response, $client)
    {
        $response->setDestination($client->endpoint);        
    }

    protected function setSignature(&$response)
    {
        //
    }

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
                ->addAttribute((new Attribute(ClaimTypes::PPID, auth()->user()->id))->setFriendlyName('id'))
        );

        $assertion->addItem(
            (new AuthnStatement())
                ->setAuthnInstant(new \DateTime('-10 MINUTE'))
                ->setSessionIndex('_some_session_index')
                ->setAuthnContext((new AuthnContext())->setAuthnContextClassRef(SamlConstants::AUTHN_CONTEXT_PASSWORD_PROTECTED_TRANSPORT))
        );
    }

    public function build($message)
    {
        $bindingFactory = new BindingFactory();
        $postBinding = $bindingFactory->create(SamlConstants::BINDING_SAML2_HTTP_POST);
        $messageContext = new MessageContext();
        $messageContext->setMessage($message)->asResponse();

        return $postBinding->send($messageContext);
    }      
}
