<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Domain\Repository\EntityStore;

use MicroModule\Snapshotting\EventSourcing\SnapshottingEventSourcingRepositoryException;
use Micro\Tracker\Task\Domain\Entity\UserEntityInterface;
use Ramsey\Uuid\UuidInterface;

/**
 * @interface UserRepositoryInterface
 *
 * @package Micro\Tracker\Task\Domain\Repository\EntityStore
 */
interface UserRepositoryInterface
{
   /**
     * Retrieve TaskEntity with applied events
     */
    public function get(UuidInterface $uuid): UserEntityInterface;

    /**
     * Save TaskEntity last uncommitted events
     *
     * @throws SnapshottingEventSourcingRepositoryException
     */
    public function store(UserEntityInterface $entity): void;
}
