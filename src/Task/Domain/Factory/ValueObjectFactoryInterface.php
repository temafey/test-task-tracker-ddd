<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Domain\Factory;

use MicroModule\Base\Domain\Factory\CommonValueObjectFactoryInterface;
use Micro\Tracker\Task\Domain\ValueObject\AssigneeId;
use Micro\Tracker\Task\Domain\ValueObject\Description;
use Micro\Tracker\Task\Domain\ValueObject\Email;
use Micro\Tracker\Task\Domain\ValueObject\Name;
use Micro\Tracker\Task\Domain\ValueObject\Status;
use Micro\Tracker\Task\Domain\ValueObject\Task;
use Micro\Tracker\Task\Domain\ValueObject\Title;
use Micro\Tracker\Task\Domain\ValueObject\User;

/**
 * @interface ValueObjectFactoryInterface
 *
 * @package Micro\Tracker\Task\Domain\Factory
 */
interface ValueObjectFactoryInterface extends CommonValueObjectFactoryInterface
{
    /**
     * Create Title ValueObject.
     */
    public function makeTitle(string $title): Title;

    /**
     * Create Description ValueObject.
     */
    public function makeDescription(string $description): Description;

    /**
     * Create Status ValueObject.
     */
    public function makeStatus(string $status): Status;

    /**
     * Create AssigneeId ValueObject.
     */
    public function makeAssigneeId(string $assigneeId): AssigneeId;

    /**
     * Create Name ValueObject.
     */
    public function makeName(string $name): Name;

    /**
     * Create Email ValueObject.
     */
    public function makeEmail(string $email): Email;

    /**
     * Create Task ValueObject.
     */
    public function makeTask(array $task): Task;

    /**
     * Create User ValueObject.
     */
    public function makeUser(array $user): User;
}
