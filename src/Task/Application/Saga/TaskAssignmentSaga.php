<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Application\Saga;

use Broadway\Saga\Metadata\StaticallyConfiguredSagaInterface;
use Broadway\Saga\State;
use League\Tactician\CommandBus;
use MicroModule\Saga\AbstractSaga;
use Micro\Tracker\Task\Domain\Event\TaskAssignedEvent;
use Micro\Tracker\Task\Domain\Factory\CommandFactoryInterface as CommandFactoryInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * @class TaskAssignmentSaga
 *
 * @package Micro\Tracker\Task\Application\Saga
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */
#[AutoconfigureTag(name: 'broadway.saga', attributes: ['type' => 'api.task.task-assignment'])]
class TaskAssignmentSaga extends AbstractSaga implements StaticallyConfiguredSagaInterface
{
	protected const STATE_CRITERIA_KEY = 'processId';
	protected const STATE_ID_KEY = 'id';
    /**
     * Constructor
     */
    public function __construct(
		#[Autowire(service: 'tactician.commandbus.command.task')]
		protected CommandBus $commandBus,
		#[Autowire(service: 'Micro\Tracker\Task\Domain\Factory\CommandFactory')]
		protected CommandFactoryInterface $commandFactory
	)
    {
        
    }

    /**
     * Saga configuration method, return map of events and state search criteria.
     */
    public static function configuration()
    {
        return [
		];
    }
}
