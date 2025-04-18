<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Domain\Factory;

use MicroModule\Base\Domain\Exception\ValueObjectInvalidException;
use MicroModule\Base\Domain\ValueObject\Payload;
use MicroModule\Base\Domain\ValueObject\ProcessUuid;
use MicroModule\Base\Domain\ValueObject\Uuid;
use Micro\Tracker\Task\Domain\Entity\TaskEntity;
use Micro\Tracker\Task\Domain\Entity\TaskEntityInterface;
use Micro\Tracker\Task\Domain\Entity\UserEntity;
use Micro\Tracker\Task\Domain\Entity\UserEntityInterface;
use Micro\Tracker\Task\Domain\ValueObject\Task;
use Micro\Tracker\Task\Domain\ValueObject\User;

/**
 * @interface EntityFactoryInterface
 *
 * @package Micro\Tracker\Task\Domain\Factory
 */
interface EntityFactoryInterface
{

    /**
     * Create TaskEntity instance from value object with Uuid & ProcessId.
     */
    public function createTaskInstance(
        ProcessUuid $processUuid,
        Task $task,
        ?EventFactoryInterface $eventFactory = null,
        ?ValueObjectFactoryInterface $valueObjectFactory = null
    ): TaskEntityInterface;

    /**
     * Create TaskEntity instance from value object with Uuid.
     *
     * @throws ValueObjectInvalidException
     */
    public function makeActualTaskInstance(
        Uuid $uuid,
        Task $task,
        ?EventFactoryInterface $eventFactory = null,
        ?ValueObjectFactoryInterface $valueObjectFactory = null
    ): TaskEntityInterface;

    /**
     * Create UserEntity instance from value object with Uuid & ProcessId.
     */
    public function createUserInstance(
        ProcessUuid $processUuid,
        User $user,
        ?EventFactoryInterface $eventFactory = null,
        ?ValueObjectFactoryInterface $valueObjectFactory = null
    ): UserEntityInterface;

    /**
     * Create UserEntity instance from value object with Uuid.
     *
     * @throws ValueObjectInvalidException
     */
    public function makeActualUserInstance(
        Uuid $uuid,
        User $task,
        ?EventFactoryInterface $eventFactory = null,
        ?ValueObjectFactoryInterface $valueObjectFactory = null
    ): UserEntityInterface;

}
