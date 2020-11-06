<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTAuthenticatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\HttpFoundation\RequestStack;

class JWTSubscriber implements EventSubscriberInterface
{
    protected $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function onJWTAuthenticated(JWTAuthenticatedEvent $event)
    {
        $token = $event->getToken();
        $payload = $event->getPayload();
        $token->setAttribute('workspace_id', $payload['workspace_id']);
    }

    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $request = $this->requestStack->getMasterRequest();
        $workspaceId = null;
        if ($request) {
            $workspaceId = $request->attributes->get('workspace_id');
        }
        $payload = $event->getData();
        $payload['workspace_id'] = $workspaceId;
        $event->setData($payload);
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::JWT_AUTHENTICATED => 'onJWTAuthenticated',
            Events::JWT_CREATED => 'onJWTCreated',
        ];
    }
}
