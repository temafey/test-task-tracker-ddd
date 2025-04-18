<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Application\Factory;

use MicroModule\Base\Application\Factory\DtoFactoryInterface as BaseDtoFactoryInterface;
use Micro\Tracker\Task\Application\Dto\TaskDto;
use Micro\Tracker\Task\Application\Dto\UserDto;

/**
 * @interface DtoFactoryInterface
 *
 * @package Micro\Tracker\Task\Application\Factory

 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 */
interface DtoFactoryInterface extends BaseDtoFactoryInterface
{
	public const TASK_DTO = "TaskDto"; 
	public const USER_DTO = "UserDto";

    /**
     * Create TaskDto Dto.
     */
    public function makeTaskDto(
		string $processUuid,
		string $uuid,
		string $title,
		string $description,
		string $status,
		string $assigneeId
	): TaskDto;

    /**
     * Create TaskDto Dto.
     */
    public function makeTaskDtoFromData(array $data): TaskDto;

    /**
     * Create UserDto Dto.
     */
    public function makeUserDto(
		string $processUuid,
		string $uuid,
		string $name,
		string $email
	): UserDto;

    /**
     * Create UserDto Dto.
     */
    public function makeUserDtoFromData(array $data): UserDto;

}
