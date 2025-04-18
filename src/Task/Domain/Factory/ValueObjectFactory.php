<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Domain\Factory;

use MicroModule\Base\Domain\Factory\CommonValueObjectFactory;
use Micro\Tracker\Task\Domain\ValueObject\AssigneeId;
use Micro\Tracker\Task\Domain\ValueObject\Description;
use Micro\Tracker\Task\Domain\ValueObject\Email;
use Micro\Tracker\Task\Domain\ValueObject\Name;
use Micro\Tracker\Task\Domain\ValueObject\Status;
use Micro\Tracker\Task\Domain\ValueObject\Task;
use Micro\Tracker\Task\Domain\ValueObject\Title;
use Micro\Tracker\Task\Domain\ValueObject\User;

/**
 * @class ValueObjectFactory
 *
 * @package Micro\Tracker\Task\Domain\Factory
 *
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */

class ValueObjectFactory extends CommonValueObjectFactory implements ValueObjectFactoryInterface
{
    /**
     * Create Title ValueObject.
     */
    public function makeTitle(string $title): Title
    {
        return Title::fromNative($title);
    }

    /**
     * Create Description ValueObject.
     */
    public function makeDescription(string $description): Description
    {
        return Description::fromNative($description);
    }

    /**
     * Create Status ValueObject.
     */
    public function makeStatus(string $status): Status
    {
        return Status::fromNative($status);
    }

    /**
     * Create AssigneeId ValueObject.
     */
    public function makeAssigneeId(string $assigneeId): AssigneeId
    {
        return AssigneeId::fromNative($assigneeId);
    }

    /**
     * Create Name ValueObject.
     */
    public function makeName(string $name): Name
    {
        return Name::fromNative($name);
    }

    /**
     * Create Email ValueObject.
     */
    public function makeEmail(string $email): Email
    {
        return Email::fromNative($email);
    }

    /**
     * Create Task ValueObject.
     */
    public function makeTask(array $task): Task
    {
        return Task::fromNative($task);
    }

    /**
     * Create User ValueObject.
     */
    public function makeUser(array $user): User
    {
        return User::fromNative($user);
    }
}
