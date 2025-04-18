<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Domain\Repository;

use MicroModule\Base\Domain\ValueObject\ProcessUuid;
use MicroModule\Base\Domain\ValueObject\Uuid;
use Micro\Tracker\Task\Domain\ValueObject\Task;
use Micro\Tracker\Task\Domain\ValueObject\User;

/**
 * @interface TaskRepositoryInterface
 *
 * @package Micro\Tracker\Task\Domain\Repository
 */
interface TaskRepositoryInterface
{
    /**
     * Send `TaskCreate Command` into queue.
     */
    public function addTaskCreateTask(ProcessUuid $processUuid, Task $task): void;

    /**
     * Send `TaskUpdateStatus Command` into queue.
     */
    public function addTaskUpdateStatusTask(ProcessUuid $processUuid, Uuid $uuid, Task $task): void;

    /**
     * Send `TaskAssign Command` into queue.
     */
    public function addTaskAssignTask(ProcessUuid $processUuid, Uuid $uuid, Task $task): void;

    /**
     * Send `UserCreate Command` into queue.
     */
    public function addUserCreateTask(ProcessUuid $processUuid, User $user): void;

    /**
     * Send `UserUpdate Command` into queue.
     */
    public function addUserUpdateTask(ProcessUuid $processUuid, Uuid $uuid, User $user): void;
}
