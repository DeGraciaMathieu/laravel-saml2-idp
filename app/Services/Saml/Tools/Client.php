<?php

namespace App\Services\Saml\Tools;

class Client {

    public static function getByEntityId($entityId)
    {
        return \App\Client::where('entity_id', $entityId)->firstOrFail();
    }
}
