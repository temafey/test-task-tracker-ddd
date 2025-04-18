<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Application\CommandHandler\Task;

use MicroModule\Base\Application\CommandHandler\CommandHandlerInterface;
use MicroModule\Base\Domain\Command\CommandInterface;
use Micro\Tracker\Task\Domain\Command\Task\TaskUpdateStatusTaskCommand;
use Micro\Tracker\Task\Domain\Repository\TaskRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * @class TaskUpdateStatusTaskCommandHandler
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
	'command' => 'Micro\Tracker\Task\Domain\Command\Task\TaskUpdateStatusTaskCommand',
	'bus' => 'command.task'
])]
class TaskUpdateStatusTaskCommandHandler implements CommandHandlerInterface
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
     * Handle TaskUpdateStatusTaskCommand command.
	 *
	 * @param CommandInterface|TaskUpdateStatusTaskCommand $taskUpdateStatusTaskCommand
     */
    public function handle(CommandInterface $taskUpdateStatusTaskCommand): bool
    {
		$this->taskRepository->addTaskUpdateStatusTask($taskUpdateStatusTaskCommand->getProcessUuid(), $taskUpdateStatusTaskCommand->getUuid(), $taskUpdateStatusTaskCommand->getTask());

        return true;
    }
}
