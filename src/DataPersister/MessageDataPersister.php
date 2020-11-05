<?php

namespace App\DataPersister;

use App\Entity\Chat\Message;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;

class MessageDataPersister extends BaseDataPersister
{
    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        parent::__construct($entityManager);
        $this->security = $security;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Message;
    }

    protected function afterPersist($data, array $context = [])
    {
        if ($this->isCreate($context)) {
            $data->setOwner($this->security->getUser());
        }
    }
}
