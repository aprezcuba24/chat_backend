<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\Traits\JWTTrait;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use App\Entity\Chat\Channel;
use App\Entity\User;

class MessageTest extends ApiTestCase
{
    use JWTTrait;
    use ReloadDatabaseTrait;

    public function testCreate()
    {
        $client = $this->getCreateClientJWT('user1@admin.com', 'Workspace 1');
        $channelIri = $this->findIriBy(Channel::class, ['name' => 'General 1']);

        $response = $client->request('GET', \sprintf('%s/messages', $channelIri));
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@context' => '/api/contexts/Message',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 1,
        ]);

        $response = $client->request('POST', '/api/messages', ['json' => [
            'body' => 'Message test',
            'channel' => $channelIri,
        ]]);
        $this->assertResponseIsSuccessful();
        $userIri = $this->findIriBy(User::class, ['email' => 'user1@admin.com']);
        $this->assertJsonContains([
            '@context' => '/api/contexts/Message',
            '@type' => 'Message',
            'body' => 'Message test',
            'channel' => $channelIri,
            'owner' => $userIri,
        ]);

        $response = $client->request('GET', \sprintf('%s/messages', $channelIri));
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@context' => '/api/contexts/Message',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 2,
        ]);
    }
}
