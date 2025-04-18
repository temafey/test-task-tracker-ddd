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
 * @class EntityFactory
 *
 * @package Micro\Tracker\Task\Domain\Factory
 *
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class EntityFactory implements EntityFactoryInterface
{

    /**
     * Create TaskEntity instance from value object with Uuid & ProcessId.
     */
    public function createTaskInstance(
        ProcessUuid $processUuid,
        Task $task,
        ?EventFactoryInterface $eventFactory = null,
        ?ValueObjectFactoryInterface $valueObjectFactory = null
    ): TaskEntityInterface {
        $uuid = new Uuid();

        return TaskEntity::create(
            $processUuid,
            $uuid,
            $task,
            $eventFactory,
            $valueObjectFactory
        );
    }

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
    ): TaskEntityInterface {
        return TaskEntity::createActual(
            $uuid,
            $task,
            $eventFactory,
            $valueObjectFactory
        );
    }

    /**
     * Create UserEntity instance from value object with Uuid & ProcessId.
     */
    public function createUserInstance(
        ProcessUuid $processUuid,
        User $user,
        ?EventFactoryInterface $eventFactory = null,
        ?ValueObjectFactoryInterface $valueObjectFactory = null
    ): UserEntityInterface {
        $uuid = new Uuid();

        return UserEntity::create(
            $processUuid,
            $uuid,
            $user,
            $eventFactory,
            $valueObjectFactory
        );
    }

    /**
     * Create UserEntity instance from value object with Uuid.
     *
     * @throws ValueObjectInvalidException
     */
    public function makeActualUserInstance(
        Uuid $uuid,
        User $user,
        ?EventFactoryInterface $eventFactory = null,
        ?ValueObjectFactoryInterface $valueObjectFactory = null
    ): UserEntityInterface {
        return UserEntity::createActual(
            $uuid,
            $user,
            $eventFactory,
            $valueObjectFactory
        );
    }

}
