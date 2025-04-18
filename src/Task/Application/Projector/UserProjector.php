<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Application\Projector;

use Broadway\ReadModel\Projector;
use Micro\Tracker\Task\Domain\Event\UserCreatedEvent;
use Micro\Tracker\Task\Domain\Event\UserUpdatedEvent;
use Micro\Tracker\Task\Domain\Factory\ReadModelFactoryInterface as ReadModelFactoryInterface;
use Micro\Tracker\Task\Domain\Repository\ReadModel\UserRepositoryInterface as ReadModelInterface;
use Micro\Tracker\Task\Infrastructure\Repository\EntityStore\UserRepository;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * @class UserProjector
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
class UserProjector extends Projector
{
    /**
     * Constructor
     */
    public function __construct(
		#[Autowire(service: 'Micro\Tracker\Task\Infrastructure\Repository\EntityStore\UserRepository')]
		protected UserRepository $entityStore,
		#[Autowire(service: 'Micro\Tracker\Task\Infrastructure\Repository\ReadModel\UserRepository')]
		protected ReadModelInterface $readModelStore,
		#[Autowire(service: 'Micro\Tracker\Task\Domain\Factory\ReadModelFactory')]
		protected ReadModelFactoryInterface $readModelFactory
	)
    {
        
    }

    /**
     * Apply UserCreatedEvent event.
     */
    public function applyUserCreatedEvent(UserCreatedEvent $event): void
    {
		$entity = $this->entityStore->get($event->getUuid());
		$readModel = $this->readModelFactory->makeUserActualInstanceByEntity($entity);
		$this->readModelStore->add($readModel);
    }

    /**
     * Apply UserUpdatedEvent event.
     */
    public function applyUserUpdatedEvent(UserUpdatedEvent $event): void
    {
		$entity = $this->entityStore->get($event->getUuid());
		$readModel = $this->readModelFactory->makeUserActualInstanceByEntity($entity);
		$this->readModelStore->update($readModel);
    }
}
