<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Application\Factory;

use MicroModule\Base\Domain\Dto\DtoInterface;
use MicroModule\Base\Domain\Exception\FactoryException;
use Micro\Tracker\Task\Application\Dto\TaskDto;
use Micro\Tracker\Task\Application\Dto\UserDto;

/**
 * @class DtoFactory
 *
 * @package Micro\Tracker\Task\Application\Factory
 *
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 */
class DtoFactory implements DtoFactoryInterface
{
    /**
     * Make command by command constant.
     *
     * @throws FactoryException
     * @throws Exception
     */
    public function makeDtoByType(...$args): DtoInterface
    {
        $type = (string)array_shift($args);

        return match ($type) {
            self::TASK_DTO => $this->makeTaskDto(...$args), 
			self::USER_DTO => $this->makeUserDto(...$args),
            default => throw new FactoryException(sprintf('Dto for type `%s` not found!', $type)),
        };
    }

    /**
     * Create TaskDto object .
     */
    public function makeTaskDto(
			string $processUuid, 
			string $uuid, 
			string $title, 
			string $description, 
			string $status, 
			string $assigneeId
		): TaskDto
    {
        return new TaskDto(
			$processUuid, $uuid, $title, $description, $status, $assigneeId
		);
    }

    /**
     * Create TaskDto Dto.
     */
    public function makeTaskDtoFromData(array $data): TaskDto
    {
        return TaskDto::denormalize($data);
    }

    /**
     * Create UserDto object .
     */
    public function makeUserDto(
			string $processUuid, 
			string $uuid, 
			string $name, 
			string $email
		): UserDto
    {
        return new UserDto(
			$processUuid, $uuid, $name, $email
		);
    }

    /**
     * Create UserDto Dto.
     */
    public function makeUserDtoFromData(array $data): UserDto
    {
        return UserDto::denormalize($data);
    }

}
