<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Domain\Factory;

use MicroModule\Base\Domain\ValueObject\Payload;
use MicroModule\Base\Domain\ValueObject\ProcessUuid;
use MicroModule\Base\Domain\ValueObject\Uuid;
use Micro\Tracker\Task\Domain\Event\TaskAssignedEvent;
use Micro\Tracker\Task\Domain\Event\TaskCreatedEvent;
use Micro\Tracker\Task\Domain\Event\TaskStatusUpdatedEvent;
use Micro\Tracker\Task\Domain\Event\UserCreatedEvent;
use Micro\Tracker\Task\Domain\Event\UserUpdatedEvent;
use Micro\Tracker\Task\Domain\ValueObject\Task;
use Micro\Tracker\Task\Domain\ValueObject\User;

/**
 * @class EventFactory
 *
 * @package Micro\Tracker\Task\Domain\Factory
 *
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */

class EventFactory implements EventFactoryInterface
{
    /**
     * Create TaskCreatedEvent Event.
     */
    public function makeTaskCreatedEvent(ProcessUuid $processUuid, Uuid $uuid, Task $task, ?Payload $payload = null): TaskCreatedEvent
    {
        return new TaskCreatedEvent(
			$processUuid, $uuid, $task, $payload
		);
    }

    /**
     * Create TaskStatusUpdatedEvent Event.
     */
    public function makeTaskStatusUpdatedEvent(ProcessUuid $processUuid, Uuid $uuid, Task $task, ?Payload $payload = null): TaskStatusUpdatedEvent
    {
        return new TaskStatusUpdatedEvent(
			$processUuid, $uuid, $task, $payload
		);
    }

    /**
     * Create TaskAssignedEvent Event.
     */
    public function makeTaskAssignedEvent(ProcessUuid $processUuid, Uuid $uuid, Task $task, ?Payload $payload = null): TaskAssignedEvent
    {
        return new TaskAssignedEvent(
			$processUuid, $uuid, $task, $payload
		);
    }

    /**
     * Create UserCreatedEvent Event.
     */
    public function makeUserCreatedEvent(ProcessUuid $processUuid, Uuid $uuid, User $user, ?Payload $payload = null): UserCreatedEvent
    {
        return new UserCreatedEvent(
			$processUuid, $uuid, $user, $payload
		);
    }

    /**
     * Create UserUpdatedEvent Event.
     */
    public function makeUserUpdatedEvent(ProcessUuid $processUuid, Uuid $uuid, User $user, ?Payload $payload = null): UserUpdatedEvent
    {
        return new UserUpdatedEvent(
			$processUuid, $uuid, $user, $payload
		);
    }
}
