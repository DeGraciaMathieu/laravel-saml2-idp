<?php

namespace App\Services\Saml\Managers;

use LightSaml\Binding\BindingFactory;
use LightSaml\Model\Protocol\SamlMessage;
use LightSaml\Model\Protocol\AuthnRequest;
use LightSaml\Context\Profile\MessageContext;
use App\Exceptions\UnexpectedMessageTypeException;

class ManageRequest {

    /**
     * Deserialize an SAML AuthnRequest
     * @param  \Illuminate\Http\Request $request 
     * @throws \App\Exceptions\UnexpectedMessageTypeException
     * @return \LightSaml\Model\Protocol\SamlMessage
     */
    public function deserializeAuthnRequest($request)
    {
        $message = $this->deserialize($request);

        $this->checkMessageType($message, AuthnRequest::class);

        return $message;
    }

    /**
     * Deserialize an SAML Request
     * @param  \Illuminate\Http\Request $request 
     * @return \LightSaml\Model\Protocol\SamlMessage
     */
    protected function deserialize($request)
    {
        $bindingFactory = new BindingFactory();

        $binding = $bindingFactory->getBindingByRequest($request);

        $messageContext = new MessageContext();

        $binding->receive($request, $messageContext);

        return $messageContext->getMessage();
    }

    /**
     * Check the request type
     * @param \LightSaml\Model\Protocol\SamlMessage $message 
     * @param string $type    
     * @throws \App\Exceptions\UnexpectedMessageTypeException
     * @return void
     */
    protected function checkMessageType(SamlMessage $message, $type)
    {
        $class = get_class($message);

        if ($class != $type) {
            throw new UnexpectedMessageTypeException();
        }
    }   
}
