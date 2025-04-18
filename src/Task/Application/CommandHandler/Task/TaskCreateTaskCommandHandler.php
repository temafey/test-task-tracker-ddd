<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Application\CommandHandler\Task;

use MicroModule\Base\Application\CommandHandler\CommandHandlerInterface;
use MicroModule\Base\Domain\Command\CommandInterface;
use Micro\Tracker\Task\Domain\Command\Task\TaskCreateTaskCommand;
use Micro\Tracker\Task\Domain\Repository\TaskRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * @class TaskCreateTaskCommandHandler
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
	'command' => 'Micro\Tracker\Task\Domain\Command\Task\TaskCreateTaskCommand',
	'bus' => 'command.task'
])]
class TaskCreateTaskCommandHandler implements CommandHandlerInterface
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
     * Handle TaskCreateTaskCommand command.
	 *
	 * @param CommandInterface|TaskCreateTaskCommand $taskCreateTaskCommand
     */
    public function handle(CommandInterface $taskCreateTaskCommand): bool
    {
		$this->taskRepository->addTaskCreateTask($taskCreateTaskCommand->getProcessUuid(), $taskCreateTaskCommand->getTask());

        return true;
    }
}
