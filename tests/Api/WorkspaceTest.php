<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\Traits\JWTTrait;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use App\Entity\User;
use App\Entity\Chat\Workspace;

class WorkspaceTest extends ApiTestCase
{
    use ReloadDatabaseTrait;
    use JWTTrait;

    public function testList()
    {
        // One Workspace
        $client = $this->getCreateClientJWT('user1@admin.com');
        $response = $client->request('GET', '/api/workspaces');
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@context' => '/api/contexts/Workspace',
            '@id' => '/api/workspaces',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 3,
        ]);

        // One Workspace
        $client = $this->getCreateClientJWT('user3@admin.com');
        $response = $client->request('GET', '/api/workspaces');
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@context' => '/api/contexts/Workspace',
            '@id' => '/api/workspaces',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 1,
        ]);
    }

    public function testCreate()
    {
        $client = $this->getCreateClientJWT('user1@admin.com');
        $response = $client->request('POST', '/api/workspaces', ['json' => [
            'name' => 'Workspace test',
        ]]);
        $this->assertResponseIsSuccessful();
        $iri = $this->findIriBy(User::class, ['email' => 'user1@admin.com']);
        $this->assertJsonContains([
            '@context' => '/api/contexts/Workspace',
            '@type' => 'Workspace',
            'name' => 'Workspace test',
            'owner' => $iri,
        ]);
        // $client = $this->getCreateClientJWT('user1@admin.com', 'Workspace test');
        $this->addTokenByWorkspace($client, 'Workspace test');
        $response = $client->request('GET', '/api/users');
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@context' => '/api/contexts/User',
            '@id' => '/api/users',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 1,
        ]);
    }

    public function testEdit()
    {
        $client = $this->getCreateClientJWT('user1@admin.com');
        $iri = $this->findIriBy(Workspace::class, ['name' => 'Workspace 1']);
        $response = $client->request('PUT', $iri, ['json' => [
            'name' => 'Workspace test',
        ]]);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@context' => '/api/contexts/Workspace',
            '@type' => 'Workspace',
            'name' => 'Workspace test',
        ]);
    }

    public function testCantEdit()
    {
        $client = $this->getCreateClientJWT('user2@admin.com');
        $iri = $this->findIriBy(Workspace::class, ['name' => 'Workspace 1']);
        $response = $client->request('PUT', $iri, ['json' => [
            'name' => 'Workspace test',
        ]]);
        $this->assertSame(403, $client->getResponse()->getStatusCode());
    }

    public function testRemove()
    {
        $client = $this->getCreateClientJWT('user1@admin.com');
        $iri = $this->findIriBy(Workspace::class, ['name' => 'Workspace 3']);
        $response = $client->request('DELETE', $iri);
        $this->assertResponseIsSuccessful();
    }

    public function testCantRemove()
    {
        $client = $this->getCreateClientJWT('user2@admin.com');
        $iri = $this->findIriBy(Workspace::class, ['name' => 'Workspace 1']);
        $response = $client->request('DELETE', $iri);
        $this->assertSame(403, $client->getResponse()->getStatusCode());
    }
}