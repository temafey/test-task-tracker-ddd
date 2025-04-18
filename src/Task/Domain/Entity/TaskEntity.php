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
use Micro\Tracker\Task\Domain\Event\TaskAssignedEvent;
use Micro\Tracker\Task\Domain\Event\TaskCreatedEvent;
use Micro\Tracker\Task\Domain\Event\TaskStatusUpdatedEvent;
use Micro\Tracker\Task\Domain\Factory\EventFactory;
use Micro\Tracker\Task\Domain\Factory\EventFactoryInterface;
use Micro\Tracker\Task\Domain\Factory\ValueObjectFactory;
use Micro\Tracker\Task\Domain\Factory\ValueObjectFactoryInterface;
use Micro\Tracker\Task\Domain\ValueObject\AssigneeId;
use Micro\Tracker\Task\Domain\ValueObject\Description;
use Micro\Tracker\Task\Domain\ValueObject\Status;
use Micro\Tracker\Task\Domain\ValueObject\Task;
use Micro\Tracker\Task\Domain\ValueObject\Title;

/**
 * @class TaskEntity
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
class TaskEntity extends EventSourcedAggregateRoot implements TaskEntityInterface, Serializable
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
     * title value object.
     */
    protected ?Title $title = null;

    /**
     * description value object.
     */
    protected ?Description $description = null;

    /**
     * status value object.
     */
    protected ?Status $status = null;

    /**
     * assignee_id value object.
     */
    protected ?AssigneeId $assigneeId = null;

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
		Task $task,
		?EventFactoryInterface $eventFactory = null,
		?ValueObjectFactoryInterface $valueObjectFactory = null
	): self
    {
		$entity = new static($eventFactory, $valueObjectFactory);
		$entity->uuid = $uuid;
		$entity->taskCreate($processUuid, $task);

        return $entity;
    }

    /**
     * Factory method for creating a new TaskEntity.
     */
    public static function createActual(Uuid $uuid, Task $task, ?EventFactoryInterface $eventFactory = null, ?ValueObjectFactoryInterface $valueObjectFactory = null): self
    {
		$entity = new static($eventFactory, $valueObjectFactory);
		$entity->uuid = $uuid;
		$entity->assembleFromValueObject($task);

        return $entity;
    }

    /**
     * Execute task-create command.
     */
    public function taskCreate(ProcessUuid $processUuid, Task $task, ?Payload $payload = null): void
    {
		$this->apply($this->eventFactory->makeTaskCreatedEvent($processUuid, $this->uuid, $task, $payload));
    }

    /**
     * Apply TaskCreatedEvent event.
     */
    public function applyTaskCreatedEvent(TaskCreatedEvent $event): void
    {
		$this->processUuid = $event->getProcessUuid();
		$this->uuid = $event->getUuid();
		$this->assembleFromValueObject($event->getTask());
    }

    /**
     * Execute task-update-status command.
     */
    public function taskUpdateStatus(ProcessUuid $processUuid, Task $task, ?Payload $payload = null): void
    {
		$this->apply($this->eventFactory->makeTaskStatusUpdatedEvent($processUuid, $this->uuid, $task, $payload));
    }

    /**
     * Apply TaskStatusUpdatedEvent event.
     */
    public function applyTaskStatusUpdatedEvent(TaskStatusUpdatedEvent $event): void
    {
		$this->processUuid = $event->getProcessUuid();
		$this->assembleFromValueObject($event->getTask());
    }

    /**
     * Execute task-assign command.
     */
    public function taskAssign(ProcessUuid $processUuid, Task $task, ?Payload $payload = null): void
    {
		$this->apply($this->eventFactory->makeTaskAssignedEvent($processUuid, $this->uuid, $task, $payload));
    }

    /**
     * Apply TaskAssignedEvent event.
     */
    public function applyTaskAssignedEvent(TaskAssignedEvent $event): void
    {
		$this->processUuid = $event->getProcessUuid();
		$this->assembleFromValueObject($event->getTask());
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
     * Return title value object.
     */
    public function getTitle(): ?Title
    {
        return $this->title;
    }

    /**
     * Return description value object.
     */
    public function getDescription(): ?Description
    {
        return $this->description;
    }

    /**
     * Return status value object.
     */
    public function getStatus(): ?Status
    {
        return $this->status;
    }

    /**
     * Return assignee_id value object.
     */
    public function getAssigneeId(): ?AssigneeId
    {
        return $this->assigneeId;
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
     * Factory method for creating a new TaskEntity.
     */
    public static function deserialize(array $data): self
    {
		Assertion::keyExists($data, self::KEY_UUID);
		$task = Task::fromNative($data);

        return static::createActual(Uuid::fromNative($data[self::KEY_UUID]), $task);
    }

    /**
     * Assemble entity from value object.
     */
    public function assembleFromValueObject(ValueObjectInterface $valueObject): void
    {
		if (!$valueObject instanceof Task) {
			throw new ValueObjectInvalidException('TaskEntity can be assembled only with Task value object');
		}

		if (null !== $valueObject->getTitle()) {
			$this->title = $valueObject->getTitle();
		}

		if (null !== $valueObject->getDescription()) {
			$this->description = $valueObject->getDescription();
		}

		if (null !== $valueObject->getStatus()) {
			$this->status = $valueObject->getStatus();
		}

		if (null !== $valueObject->getAssigneeId()) {
			$this->assigneeId = $valueObject->getAssigneeId();
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
		$task = $this->normalize();

        return Task::fromNative($task);
    }

    /**
     * Convert entity object to array.
     */
    public function normalize(): array
    {
		$data = [];
		$data["process_uuid"] = $this->getProcessUuid()?->toNative();
		$data["uuid"] = $this->getUuid()?->toNative();
		$data["title"] = $this->getTitle()?->toNative();
		$data["description"] = $this->getDescription()?->toNative();
		$data["status"] = $this->getStatus()?->toNative();
		$data["assignee_id"] = $this->getAssigneeId()?->toNative();
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
