<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Domain\Factory;

use MicroModule\Base\Domain\ValueObject\Id;
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
 * @interface EventFactoryInterface
 *
 * @package Micro\Tracker\Task\Domain\Factory
 */
interface EventFactoryInterface
{
    /**
     * Create TaskCreatedEvent Event.
     */
    public function makeTaskCreatedEvent(ProcessUuid $processUuid, Uuid $uuid, Task $task, ?Payload $payload = null): TaskCreatedEvent;

    /**
     * Create TaskStatusUpdatedEvent Event.
     */
    public function makeTaskStatusUpdatedEvent(ProcessUuid $processUuid, Uuid $uuid, Task $task, ?Payload $payload = null): TaskStatusUpdatedEvent;

    /**
     * Create TaskAssignedEvent Event.
     */
    public function makeTaskAssignedEvent(ProcessUuid $processUuid, Uuid $uuid, Task $task, ?Payload $payload = null): TaskAssignedEvent;

    /**
     * Create UserCreatedEvent Event.
     */
    public function makeUserCreatedEvent(ProcessUuid $processUuid, Uuid $uuid, User $user, ?Payload $payload = null): UserCreatedEvent;

    /**
     * Create UserUpdatedEvent Event.
     */
    public function makeUserUpdatedEvent(ProcessUuid $processUuid, Uuid $uuid, User $user, ?Payload $payload = null): UserUpdatedEvent;
}
