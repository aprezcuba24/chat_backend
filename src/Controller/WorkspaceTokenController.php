<?php

namespace App\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Mercure\ConfigGenerator;
use App\Entity\Chat\Channel;

class WorkspaceTokenController extends AbstractController
{
    protected $security;
    protected $entityManager;
    protected $workspaceId;
    protected $configGenerator;
    protected $jwtManager;

    public function __construct(
        Security $security,
        EntityManagerInterface $entityManager,
        ConfigGenerator $configGenerator,
        JWTTokenManagerInterface $jwtManager
    )
    {
        $this->security = $security;
        $this->configGenerator = $configGenerator;
        $this->entityManager = $entityManager;
        $this->jwtManager = $jwtManager;
        $token = $this->security->getToken();
        $this->workspaceId = $token->getAttribute('workspace_id');
    }

    /**
     * @Route(
     *     name="workspace_token",
     *     path="/api/authentication_token/{workspace_id}",
     * )
     */
    public function __invoke($workspace_id, Request $request)
    {
        $mercureConfig = $this->getSubscribeConfig($request, $workspace_id);
        return $this->json([
            'mercure' => $mercureConfig['mercure'],
            'token'   => $this->jwtManager->create($this->security->getUser()),
        ],);
    }

    protected function getSubscribeConfig(Request $request, $workspaceId)
    {
        $hubUrl = $this->getParameter('mercure_hub');
        $channels = $this->getTopicMessages($workspaceId);

        $token = (new Builder())
            ->withClaim(
                'mercure', ['subscribe' => $channels],
            )
            ->getToken(
                new Sha256(),
                new Key($this->getParameter('mercure_secret_key'))
            );

        return [
            'mercure' => [
                'token'  => $token->__toString(),
                'topics' => $channels,
                'hubUrl' => $hubUrl,
            ],
        ];
    }

    protected function getTopicMessages($workspaceId)
    {
        $channels = $this->entityManager
            ->getRepository(Channel::class)
            ->findByWorkspace($workspaceId)
            ->getQuery()
            ->getResult()
        ;
        return array_map(function ($item) {
            return $this->configGenerator->getTopicMessages($item);
        }, $channels);
    }
}