<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Application\CommandHandler;

use MicroModule\Base\Application\CommandHandler\CommandHandlerInterface;
use MicroModule\Base\Domain\Command\CommandInterface;
use Micro\Tracker\Task\Domain\Command\UserUpdateCommand;
use Micro\Tracker\Task\Domain\Repository\EntityStore\UserRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * @class UserUpdateHandler
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
	'command' => 'Micro\Tracker\Task\Domain\Command\UserUpdateCommand',
	'bus' => 'command.task'
])]
class UserUpdateHandler implements CommandHandlerInterface
{
    /**
     * Constructor
     */
    public function __construct(
		protected UserRepositoryInterface $userRepository
	)
    {
        
    }

    /**
     * Handle UserUpdateCommand command.
	 *
	 * @param CommandInterface|UserUpdateCommand $userUpdateCommand
     */
    public function handle(CommandInterface $userUpdateCommand): string
    {
		$userEntity = $this->userRepository->get($userUpdateCommand->getUuid());
		$userEntity->userUpdate($userUpdateCommand->getProcessUuid(), $userUpdateCommand->getUser());
		$this->userRepository->store($userEntity);

        return $userEntity->getUuid()->toString();
    }
}
