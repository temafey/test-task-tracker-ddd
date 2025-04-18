<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Application\CommandHandler;

use MicroModule\Base\Application\CommandHandler\CommandHandlerInterface;
use MicroModule\Base\Domain\Command\CommandInterface;
use Micro\Tracker\Task\Domain\Command\TaskUpdateStatusCommand;
use Micro\Tracker\Task\Domain\Repository\EntityStore\TaskRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * @class TaskUpdateStatusHandler
 *
 * @package Micro\Tracker\Task\Application\CommandHandler
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */
#[AutoconfigureTag(name: 'tactician.handler', attributes: [
	'command' => 'Micro\Tracker\Task\Domain\Command\TaskUpdateStatusCommand',
	'bus' => 'command.task'
])]
class TaskUpdateStatusHandler implements CommandHandlerInterface
{
    /**
     * Constructor
     */
    public function __construct(
		protected TaskRepositoryInterface $taskRepository
	)
    {
        
    }

    /**
     * Handle TaskUpdateStatusCommand command.
	 *
	 * @param CommandInterface|TaskUpdateStatusCommand $taskUpdateStatusCommand
     */
    public function handle(CommandInterface $taskUpdateStatusCommand): string
    {
		$taskEntity = $this->taskRepository->get($taskUpdateStatusCommand->getUuid());
		$taskEntity->taskUpdateStatus($taskUpdateStatusCommand->getProcessUuid(), $taskUpdateStatusCommand->getTask());
		$this->taskRepository->store($taskEntity);

        return $taskEntity->getUuid()->toString();
    }
}
