<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Application\CommandHandler\Task;

use MicroModule\Base\Application\CommandHandler\CommandHandlerInterface;
use MicroModule\Base\Domain\Command\CommandInterface;
use Micro\Tracker\Task\Domain\Command\Task\UserCreateTaskCommand;
use Micro\Tracker\Task\Domain\Repository\TaskRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * @class UserCreateTaskCommandHandler
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
	'command' => 'Micro\Tracker\Task\Domain\Command\Task\UserCreateTaskCommand',
	'bus' => 'command.task'
])]
class UserCreateTaskCommandHandler implements CommandHandlerInterface
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
     * Handle UserCreateTaskCommand command.
	 *
	 * @param CommandInterface|UserCreateTaskCommand $userCreateTaskCommand
     */
    public function handle(CommandInterface $userCreateTaskCommand): bool
    {
		$this->taskRepository->addUserCreateTask($userCreateTaskCommand->getProcessUuid(), $userCreateTaskCommand->getUser());

        return true;
    }
}
