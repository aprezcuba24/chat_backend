<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use Symfony\Component\Security\Core\Security;
use App\Entity\Chat\Workspace;

class WorkspaceDataTransformer implements DataTransformerInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($entity, string $to, array $context = [])
    {
        if ($context['operation_type'] === 'item') {
            $entity = $context['object_to_populate'];
        }
        if ($context['operation_type'] === 'collection' && $context['collection_operation_name'] === 'post') {
            $entity->setOwner($this->security->getUser());
            $entity->addMember($this->security->getUser());
        }

        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof Workspace) {
          return false;
        }

        return Workspace::class === $to && null !== ($context['input']['class'] ?? null);
    }
}
