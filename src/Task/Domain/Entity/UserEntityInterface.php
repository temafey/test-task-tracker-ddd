<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Domain\Entity;

use MicroModule\Base\Domain\Entity\EntityInterface;
use MicroModule\Base\Domain\ValueObject\CreatedAt;
use MicroModule\Base\Domain\ValueObject\Payload;
use MicroModule\Base\Domain\ValueObject\ProcessUuid;
use MicroModule\Base\Domain\ValueObject\UpdatedAt;
use MicroModule\Base\Domain\ValueObject\Uuid;
use Micro\Tracker\Task\Domain\ValueObject\Email;
use Micro\Tracker\Task\Domain\ValueObject\Name;
use Micro\Tracker\Task\Domain\ValueObject\User;

/**
 * @interface UserEntityInterface
 *
 * @package Micro\Tracker\Task\Domain\Entity
 */
interface UserEntityInterface extends EntityInterface
{
    /**
     * Execute user-create command.
     */
    public function userCreate(ProcessUuid $processUuid, User $user, ?Payload $payload = null): void;

    /**
     * Execute user-update command.
     */
    public function userUpdate(ProcessUuid $processUuid, User $user, ?Payload $payload = null): void;

    /**
     * Return name value object.
     */
    public function getName(): ?Name;

    /**
     * Return email value object.
     */
    public function getEmail(): ?Email;

    /**
     * Return created_at value object.
     */
    public function getCreatedAt(): ?CreatedAt;

    /**
     * Return updated_at value object.
     */
    public function getUpdatedAt(): ?UpdatedAt;
}
