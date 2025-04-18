<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Domain\Repository\EntityStore;

use MicroModule\Snapshotting\EventSourcing\SnapshottingEventSourcingRepositoryException;
use Micro\Tracker\Task\Domain\Entity\TaskEntityInterface;
use Ramsey\Uuid\UuidInterface;

/**
 * @interface TaskRepositoryInterface
 *
 * @package Micro\Tracker\Task\Domain\Repository\EntityStore
 */
interface TaskRepositoryInterface
{
   /**
     * Retrieve TaskEntity with applied events
     */
    public function get(UuidInterface $uuid): TaskEntityInterface;

    /**
     * Save TaskEntity last uncommitted events
     *
     * @throws SnapshottingEventSourcingRepositoryException
     */
    public function store(TaskEntityInterface $entity): void;
}
