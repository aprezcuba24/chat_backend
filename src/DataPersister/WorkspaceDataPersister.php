<?php

namespace App\DataPersister;

use App\Entity\Chat\Workspace;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;

class WorkspaceDataPersister extends BaseDataPersister
{
    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        parent::__construct($entityManager);
        $this->security = $security;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Workspace;
    }

    protected function afterPersist($data, array $context = [])
    {
        if ($this->isCreate($context)) {
            $data->setOwner($this->security->getUser());
            $data->addMember($this->security->getUser());
        }
    }
}
