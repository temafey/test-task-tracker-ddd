<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Application\Projector;

use Broadway\ReadModel\Projector;
use Micro\Tracker\Task\Domain\Event\TaskAssignedEvent;
use Micro\Tracker\Task\Domain\Event\TaskCreatedEvent;
use Micro\Tracker\Task\Domain\Event\TaskStatusUpdatedEvent;
use Micro\Tracker\Task\Domain\Factory\ReadModelFactoryInterface as ReadModelFactoryInterface;
use Micro\Tracker\Task\Domain\Repository\ReadModel\TaskRepositoryInterface as ReadModelInterface;
use Micro\Tracker\Task\Infrastructure\Repository\EntityStore\TaskRepository;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * @class TaskProjector
 *
 * @package Micro\Tracker\Task\Application\Projector
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */
#[AutoconfigureTag(name: 'broadway.domain.event_listener')]
class TaskProjector extends Projector
{
    /**
     * Constructor
     */
    public function __construct(
		#[Autowire(service: 'Micro\Tracker\Task\Infrastructure\Repository\EntityStore\TaskRepository')]
		protected TaskRepository $entityStore,
		#[Autowire(service: 'Micro\Tracker\Task\Infrastructure\Repository\ReadModel\TaskRepository')]
		protected ReadModelInterface $readModelStore,
		#[Autowire(service: 'Micro\Tracker\Task\Domain\Factory\ReadModelFactory')]
		protected ReadModelFactoryInterface $readModelFactory
	)
    {
        
    }

    /**
     * Apply TaskCreatedEvent event.
     */
    public function applyTaskCreatedEvent(TaskCreatedEvent $event): void
    {
		$entity = $this->entityStore->get($event->getUuid());
		$readModel = $this->readModelFactory->makeTaskActualInstanceByEntity($entity);
		$this->readModelStore->add($readModel);
    }

    /**
     * Apply TaskStatusUpdatedEvent event.
     */
    public function applyTaskStatusUpdatedEvent(TaskStatusUpdatedEvent $event): void
    {
		$entity = $this->entityStore->get($event->getUuid());
		$readModel = $this->readModelFactory->makeTaskActualInstanceByEntity($entity);
		$this->readModelStore->update($readModel);
    }

    /**
     * Apply TaskAssignedEvent event.
     */
    public function applyTaskAssignedEvent(TaskAssignedEvent $event): void
    {
		$entity = $this->entityStore->get($event->getUuid());
		$readModel = $this->readModelFactory->makeTaskActualInstanceByEntity($entity);
		$this->readModelStore->update($readModel);
    }
}
