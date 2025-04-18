<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Application\CommandHandler\Task;

use MicroModule\Base\Application\CommandHandler\CommandHandlerInterface;
use MicroModule\Base\Domain\Command\CommandInterface;
use Micro\Tracker\Task\Domain\Command\Task\UserUpdateTaskCommand;
use Micro\Tracker\Task\Domain\Repository\TaskRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * @class UserUpdateTaskCommandHandler
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
	'command' => 'Micro\Tracker\Task\Domain\Command\Task\UserUpdateTaskCommand',
	'bus' => 'command.task'
])]
class UserUpdateTaskCommandHandler implements CommandHandlerInterface
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
     * Handle UserUpdateTaskCommand command.
	 *
	 * @param CommandInterface|UserUpdateTaskCommand $userUpdateTaskCommand
     */
    public function handle(CommandInterface $userUpdateTaskCommand): bool
    {
		$this->taskRepository->addUserUpdateTask($userUpdateTaskCommand->getProcessUuid(), $userUpdateTaskCommand->getUuid(), $userUpdateTaskCommand->getUser());

        return true;
    }
}
