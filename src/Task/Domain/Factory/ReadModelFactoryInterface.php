<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Domain\Factory;

use MicroModule\Base\Domain\ValueObject\Uuid;
use Micro\Tracker\Task\Domain\Entity\TaskEntityInterface;
use Micro\Tracker\Task\Domain\Entity\UserEntityInterface;
use Micro\Tracker\Task\Domain\ReadModel\TaskReadModelInterface;
use Micro\Tracker\Task\Domain\ReadModel\UserReadModelInterface;
use Micro\Tracker\Task\Domain\ValueObject\Task;
use Micro\Tracker\Task\Domain\ValueObject\User;

/**
 * @interface ReadModelFactoryInterface
 *
 * @package Micro\Tracker\Task\Domain\Factory
 */
interface ReadModelFactoryInterface
{

    /**
      * Create Task read model.
      */
     public function makeTaskActualInstance(Task $task, Uuid $uuid): TaskReadModelInterface;


    /**
      * Create Task read model from Entity.
      */
     public function makeTaskActualInstanceByEntity(TaskEntityInterface $taskEntity): TaskReadModelInterface;

    /**
      * Create User read model.
      */
     public function makeUserActualInstance(User $user, Uuid $uuid): UserReadModelInterface;


    /**
      * Create User read model from Entity.
      */
     public function makeUserActualInstanceByEntity(UserEntityInterface $userEntity): UserReadModelInterface;

}
