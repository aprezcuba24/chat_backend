<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class LoginTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    public function testBadPassword()
    {
        $client = static::createClient();
        $response = $client->request('POST', '/api/authentication_token', ['json' => [
            'email' => 'user1@admin.com',
            'password' => 'badpassword',
        ]]);
        $this->assertSame(401, $client->getResponse()->getStatusCode());
    }

    public function testGoodPassword()
    {
        $client = static::createClient();
        $response = $client->request('POST', '/api/authentication_token', ['json' => [
            'email' => 'user1@admin.com',
            'password' => 'admin',
        ]]);
        $this->assertResponseIsSuccessful();
    }
}