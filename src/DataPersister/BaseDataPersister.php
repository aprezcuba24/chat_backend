<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Doctrine\ORM\EntityManagerInterface;

abstract class BaseDataPersister implements DataPersisterInterface
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function isCreate($context) {
        return ($context['collection_operation_name'] ?? null) === 'post' ||
        ($context['graphql_operation_name'] ?? null) === 'create';
    }

    protected function afterPersist($data, array $context = [])
    {
    }

    protected function beforePersist($data, array $context = [])
    {
    }

    public function persist($data, array $context = [])
    {
        $this->afterPersist($data, $context);
        $result = $this->entityManager->persist($data);
        $this->entityManager->flush();
        $this->beforePersist($data, $context);

        return $result;
    }

    protected function afterRemove($data, array $context = [])
    {
    }

    protected function beforeRemove($data, array $context = [])
    {
    }

    public function remove($data, array $context = [])
    {
        $this->afterRemove($data, $context);
        $result = $this->entityManager->remove($data);
        $this->entityManager->flush();
        $this->beforeRemove($data, $context);
    }
}