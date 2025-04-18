<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Application\Processor;

use MicroModule\EventQueue\Application\EventHandling\QueueEventProcessor as BaseQueueEventProcessor;

class QueueEventProcessor extends BaseQueueEventProcessor
{
    public const TOPIC = 'micro-platform.task.queueevent';

    public static function getTopic(): string
    {
        return self::TOPIC;
    }
}
