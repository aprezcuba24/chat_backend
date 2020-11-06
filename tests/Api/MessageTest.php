<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\Traits\JWTTrait;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use App\Entity\Chat\Channel;

// class MessageTest extends ApiTestCase
// {
//     use JWTTrait;
//     use ReloadDatabaseTrait;

//     public function testCreate()
//     {
//         $client = $this->getCreateClientJWT('user1@admin.com');
//         $channelIri = $this->findIriBy(Channel::class, ['name' => 'General 1']);
//         $response = $client->request('POST', '/api/messages', ['json' => [
//             'body' => 'Message test',
//             'channel' => $channelIri,
//         ]]);
//         $this->assertResponseIsSuccessful();
//     }
// }
