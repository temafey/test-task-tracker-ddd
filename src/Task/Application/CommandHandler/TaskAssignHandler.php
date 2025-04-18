<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Application\CommandHandler;

use MicroModule\Base\Application\CommandHandler\CommandHandlerInterface;
use MicroModule\Base\Domain\Command\CommandInterface;
use Micro\Tracker\Task\Domain\Command\TaskAssignCommand;
use Micro\Tracker\Task\Domain\Repository\EntityStore\TaskRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * @class TaskAssignHandler
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
	'command' => 'Micro\Tracker\Task\Domain\Command\TaskAssignCommand',
	'bus' => 'command.task'
])]
class TaskAssignHandler implements CommandHandlerInterface
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
     * Handle TaskAssignCommand command.
	 *
	 * @param CommandInterface|TaskAssignCommand $taskAssignCommand
     */
    public function handle(CommandInterface $taskAssignCommand): string
    {
		$taskEntity = $this->taskRepository->get($taskAssignCommand->getUuid());
		$taskEntity->taskAssign($taskAssignCommand->getProcessUuid(), $taskAssignCommand->getTask());
		$this->taskRepository->store($taskEntity);

        return $taskEntity->getUuid()->toString();
    }
}
