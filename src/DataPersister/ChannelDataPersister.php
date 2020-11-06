<?php

namespace App\DataPersister;

use App\Entity\Chat\Channel;
use App\Entity\Chat\Workspace;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;

class ChannelDataPersister extends BaseDataPersister
{
    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        parent::__construct($entityManager);
        $this->security = $security;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Channel;
    }

    protected function afterPersist($data, array $context = [])
    {
        if ($this->isCreate($context)) {
            $token = $this->security->getToken();
            $workspaceId = $token->getAttribute('workspace_id');
            $workspace = $this->entityManager->getRepository(Workspace::class)->find($workspaceId);
            $data->addMember($this->security->getUser());
            $data->setWorkspace($workspace);
        }
    }
}
