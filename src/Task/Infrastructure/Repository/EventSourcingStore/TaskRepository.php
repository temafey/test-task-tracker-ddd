<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Infrastructure\Repository\EventSourcingStore;

use Broadway\EventHandling\EventBus as EventBusInterface;
use Broadway\EventSourcing\AggregateFactory\PublicConstructorAggregateFactory;
use Broadway\EventSourcing\EventSourcingRepository;
use Broadway\EventSourcing\EventStreamDecorator as EventStreamDecoratorInterface;
use Broadway\EventStore\EventStore as EventStoreInterface;
use Micro\Tracker\Task\Domain\Entity\TaskEntity;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * @class TaskRepository
 *
 * @package Micro\Tracker\Task\Infrastructure\Repository\EventSourcingStore
 */
class TaskRepository extends EventSourcingRepository
{
    /**
     * @param EventStreamDecoratorInterface[] $eventStreamDecorators
     */
    public function __construct(
        #[Autowire(service: 'Broadway\EventStore\Dbal\DBALEventStore')]
        EventStoreInterface $eventStore,
        #[Autowire(service: 'queue_event_bus.global')]
        EventBusInterface $eventBus,
        array $eventStreamDecorators = []
    ) {
        parent::__construct(
            $eventStore,
            $eventBus,
            TaskEntity::class,
            new PublicConstructorAggregateFactory(),
            $eventStreamDecorators
        );
    }
}
