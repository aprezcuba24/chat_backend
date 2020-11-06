<?php

namespace App\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WorkspaceTokenController extends AbstractController
{
    /**
     * @Route(
     *     name="workspace_token",
     *     path="/api/authentication_token/{workspace_id}",
     * )
     */
    public function __invoke(JWTTokenManagerInterface $jwtManager, Security $security)
    {
        return $this->json([
            'token' => $jwtManager->create($security->getUser()),
        ]);
    }
}