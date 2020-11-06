<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\Traits\JWTTrait;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class UserTest extends ApiTestCase
{
    use ReloadDatabaseTrait;
    use JWTTrait;

    public function testList()
    {
        $client = $this->getCreateClientJWT('user1@admin.com', 'Workspace 1');
        $response = $client->request('GET', '/api/users');
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@context' => '/api/contexts/User',
            '@id' => '/api/users',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 3,
        ]);

        $client = $this->getCreateClientJWT('user1@admin.com', 'Workspace 3');
        $response = $client->request('GET', '/api/users');
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@context' => '/api/contexts/User',
            '@id' => '/api/users',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 1,
        ]);
    }
}
