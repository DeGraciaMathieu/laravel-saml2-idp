<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use LightSaml\Model\Protocol\AuthnRequest;

class SamlHttpTest extends TestCase
{
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(302);

        $response->assertRedirect('/login');
    }

    public function testCallConsumeWithGuestAndSamlRequest()
    {
        $client = factory(\App\Client::class)->create();

        $authnRequest = $this->buildRequest($this->getAuthnRequest($client));

        $response = $this->get($authnRequest->getTargetUrl());   

        $response->assertStatus(302);

        $response->assertRedirect('/login');   

        $samlMessage = session()->get('saml_message');

        $this->assertInstanceOf(AuthnRequest::class, $samlMessage);        
    }

    public function testCallConsumeWithAuthAndSamlRequest()
    {
        $user = factory(\App\User::class)->create();
        $client = factory(\App\Client::class)->create();

        $authnRequest = $this->buildRequest($this->getAuthnRequest($client));

        $response = $this->actingAs($user)->get($authnRequest->getTargetUrl());   

        $response->assertStatus(200);

        $this->assertEquals($response->getDestination(), $client->endpoint);

        $samlMessage = session()->get('saml_message');

        $this->assertInstanceOf(AuthnRequest::class, $samlMessage);
    }

    public function testCallProceedConnexionWithGuest()
    {
        $client = factory(\App\Client::class)->create();

        $authnRequest = $this->buildRequest($this->getAuthnRequest($client));

        $response = $this->get('/saml/proceed-connexion');   

        $response->assertStatus(302);

        $response->assertRedirect('/login');                
    }

    public function testCallProceedConnexionWithAuth()
    {
        $user = factory(\App\User::class)->create();

        $response = $this->actingAs($user)->get('/saml/proceed-connexion');

        $response->assertStatus(302);

        $response->assertRedirect('/home');                
    }

    public function testCallProceedConnexionWithAuthAndSamlMessage()
    {
        $user = factory(\App\User::class)->create();
        $client = factory(\App\Client::class)->create();

        $authnRequest = $this->actingAs($user)->get('/saml/proceed-connexion');

        session()->put('saml_message', $this->getAuthnRequest($client));

        $response = $this->get('/saml/proceed-connexion');   

        $response->assertStatus(200);

        $this->assertEquals($response->getDestination(), $client->endpoint);

        $this->assertNull(session()->get('saml_message'));               
    }

    protected function getAuthnRequest($client) 
    {
        $authnRequest = new \LightSaml\Model\Protocol\AuthnRequest();

        $authnRequest->setID(\LightSaml\Helper::generateID());
        $authnRequest->setIssuer(new \LightSaml\Model\Assertion\Issuer($client->entity_id));
        $authnRequest->setDestination('/saml/consume');
        $authnRequest->setRelayState('setRelayState');

        return $authnRequest;          
    }

    protected function buildRequest($authnRequest)
    {
        $bindingFactory = new \LightSaml\Binding\BindingFactory();
        $redirectBinding = $bindingFactory->create(\LightSaml\SamlConstants::BINDING_SAML2_HTTP_REDIRECT);
        $messageContext = new \LightSaml\Context\Profile\MessageContext();
        $messageContext->setMessage($authnRequest);
        $httpResponse = $redirectBinding->send($messageContext);

        return $httpResponse;        
    }
}
