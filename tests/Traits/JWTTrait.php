<?php

declare(strict_types=1);

namespace App\Tests\Traits;

use App\Entity\User;
use App\Entity\Chat\Workspace;
use Doctrine\ORM\EntityManagerInterface;

trait JWTTrait
{
    function getCreateClientJWT($email = 'user1@admin.com', $workspaceName = null)
    {
        $client = static::createClient();
        $container = static::$container ?? static::$kernel->getContainer();
        $entityManager = $container->get(EntityManagerInterface::class);
        $user = $entityManager->getRepository(User::class)->findOneBy([
            'email' => $email,
        ]);
        $jwtManager = $container->get('lexik_jwt_authentication.jwt_manager');
        $token = $jwtManager->create($user);
        $this->addToken($client, $token);
        if ($workspaceName) {
            $this->addTokenByWorkspace($client, $workspaceName);
        }

        return $client;
    }

    function addTokenByWorkspace($client, $workspaceName)
    {
        $container = static::$container ?? static::$kernel->getContainer();
        $entityManager = $container->get(EntityManagerInterface::class);
        $workspace = $entityManager->getRepository(Workspace::class)->findOneBy([
            'name' => $workspaceName,
        ]);
        $response = $client->request('GET', sprintf('/api/authentication_token/%s', $workspace->getId()));
        $token = \json_decode($response->getContent(), true)['token'];
        $this->addToken($client, $token);
    }

    function addToken($client, $token)
    {
        $headers = [
            'Authorization'=> 'Bearer '.$token,
        ];
        $client->setDefaultOptions([
            'headers' => $headers,
        ]);
    }
}