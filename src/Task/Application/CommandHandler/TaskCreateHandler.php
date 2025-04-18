<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Application\CommandHandler;

use MicroModule\Base\Application\CommandHandler\CommandHandlerInterface;
use MicroModule\Base\Domain\Command\CommandInterface;
use Micro\Tracker\Task\Domain\Command\TaskCreateCommand;
use Micro\Tracker\Task\Domain\Factory\EntityFactoryInterface;
use Micro\Tracker\Task\Domain\Repository\EntityStore\TaskRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * @class TaskCreateHandler
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
	'command' => 'Micro\Tracker\Task\Domain\Command\TaskCreateCommand',
	'bus' => 'command.task'
])]
class TaskCreateHandler implements CommandHandlerInterface
{
    /**
     * Constructor
     */
    public function __construct(
		protected TaskRepositoryInterface $taskRepository,
		protected EntityFactoryInterface $entityFactory
	)
    {
        
    }

    /**
     * Handle TaskCreateCommand command.
	 *
	 * @param CommandInterface|TaskCreateCommand $taskCreateCommand
     */
    public function handle(CommandInterface $taskCreateCommand): string
    {
		$taskEntity = $this->entityFactory->createTaskInstance($taskCreateCommand->getProcessUuid(), $taskCreateCommand->getTask());
		$this->taskRepository->store($taskEntity);

        return $taskEntity->getUuid()->toString();
    }
}
