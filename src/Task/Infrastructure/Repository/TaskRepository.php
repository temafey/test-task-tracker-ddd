<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Infrastructure\Repository;

use MicroModule\Base\Domain\ValueObject\ProcessUuid;
use MicroModule\Base\Domain\ValueObject\Uuid;
use MicroModule\Base\Infrastructure\Repository\TaskRepository as BaseTaskRepository;
use Micro\Tracker\Task\Domain\Factory\CommandFactoryInterface;
use Micro\Tracker\Task\Domain\Repository\TaskRepositoryInterface;
use Micro\Tracker\Task\Domain\ValueObject\Task;
use Micro\Tracker\Task\Domain\ValueObject\User;

/**
 * @class TaskRepository
 *
 * @package Micro\Tracker\Task\Infrastructure\Repository

 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class TaskRepository extends BaseTaskRepository implements TaskRepositoryInterface
{

    /**
     * Send `TaskCreate Command` into queue.
     */
    public function addTaskCreateTask(ProcessUuid $processUuid, Task $task): void
    {
		$this->produce(
			CommandFactoryInterface::TASK_CREATE_COMMAND,
			[
				$processUuid->toNative(),
				$task->toNative()
			]
		);
    }

    /**
     * Send `TaskUpdateStatus Command` into queue.
     */
    public function addTaskUpdateStatusTask(ProcessUuid $processUuid, Uuid $uuid, Task $task): void
    {
		$this->produce(
			CommandFactoryInterface::TASK_UPDATE_STATUS_COMMAND,
			[
				$processUuid->toNative(),
				$uuid->toNative(),
				$task->toNative()
			]
		);
    }

    /**
     * Send `TaskAssign Command` into queue.
     */
    public function addTaskAssignTask(ProcessUuid $processUuid, Uuid $uuid, Task $task): void
    {
		$this->produce(
			CommandFactoryInterface::TASK_ASSIGN_COMMAND,
			[
				$processUuid->toNative(),
				$uuid->toNative(),
				$task->toNative()
			]
		);
    }

    /**
     * Send `UserCreate Command` into queue.
     */
    public function addUserCreateTask(ProcessUuid $processUuid, User $user): void
    {
		$this->produce(
			CommandFactoryInterface::USER_CREATE_COMMAND,
			[
				$processUuid->toNative(),
				$user->toNative()
			]
		);
    }

    /**
     * Send `UserUpdate Command` into queue.
     */
    public function addUserUpdateTask(ProcessUuid $processUuid, Uuid $uuid, User $user): void
    {
		$this->produce(
			CommandFactoryInterface::USER_UPDATE_COMMAND,
			[
				$processUuid->toNative(),
				$uuid->toNative(),
				$user->toNative()
			]
		);
    }

}
