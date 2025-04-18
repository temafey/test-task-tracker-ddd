<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Domain\Factory;

use MicroModule\Base\Domain\Factory\CommandFactoryInterface as BaseCommandFactoryInterface;
use MicroModule\Base\Domain\ValueObject\Payload;
use MicroModule\Base\Domain\ValueObject\ProcessUuid;
use MicroModule\Base\Domain\ValueObject\Uuid;
use Micro\Tracker\Task\Domain\Command\TaskAssignCommand;
use Micro\Tracker\Task\Domain\Command\TaskCreateCommand;
use Micro\Tracker\Task\Domain\Command\TaskUpdateStatusCommand;
use Micro\Tracker\Task\Domain\Command\Task\TaskAssignTaskCommand;
use Micro\Tracker\Task\Domain\Command\Task\TaskCreateTaskCommand;
use Micro\Tracker\Task\Domain\Command\Task\TaskUpdateStatusTaskCommand;
use Micro\Tracker\Task\Domain\Command\Task\UserCreateTaskCommand;
use Micro\Tracker\Task\Domain\Command\Task\UserUpdateTaskCommand;
use Micro\Tracker\Task\Domain\Command\UserCreateCommand;
use Micro\Tracker\Task\Domain\Command\UserUpdateCommand;
use Micro\Tracker\Task\Domain\ValueObject\Task;
use Micro\Tracker\Task\Domain\ValueObject\User;

/**
 * @interface CommandFactoryInterface
 *
 * @package Micro\Tracker\Task\Domain\Factory
 */
interface CommandFactoryInterface extends BaseCommandFactoryInterface
{
	public const TASK_CREATE_COMMAND = "TaskCreateCommand"; 
	public const TASK_CREATE_TASK_COMMAND = "TaskCreateTaskCommand"; 
	public const TASK_UPDATE_STATUS_COMMAND = "TaskUpdateStatusCommand"; 
	public const TASK_UPDATE_STATUS_TASK_COMMAND = "TaskUpdateStatusTaskCommand"; 
	public const TASK_ASSIGN_COMMAND = "TaskAssignCommand"; 
	public const TASK_ASSIGN_TASK_COMMAND = "TaskAssignTaskCommand"; 
	public const USER_CREATE_COMMAND = "UserCreateCommand"; 
	public const USER_CREATE_TASK_COMMAND = "UserCreateTaskCommand"; 
	public const USER_UPDATE_COMMAND = "UserUpdateCommand"; 
	public const USER_UPDATE_TASK_COMMAND = "UserUpdateTaskCommand";

    /**
     * Create TaskCreateCommand Command.
     */
    public function makeTaskCreateCommand(string $processUuid, array $task, ?array $payload = null): TaskCreateCommand;

    /**
     * Create TaskCreateTaskCommand Command.
     */
    public function makeTaskCreateTaskCommand(string $processUuid, array $task, ?array $payload = null): TaskCreateTaskCommand;

    /**
     * Create TaskUpdateStatusCommand Command.
     */
    public function makeTaskUpdateStatusCommand(string $processUuid, string $uuid, array $task, ?array $payload = null): TaskUpdateStatusCommand;

    /**
     * Create TaskUpdateStatusTaskCommand Command.
     */
    public function makeTaskUpdateStatusTaskCommand(string $processUuid, string $uuid, array $task, ?array $payload = null): TaskUpdateStatusTaskCommand;

    /**
     * Create TaskAssignCommand Command.
     */
    public function makeTaskAssignCommand(string $processUuid, string $uuid, array $task, ?array $payload = null): TaskAssignCommand;

    /**
     * Create TaskAssignTaskCommand Command.
     */
    public function makeTaskAssignTaskCommand(string $processUuid, string $uuid, array $task, ?array $payload = null): TaskAssignTaskCommand;

    /**
     * Create UserCreateCommand Command.
     */
    public function makeUserCreateCommand(string $processUuid, array $user, ?array $payload = null): UserCreateCommand;

    /**
     * Create UserCreateTaskCommand Command.
     */
    public function makeUserCreateTaskCommand(string $processUuid, array $user, ?array $payload = null): UserCreateTaskCommand;

    /**
     * Create UserUpdateCommand Command.
     */
    public function makeUserUpdateCommand(string $processUuid, string $uuid, array $user, ?array $payload = null): UserUpdateCommand;

    /**
     * Create UserUpdateTaskCommand Command.
     */
    public function makeUserUpdateTaskCommand(string $processUuid, string $uuid, array $user, ?array $payload = null): UserUpdateTaskCommand;

}
