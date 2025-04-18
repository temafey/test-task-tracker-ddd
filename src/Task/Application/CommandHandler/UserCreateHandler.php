<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Application\CommandHandler;

use MicroModule\Base\Application\CommandHandler\CommandHandlerInterface;
use MicroModule\Base\Domain\Command\CommandInterface;
use Micro\Tracker\Task\Domain\Command\UserCreateCommand;
use Micro\Tracker\Task\Domain\Factory\EntityFactoryInterface;
use Micro\Tracker\Task\Domain\Repository\EntityStore\UserRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * @class UserCreateHandler
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
	'command' => 'Micro\Tracker\Task\Domain\Command\UserCreateCommand',
	'bus' => 'command.task'
])]
class UserCreateHandler implements CommandHandlerInterface
{
    /**
     * Constructor
     */
    public function __construct(
		protected UserRepositoryInterface $userRepository,
		protected EntityFactoryInterface $entityFactory
	)
    {
        
    }

    /**
     * Handle UserCreateCommand command.
	 *
	 * @param CommandInterface|UserCreateCommand $userCreateCommand
     */
    public function handle(CommandInterface $userCreateCommand): string
    {
		$userEntity = $this->entityFactory->createUserInstance($userCreateCommand->getProcessUuid(), $userCreateCommand->getUser());
		$this->userRepository->store($userEntity);

        return $userEntity->getUuid()->toString();
    }
}
