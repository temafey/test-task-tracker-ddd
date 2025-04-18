<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Domain\ReadModel;

use Exception;
use MicroModule\Base\Domain\Exception\ValueObjectInvalidException;
use MicroModule\Base\Domain\ReadModel\ReadModelInterface;
use MicroModule\Base\Domain\ValueObject\Uuid;
use Micro\Tracker\Task\Domain\Entity\TaskEntityInterface;
use Micro\Tracker\Task\Domain\ValueObject\Task;

/**
 * @interface TaskReadModelInterface
 *
 * @package Micro\Tracker\Task\Domain\ReadModel
 */
interface TaskReadModelInterface extends ReadModelInterface
{
    /**
     * Return uuid value object.
     */
    public function getUuid(): ?Uuid;

    /**
     * Return title value.
     */
    public function getTitle(): ?string;

    /**
     * Return description value.
     */
    public function getDescription(): ?string;

    /**
     * Return status value.
     */
    public function getStatus(): ?string;

    /**
     * Return assignee_id value.
     */
    public function getAssigneeId(): ?string;

    /**
     * Return created_at value.
     */
    public function getCreatedAt(): ?\DateTimeInterface;

    /**
     * Return updated_at value.
     */
    public function getUpdatedAt(): ?\DateTimeInterface;

	/**
     * Create TaskEntity by TaskEntity
     *
     * @throws Exception
     */
    public static function createByEntity(TaskEntityInterface $entity): TaskReadModelInterface;

    /**
     * Create TaskEntity by Task value object.
     *
     * @throws ValueObjectInvalidException
     */
    public static function createByValueObject(Task $entityValueObject, Uuid $uuid): TaskReadModelInterface;

    /**
     * Update TaskEntity by Task value object.
     *
     * @throws ValueObjectInvalidException
     */
    public function updateByValueObject(Task $entityValueObject, Uuid $uuid): void;
}
