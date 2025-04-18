<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Domain\Entity;

use MicroModule\Base\Domain\Entity\EntityInterface;
use MicroModule\Base\Domain\ValueObject\CreatedAt;
use MicroModule\Base\Domain\ValueObject\Payload;
use MicroModule\Base\Domain\ValueObject\ProcessUuid;
use MicroModule\Base\Domain\ValueObject\UpdatedAt;
use MicroModule\Base\Domain\ValueObject\Uuid;
use Micro\Tracker\Task\Domain\ValueObject\AssigneeId;
use Micro\Tracker\Task\Domain\ValueObject\Description;
use Micro\Tracker\Task\Domain\ValueObject\Status;
use Micro\Tracker\Task\Domain\ValueObject\Task;
use Micro\Tracker\Task\Domain\ValueObject\Title;

/**
 * @interface TaskEntityInterface
 *
 * @package Micro\Tracker\Task\Domain\Entity
 */
interface TaskEntityInterface extends EntityInterface
{
    /**
     * Execute task-create command.
     */
    public function taskCreate(ProcessUuid $processUuid, Task $task, ?Payload $payload = null): void;

    /**
     * Execute task-update-status command.
     */
    public function taskUpdateStatus(ProcessUuid $processUuid, Task $task, ?Payload $payload = null): void;

    /**
     * Execute task-assign command.
     */
    public function taskAssign(ProcessUuid $processUuid, Task $task, ?Payload $payload = null): void;

    /**
     * Return title value object.
     */
    public function getTitle(): ?Title;

    /**
     * Return description value object.
     */
    public function getDescription(): ?Description;

    /**
     * Return status value object.
     */
    public function getStatus(): ?Status;

    /**
     * Return assignee_id value object.
     */
    public function getAssigneeId(): ?AssigneeId;

    /**
     * Return created_at value object.
     */
    public function getCreatedAt(): ?CreatedAt;

    /**
     * Return updated_at value object.
     */
    public function getUpdatedAt(): ?UpdatedAt;
}
