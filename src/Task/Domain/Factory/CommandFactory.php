<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Domain\Factory;

use MicroModule\Base\Domain\Command\CommandInterface as BaseCommandInterface;
use MicroModule\Base\Domain\Dto\DtoInterface;
use MicroModule\Base\Domain\Exception\FactoryException;
use MicroModule\Base\Domain\ValueObject\Id;
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
 * @class CommandFactory
 *
 * @package Micro\Tracker\Task\Domain\Factory
 *
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CommandFactory implements CommandFactoryInterface
{
    protected const ALLOWED_COMMANDS = [
        self::TASK_CREATE_COMMAND, 
		self::TASK_CREATE_TASK_COMMAND, 
		self::TASK_UPDATE_STATUS_COMMAND, 
		self::TASK_UPDATE_STATUS_TASK_COMMAND, 
		self::TASK_ASSIGN_COMMAND, 
		self::TASK_ASSIGN_TASK_COMMAND, 
		self::USER_CREATE_COMMAND, 
		self::USER_CREATE_TASK_COMMAND, 
		self::USER_UPDATE_COMMAND, 
		self::USER_UPDATE_TASK_COMMAND,
    ];

    public function isCommandAllowed(string $commandType): bool
    {
        return in_array($commandType, static::ALLOWED_COMMANDS);
    }

    /**
     * Make command by command constant.
     *
     * @throws FactoryException
     * @throws Exception
     */
    public function makeCommandInstanceByType(...$args): BaseCommandInterface
    {
        $type = (string)array_shift($args);

        return match ($type) {
            self::TASK_CREATE_COMMAND => $this->makeTaskCreateCommand(...$args), 
			self::TASK_CREATE_TASK_COMMAND => $this->makeTaskCreateTaskCommand(...$args), 
			self::TASK_UPDATE_STATUS_COMMAND => $this->makeTaskUpdateStatusCommand(...$args), 
			self::TASK_UPDATE_STATUS_TASK_COMMAND => $this->makeTaskUpdateStatusTaskCommand(...$args), 
			self::TASK_ASSIGN_COMMAND => $this->makeTaskAssignCommand(...$args), 
			self::TASK_ASSIGN_TASK_COMMAND => $this->makeTaskAssignTaskCommand(...$args), 
			self::USER_CREATE_COMMAND => $this->makeUserCreateCommand(...$args), 
			self::USER_CREATE_TASK_COMMAND => $this->makeUserCreateTaskCommand(...$args), 
			self::USER_UPDATE_COMMAND => $this->makeUserUpdateCommand(...$args), 
			self::USER_UPDATE_TASK_COMMAND => $this->makeUserUpdateTaskCommand(...$args),
            default => throw new FactoryException(sprintf('Command for type `%s` not found!', $type)),
        };
    }

    /**
     * Make command from DTO.
     *
     * @throws FactoryException
     * @throws Exception
     */
    public function makeCommandInstanceByTypeFromDto(string $commandType, DtoInterface $dto): BaseCommandInterface
    {
        $data = $dto->normalize();
        $arguments = [];

        if (array_key_exists(DtoInterface::KEY_PROCESS_UUID, $data)) {
            $arguments[] = $data[DtoInterface::KEY_PROCESS_UUID];
            unset($data[DtoInterface::KEY_PROCESS_UUID]);
        } else {
            $arguments[] = ProcessUuid::fromNative(null)->toNative();
        }
        if (array_key_exists(DtoInterface::KEY_UUID, $data)) {
            $arguments[] = $data[DtoInterface::KEY_UUID];
            unset($data[DtoInterface::KEY_UUID]);
        }
        $arguments[] = $data;

        return $this->makeCommandInstanceByType($commandType, ...$arguments);
    }

    /**
     * Create TaskCreateCommand Command.
     */
    public function makeTaskCreateCommand(string $processUuid, array $task, ?array $payload = null): TaskCreateCommand
    {
		$task["created_at"] = $task["created_at"] ?? date_create("now");
		$task["updated_at"] = $task["updated_at"] ?? date_create("now");

        return new TaskCreateCommand(
			ProcessUuid::fromNative($processUuid), 
			Task::fromNative($task), 
			$payload ? Payload::fromNative($payload) : null
		);
    }

    /**
     * Create TaskCreateTaskCommand Command.
     */
    public function makeTaskCreateTaskCommand(string $processUuid, array $task, ?array $payload = null): TaskCreateTaskCommand
    {
        return new TaskCreateTaskCommand(
			ProcessUuid::fromNative($processUuid), 
			Task::fromNative($task), 
			$payload ? Payload::fromNative($payload) : null
		);
    }

    /**
     * Create TaskUpdateStatusCommand Command.
     */
    public function makeTaskUpdateStatusCommand(string $processUuid, string $uuid, array $task, ?array $payload = null): TaskUpdateStatusCommand
    {
		$task["updated_at"] = $task["updated_at"] ?? date_create("now");

        return new TaskUpdateStatusCommand(
			ProcessUuid::fromNative($processUuid), 
			Uuid::fromNative($uuid), 
			Task::fromNative($task),
			$payload ? Payload::fromNative($payload) : null
		);
    }

    /**
     * Create TaskUpdateStatusTaskCommand Command.
     */
    public function makeTaskUpdateStatusTaskCommand(string $processUuid, string $uuid, array $task, ?array $payload = null): TaskUpdateStatusTaskCommand
    {
        return new TaskUpdateStatusTaskCommand(
			ProcessUuid::fromNative($processUuid), 
			Uuid::fromNative($uuid), 
			Task::fromNative($task), 
			$payload ? Payload::fromNative($payload) : null
		);
    }

    /**
     * Create TaskAssignCommand Command.
     */
    public function makeTaskAssignCommand(string $processUuid, string $uuid, array $task, ?array $payload = null): TaskAssignCommand
    {
		$task["updated_at"] = $task["updated_at"] ?? date_create("now");

        return new TaskAssignCommand(
			ProcessUuid::fromNative($processUuid), 
			Uuid::fromNative($uuid), 
			Task::fromNative($task), 
			$payload ? Payload::fromNative($payload) : null
		);
    }

    /**
     * Create TaskAssignTaskCommand Command.
     */
    public function makeTaskAssignTaskCommand(string $processUuid, string $uuid, array $task, ?array $payload = null): TaskAssignTaskCommand
    {
        return new TaskAssignTaskCommand(
			ProcessUuid::fromNative($processUuid), 
			Uuid::fromNative($uuid), 
			Task::fromNative($task), 
			$payload ? Payload::fromNative($payload) : null
		);
    }

    /**
     * Create UserCreateCommand Command.
     */
    public function makeUserCreateCommand(string $processUuid, array $user, ?array $payload = null): UserCreateCommand
    {
		$user["created_at"] = $user["created_at"] ?? date_create("now");
		$user["updated_at"] = $user["updated_at"] ?? date_create("now");

        return new UserCreateCommand(
			ProcessUuid::fromNative($processUuid), 
			User::fromNative($user), 
			$payload ? Payload::fromNative($payload) : null
		);
    }

    /**
     * Create UserCreateTaskCommand Command.
     */
    public function makeUserCreateTaskCommand(string $processUuid, array $user, ?array $payload = null): UserCreateTaskCommand
    {
        return new UserCreateTaskCommand(
			ProcessUuid::fromNative($processUuid), 
			User::fromNative($user), 
			$payload ? Payload::fromNative($payload) : null
		);
    }

    /**
     * Create UserUpdateCommand Command.
     */
    public function makeUserUpdateCommand(string $processUuid, string $uuid, array $user, ?array $payload = null): UserUpdateCommand
    {
		$user["updated_at"] = $user["updated_at"] ?? date_create("now");

        return new UserUpdateCommand(
			ProcessUuid::fromNative($processUuid), 
			Uuid::fromNative($uuid), 
			User::fromNative($user), 
			$payload ? Payload::fromNative($payload) : null
		);
    }

    /**
     * Create UserUpdateTaskCommand Command.
     */
    public function makeUserUpdateTaskCommand(string $processUuid, string $uuid, array $user, ?array $payload = null): UserUpdateTaskCommand
    {
        return new UserUpdateTaskCommand(
			ProcessUuid::fromNative($processUuid), 
			Uuid::fromNative($uuid), 
			User::fromNative($user), 
			$payload ? Payload::fromNative($payload) : null
		);
    }

}
