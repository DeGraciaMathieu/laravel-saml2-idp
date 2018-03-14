<?php

namespace App\Services\Saml\Tools;

use LightSaml\Binding\BindingFactory;
use LightSaml\Model\Protocol\SamlMessage;
use LightSaml\Model\Protocol\AuthnRequest;
use LightSaml\Context\Profile\MessageContext;
use App\Exceptions\UnexpectedMessageTypeException;

class Request {

    /**
     * Deserialize an SAML AuthnRequest
     * @param  \Illuminate\Http\Request $request 
     * @throws \App\Exceptions\UnexpectedMessageTypeException
     * @return \LightSaml\Model\Protocol\SamlMessage
     */
    public static function deserializeAuthnRequest($request)
    {
        $message = self::deserialize($request);

        self::checkMessageType($message, AuthnRequest::class);

        return $message;
    }

    /**
     * Deserialize an SAML Request
     * @param  \Illuminate\Http\Request $request 
     * @return \LightSaml\Model\Protocol\SamlMessage
     */
    protected static function deserialize($request)
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
    protected static function checkMessageType(SamlMessage $message, $type)
    {
        $class = get_class($message);

        if ($class != $type) {
            throw new UnexpectedMessageTypeException();
        }
    }   
}
