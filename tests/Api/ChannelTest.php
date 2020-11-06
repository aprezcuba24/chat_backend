<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\Traits\JWTTrait;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use App\Entity\Chat\Workspace;

class ChannelTest extends ApiTestCase
{
    use ReloadDatabaseTrait;
    use JWTTrait;

    public function testList()
    {
        $client = $this->getCreateClientJWT('user1@admin.com', 'Workspace 1');
        $response = $client->request('GET', '/api/channels');
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@context' => '/api/contexts/Channel',
            '@id' => '/api/channels',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 2,
        ]);

        $client = $this->getCreateClientJWT('user1@admin.com', 'Workspace 3');
        $response = $client->request('GET', '/api/channels');
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@context' => '/api/contexts/Channel',
            '@id' => '/api/channels',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 0,
        ]);
    }

    public function testCreate()
    {
        $client = $this->getCreateClientJWT('user1@admin.com', 'Workspace 3');
        $response = $client->request('POST', '/api/channels', ['json' => [
            'name' => 'Channel test',
        ]]);
        $this->assertResponseIsSuccessful();
        $iri = $this->findIriBy(Workspace::class, ['name' => 'Workspace 3']);
        $this->assertJsonContains([
            '@context' => '/api/contexts/Channel',
            '@type' => 'Channel',
            'name' => 'Channel test',
            'workspace' => $iri,
        ]);

        $channelIri = \json_decode($response->getContent(), true)['@id'];
        $response = $client->request('GET', \sprintf('%s/members', $channelIri));
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@context' => '/api/contexts/User',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 1,
        ]);
    }
}
