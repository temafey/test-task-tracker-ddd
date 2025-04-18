<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Application\Processor;

use MicroModule\Task\Application\Processor\JobCommandBusProcessor;

class JobProgramProcessor extends JobCommandBusProcessor
{
    public const TOPIC = 'task.add.command.run';

    public static function getTopic(): string
    {
        return self::TOPIC;
    }
}
