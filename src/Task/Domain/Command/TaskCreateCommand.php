<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Domain\Command;

use MicroModule\Base\Domain\Command\AbstractCommand;
use MicroModule\Base\Domain\ValueObject\Payload;
use MicroModule\Base\Domain\ValueObject\ProcessUuid;
use Micro\Tracker\Task\Domain\ValueObject\Task;

/**
 * @class TaskCreateCommand
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

class TaskCreateCommand extends AbstractCommand
{
    /**
     * Constructor
     */
    public function __construct(
		ProcessUuid $processUuid, 
		protected Task $task
    ){
		parent::__construct($processUuid, null);
    }

    /**
     * Return Task value object.
     */
    public function getTask(): Task
    {
        return $this->task;
    }
}
