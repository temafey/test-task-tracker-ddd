<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Domain\Factory;

use MicroModule\Base\Domain\Exception\ValueObjectInvalidException;
use MicroModule\Base\Domain\ValueObject\Uuid;
use Micro\Tracker\Task\Domain\Entity\TaskEntityInterface;
use Micro\Tracker\Task\Domain\Entity\UserEntityInterface;
use Micro\Tracker\Task\Domain\ReadModel\TaskReadModel;
use Micro\Tracker\Task\Domain\ReadModel\TaskReadModelInterface;
use Micro\Tracker\Task\Domain\ReadModel\UserReadModel;
use Micro\Tracker\Task\Domain\ReadModel\UserReadModelInterface;
use Micro\Tracker\Task\Domain\ValueObject\Task;
use Micro\Tracker\Task\Domain\ValueObject\User;

/**
 * @class ReadModelFactory
 *
 * @package Micro\Tracker\Task\Domain\Factory
 *
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ReadModelFactory implements ReadModelFactoryInterface
{

    /**
     * Create Task read model.
     *
     * @throws ValueObjectInvalidException
     */
    public function makeTaskActualInstance(Task $task, Uuid $uuid): TaskReadModelInterface
    {
        return TaskReadModel::createByValueObject($task, $uuid);
    }

    /**
     * Create Task read model from Entity.
     */
    public function makeTaskActualInstanceByEntity(TaskEntityInterface $taskEntity): TaskReadModelInterface
    {
        return TaskReadModel::createByEntity($taskEntity);
    }

    /**
     * Create User read model.
     *
     * @throws ValueObjectInvalidException
     */
    public function makeUserActualInstance(User $user, Uuid $uuid): UserReadModelInterface
    {
        return UserReadModel::createByValueObject($user, $uuid);
    }

    /**
     * Create User read model from Entity.
     */
    public function makeUserActualInstanceByEntity(UserEntityInterface $userEntity): UserReadModelInterface
    {
        return UserReadModel::createByEntity($userEntity);
    }

}
