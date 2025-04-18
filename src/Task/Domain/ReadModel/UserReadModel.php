<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Domain\ReadModel;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Exception;
use MicroModule\Base\Domain\Exception\ValueObjectInvalidException;
use MicroModule\Base\Domain\ValueObject\Uuid;
use MicroModule\ValueObject\ValueObjectInterface;
use Micro\Tracker\Task\Domain\Entity\UserEntityInterface;
use Micro\Tracker\Task\Domain\ValueObject\User;

/**
 * @class UserReadModel
 *
 * @package Micro\Tracker\Task\Domain\ReadModel
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
 #[Entity]
 #[Table(name: 'users')]
class UserReadModel implements UserReadModelInterface
{
    #[Id]
	#[Column(
		name : 'uuid',
		type : 'guid',
		unique : true,
		nullable : true
	)]
    protected ?Uuid $uuid = null;

    #[Column(
		name : 'name',
		type : 'string',
		nullable : true
	)]
    protected ?string $name = null;

    #[Column(
		name : 'email',
		type : 'string',
        unique : true,
		nullable : true
	)]
    protected ?string $email = null;

    #[Column(
		name : 'created_at',
		type : 'datetime',
		nullable : true
	)]
    protected ?\DateTimeInterface $createdAt = null;

    #[Column(
		name : 'updated_at',
		type : 'datetime',
		nullable : true
	)]
    protected ?\DateTimeInterface $updatedAt = null;


    /**
     * Return uuid value object.
     */
    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    /**
     * Return name value.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Return email value.
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Return created_at value.
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Return updated_at value.
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Assemble entity from value object.
     */
    public function assembleFromValueObject(ValueObjectInterface $valueObject, ?Uuid $uuid): void
    {
		if (!$valueObject instanceof User) {
			throw new ValueObjectInvalidException('UserEntity can be assembled only with User value object');
		}
		$this->uuid = $uuid;
		$this->name = $valueObject->getName()?->toNative();
		$this->email = $valueObject->getEmail()?->toNative();
		$this->createdAt = $valueObject->getCreatedAt()?->toNative();
		$this->updatedAt = $valueObject->getUpdatedAt()?->toNative();
    }

    /**
     * Convert entity object to array.
     */
    public function toArray(): array
    {
		$data = [];
		$data["uuid"] = $this->uuid;
		$data["name"] = $this->name;
		$data["email"] = $this->email;
		$data["created_at"] = $this->createdAt?->format(\DateTimeInterface::ATOM);
		$data["updated_at"] = $this->updatedAt?->format(\DateTimeInterface::ATOM);

        return $data;
    }

    /**
     * Return entity primary key value
     */
    public function getPrimaryKeyValue(): ?string
    {
        return $this->uuid?->toNative();
    }
	
	/**
     * Create UserReadModel by UserEntity
     *
     * @throws Exception
     */
    public static function createByEntity(UserEntityInterface $entity): UserReadModelInterface
    {
        $readModel = new static();
        $readModel->assembleFromValueObject($entity->assembleToValueObject(), $entity->getUuid());

        return $readModel;
    }

    /**
     * Create UserReadModel by User value object.
     *
     * @throws ValueObjectInvalidException
     */
    public static function createByValueObject(User $entityValueObject, Uuid $uuid): UserReadModelInterface
    {
        $readModel = new static();
        $readModel->assembleFromValueObject($entityValueObject, $uuid);

        return $readModel;
    }

    /**
     * Update UserReadModel by User value object.
     *
     * @throws ValueObjectInvalidException
     */
    public function updateByValueObject(User $entityValueObject, Uuid $uuid): void
    {
        $this->assembleFromValueObject($entityValueObject, $uuid);
    }

    /**
     * Convert entity object to array.
     *
     * @return array<string, mixed>
     */
    public function normalize(): array
    {
        return $this->toArray();
    }

     /**
      * Specify data which should be serialized to JSON
      */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
