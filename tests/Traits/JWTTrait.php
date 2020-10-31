<?php

declare(strict_types=1);

namespace App\Tests\Traits;

use App\Entity\User;
use App\Entity\Site;
use Doctrine\ORM\EntityManagerInterface;

trait JWTTrait
{
    function getCreateClientJWT($email = 'user1@admin.com', $siteUrl = null)
    {
        $client = static::createClient();
        $container = static::$container ?? static::$kernel->getContainer();
        $entityManager = $container->get(EntityManagerInterface::class);
        $user = $entityManager->getRepository(User::class)->findOneBy([
            'email' => $email,
        ]);
        $jwtManager = $container->get('lexik_jwt_authentication.jwt_manager');
        $token = $jwtManager->create($user);
        $siteId = null;
        if ($siteUrl) {
            $site = $entityManager->getRepository(Site::class)->findOneBy([
                'url' => $siteUrl,
            ]);
            $siteId = $site->getId();
        }
        $headers = [
            'Authorization'=> 'Bearer '.$token,
            'site_id' => $siteId,
        ];
        $client->setDefaultOptions([
            'headers' => $headers,
        ]);

        return $client;
    }
}