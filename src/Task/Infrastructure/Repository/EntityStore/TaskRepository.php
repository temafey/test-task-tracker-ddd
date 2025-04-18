<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Infrastructure\Repository\EntityStore;

use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventStore\EventStore;
use InvalidArgumentException;
use MicroModule\Snapshotting\EventSourcing\SnapshottingEventSourcingRepository;
use MicroModule\Snapshotting\EventSourcing\SnapshottingEventSourcingRepositoryException;
use MicroModule\Snapshotting\Snapshot\SnapshotRepositoryInterface;
use MicroModule\Snapshotting\Snapshot\TriggerInterface;
use Micro\Tracker\Task\Domain\Entity\TaskEntityInterface;
use Micro\Tracker\Task\Domain\Repository\EntityStore\TaskRepositoryInterface;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * @class TaskRepository
 *
 * @package Micro\Tracker\Task\Infrastructure\Repository\EntityStore
 */
class TaskRepository extends SnapshottingEventSourcingRepository implements TaskRepositoryInterface
{

    public function __construct(
        #[Autowire(service: 'Micro\Tracker\Task\Infrastructure\Repository\EventSourcingStore\TaskRepository')]
        EventSourcingRepository $eventSourcingRepository,
        #[Autowire(service: 'Broadway\EventStore\Dbal\DBALEventStore')]
        EventStore $eventStore,
        #[Autowire(service: 'micro_module.snapshotting.snapshot.task.repository')]
        SnapshotRepositoryInterface $snapshotRepository,
        #[Autowire(service: 'MicroModule\Snapshotting\Snapshot\Trigger\EventCountTrigger')]
        TriggerInterface $trigger
    ) {
        parent::__construct($eventSourcingRepository, $eventStore, $snapshotRepository, $trigger);
    }


   /**
     * Retrieve TaskEntity with applied events.
     */
    public function get(UuidInterface $uuid): TaskEntityInterface
    {
        $entity = $this->load($uuid->toString());
        
        if (!$entity instanceof TaskEntityInterface) {
            throw new InvalidArgumentException('Return object should implement TaskEntity.');
        }

        return $entity;
    }

    /**
     * Save TaskEntity last uncommitted events.
     *
     * @throws SnapshottingEventSourcingRepositoryException
     */
    public function store(TaskEntityInterface $entity): void
    {
        $this->save($entity);
    }
}
