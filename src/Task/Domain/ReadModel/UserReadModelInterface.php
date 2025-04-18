<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Domain\ReadModel;

use Exception;
use MicroModule\Base\Domain\Exception\ValueObjectInvalidException;
use MicroModule\Base\Domain\ReadModel\ReadModelInterface;
use MicroModule\Base\Domain\ValueObject\Uuid;
use Micro\Tracker\Task\Domain\Entity\UserEntityInterface;
use Micro\Tracker\Task\Domain\ValueObject\User;

/**
 * @interface UserReadModelInterface
 *
 * @package Micro\Tracker\Task\Domain\ReadModel
 */
interface UserReadModelInterface extends ReadModelInterface
{
    /**
     * Return uuid value object.
     */
    public function getUuid(): ?Uuid;

    /**
     * Return name value.
     */
    public function getName(): ?string;

    /**
     * Return email value.
     */
    public function getEmail(): ?string;

    /**
     * Return created_at value.
     */
    public function getCreatedAt(): ?\DateTimeInterface;

    /**
     * Return updated_at value.
     */
    public function getUpdatedAt(): ?\DateTimeInterface;

	/**
     * Create UserEntity by UserEntity
     *
     * @throws Exception
     */
    public static function createByEntity(UserEntityInterface $entity): UserReadModelInterface;

    /**
     * Create UserEntity by User value object.
     *
     * @throws ValueObjectInvalidException
     */
    public static function createByValueObject(User $entityValueObject, Uuid $uuid): UserReadModelInterface;

    /**
     * Update UserEntity by User value object.
     *
     * @throws ValueObjectInvalidException
     */
    public function updateByValueObject(User $entityValueObject, Uuid $uuid): void;
}
