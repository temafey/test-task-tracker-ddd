<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Domain\Command;

use MicroModule\Base\Domain\Command\AbstractCommand;
use MicroModule\Base\Domain\ValueObject\Payload;
use MicroModule\Base\Domain\ValueObject\ProcessUuid;
use Micro\Tracker\Task\Domain\ValueObject\User;

/**
 * @class UserCreateCommand
 *
 * @package Micro\Tracker\Task\Domain\Command
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */

class UserCreateCommand extends AbstractCommand
{
    /**
     * Constructor
     */
    public function __construct(
		ProcessUuid $processUuid, 
		protected User $user
    ){
		parent::__construct($processUuid, null);
        
    }

    /**
     * Return User value object.
     */
    public function getUser(): User
    {
        return $this->user;
    }
}
