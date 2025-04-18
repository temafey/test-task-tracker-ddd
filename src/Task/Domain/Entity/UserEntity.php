<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Domain\Entity;

use Assert\Assertion;
use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Broadway\Serializer\Serializable;
use MicroModule\Base\Domain\Exception\ValueObjectInvalidException;
use MicroModule\Base\Domain\ValueObject\CreatedAt;
use MicroModule\Base\Domain\ValueObject\Payload;
use MicroModule\Base\Domain\ValueObject\ProcessUuid;
use MicroModule\Base\Domain\ValueObject\UpdatedAt;
use MicroModule\Base\Domain\ValueObject\Uuid;
use MicroModule\ValueObject\ValueObjectInterface;
use Micro\Tracker\Task\Domain\Event\UserCreatedEvent;
use Micro\Tracker\Task\Domain\Event\UserUpdatedEvent;
use Micro\Tracker\Task\Domain\Factory\EventFactory;
use Micro\Tracker\Task\Domain\Factory\EventFactoryInterface;
use Micro\Tracker\Task\Domain\Factory\ValueObjectFactory;
use Micro\Tracker\Task\Domain\Factory\ValueObjectFactoryInterface;
use Micro\Tracker\Task\Domain\ValueObject\Email;
use Micro\Tracker\Task\Domain\ValueObject\Name;
use Micro\Tracker\Task\Domain\ValueObject\User;

/**
 * @class UserEntity
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
class UserEntity extends EventSourcedAggregateRoot implements UserEntityInterface, Serializable
{
    /**
     * process_uuid value object.
     */
    protected ?ProcessUuid $processUuid = null;

    /**
     * uuid value object.
     */
    protected ?Uuid $uuid = null;

    /**
     * name value object.
     */
    protected ?Name $name = null;

    /**
     * email value object.
     */
    protected ?Email $email = null;

    /**
     * created_at value object.
     */
    protected ?CreatedAt $createdAt = null;

    /**
     * updated_at value object.
     */
    protected ?UpdatedAt $updatedAt = null;



    /**
     * Constructor
     */
    public function __construct(
		protected ?EventFactoryInterface $eventFactory = null,
		protected ?ValueObjectFactoryInterface $valueObjectFactory = null
	)
    {
		$this->eventFactory = $this->eventFactory ?? new EventFactory();
		$this->valueObjectFactory = $this->valueObjectFactory ?? new ValueObjectFactory();
        
    }

    /**
     * Factory method for creating a new UuidEntity.
     */
    public static function create(
		ProcessUuid $processUuid,
		Uuid $uuid,
		User $user,
		?EventFactoryInterface $eventFactory = null,
		?ValueObjectFactoryInterface $valueObjectFactory = null
	): self
    {
		$entity = new static($eventFactory, $valueObjectFactory);
		$entity->uuid = $uuid;
		$entity->userCreate($processUuid, $user);

        return $entity;
    }

    /**
     * Factory method for creating a new UserEntity.
     */
    public static function createActual(Uuid $uuid, User $user, ?EventFactoryInterface $eventFactory = null, ?ValueObjectFactoryInterface $valueObjectFactory = null): self
    {
		$entity = new static($eventFactory, $valueObjectFactory);
		$entity->uuid = $uuid;
		$entity->assembleFromValueObject($user);

        return $entity;
    }

    /**
     * Execute user-create command.
     */
    public function userCreate(ProcessUuid $processUuid, User $user, ?Payload $payload = null): void
    {
		$this->apply($this->eventFactory->makeUserCreatedEvent($processUuid, $this->uuid, $user, $payload));
    }

    /**
     * Apply UserCreatedEvent event.
     */
    public function applyUserCreatedEvent(UserCreatedEvent $event): void
    {
		$this->processUuid = $event->getProcessUuid();
		$this->uuid = $event->getUuid();
		$this->assembleFromValueObject($event->getUser());
    }

    /**
     * Execute user-update command.
     */
    public function userUpdate(ProcessUuid $processUuid, User $user, ?Payload $payload = null): void
    {
		$this->apply($this->eventFactory->makeUserUpdatedEvent($processUuid, $this->uuid, $user, $payload));
    }

    /**
     * Apply UserUpdatedEvent event.
     */
    public function applyUserUpdatedEvent(UserUpdatedEvent $event): void
    {
		$this->processUuid = $event->getProcessUuid();
		$this->assembleFromValueObject($event->getUser());
    }

    /**
     * Return process_uuid value object.
     */
    public function getProcessUuid(): ?ProcessUuid
    {
        return $this->processUuid;
    }

    /**
     * Return uuid value object.
     */
    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    /**
     * Return name value object.
     */
    public function getName(): ?Name
    {
        return $this->name;
    }

    /**
     * Return email value object.
     */
    public function getEmail(): ?Email
    {
        return $this->email;
    }

    /**
     * Return created_at value object.
     */
    public function getCreatedAt(): ?CreatedAt
    {
        return $this->createdAt;
    }

    /**
     * Return updated_at value object.
     */
    public function getUpdatedAt(): ?UpdatedAt
    {
        return $this->updatedAt;
    }

    /**
     * Factory method for creating a new UserEntity.
     */
    public static function deserialize(array $data): self
    {
		Assertion::keyExists($data, self::KEY_UUID);
		$user = User::fromNative($data);

        return static::createActual(Uuid::fromNative($data[self::KEY_UUID]), $user);
    }

    /**
     * Assemble entity from value object.
     */
    public function assembleFromValueObject(ValueObjectInterface $valueObject): void
    {
		if (!$valueObject instanceof User) {
			throw new ValueObjectInvalidException('UserEntity can be assembled only with User value object');
		}

		if (null !== $valueObject->getName()) {
			$this->name = $valueObject->getName();
		}

		if (null !== $valueObject->getEmail()) {
			$this->email = $valueObject->getEmail();
		}

		if (null !== $valueObject->getCreatedAt()) {
			$this->createdAt = $valueObject->getCreatedAt();
		}

		if (null !== $valueObject->getUpdatedAt()) {
			$this->updatedAt = $valueObject->getUpdatedAt();
		}
    }

    /**
     * Assemble value object from entity.
     */
    public function assembleToValueObject(): ValueObjectInterface
    {
		$user = $this->normalize();

        return User::fromNative($user);
    }

    /**
     * Convert entity object to array.
     */
    public function normalize(): array
    {
		$data = [];
		$data["process_uuid"] = $this->getProcessUuid()?->toNative();
		$data["uuid"] = $this->getUuid()?->toNative();
		$data["name"] = $this->getName()?->toNative();
		$data["email"] = $this->getEmail()?->toNative();
		$data["created_at"] = $this->getCreatedAt()?->toNative();
		$data["updated_at"] = $this->getUpdatedAt()?->toNative();

        return $data;
    }

   /**
    * Converting an object into an array.
    */
    public function serialize(): array
    {
        return $this->normalize();
    }

    /**
     * Return current aggregate root unique key.
     */
    public function getAggregateRootId(): string
    {
        return $this->uuid->toNative();
    }

    /**
     * Return entity primary key value.
     */
    public function getPrimaryKeyValue(): string
    {
        return $this->getAggregateRootId();
    }
}
