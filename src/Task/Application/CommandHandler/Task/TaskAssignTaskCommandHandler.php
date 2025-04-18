<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Application\CommandHandler\Task;

use MicroModule\Base\Application\CommandHandler\CommandHandlerInterface;
use MicroModule\Base\Domain\Command\CommandInterface;
use Micro\Tracker\Task\Domain\Command\Task\TaskAssignTaskCommand;
use Micro\Tracker\Task\Domain\Repository\TaskRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * @class TaskAssignTaskCommandHandler
 *
 * @package Micro\Tracker\Task\Application\CommandHandler\Task
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
	'command' => 'Micro\Tracker\Task\Domain\Command\Task\TaskAssignTaskCommand',
	'bus' => 'command.task'
])]
class TaskAssignTaskCommandHandler implements CommandHandlerInterface
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
     * Handle TaskAssignTaskCommand command.
	 *
	 * @param CommandInterface|TaskAssignTaskCommand $taskAssignTaskCommand
     */
    public function handle(CommandInterface $taskAssignTaskCommand): bool
    {
		$this->taskRepository->addTaskAssignTask($taskAssignTaskCommand->getProcessUuid(), $taskAssignTaskCommand->getUuid(), $taskAssignTaskCommand->getTask());

        return true;
    }
}
