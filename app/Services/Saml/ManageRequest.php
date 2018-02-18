<?php

namespace App\Services\Saml;

use LightSaml\Binding\BindingFactory;
use LightSaml\Context\Profile\MessageContext;

class ManageRequest {

	public function deserialize($request)
	{
        $bindingFactory = new BindingFactory();

        $binding = $bindingFactory->getBindingByRequest($request);

        $messageContext = new MessageContext();

        $binding->receive($request, $messageContext);

        return $messageContext->getMessage();
	}
}
