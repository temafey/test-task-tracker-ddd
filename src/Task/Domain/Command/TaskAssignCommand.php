<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Domain\Command;

use MicroModule\Base\Domain\Command\AbstractCommand;
use MicroModule\Base\Domain\ValueObject\Payload;
use MicroModule\Base\Domain\ValueObject\ProcessUuid;
use MicroModule\Base\Domain\ValueObject\Uuid;
use Micro\Tracker\Task\Domain\ValueObject\Task;

/**
 * @class TaskAssignCommand
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

class TaskAssignCommand extends AbstractCommand
{
    /**
     * Constructor
     */
    public function __construct(
		ProcessUuid $processUuid, 
		Uuid $uuid, 
		protected Task $task, 
		?Payload $payload = null)
    {
		parent::__construct($processUuid, $uuid, $payload);
        
    }

    /**
     * Return Task value object.
     */
    public function getTask(): Task
    {
        return $this->task;
    }
}
