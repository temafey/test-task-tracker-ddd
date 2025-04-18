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
use Micro\Tracker\Task\Domain\Entity\TaskEntityInterface;
use Micro\Tracker\Task\Domain\ValueObject\Task;

/**
 * @class TaskReadModel
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
 #[Table(name: 'tasks')]
class TaskReadModel implements TaskReadModelInterface
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
		name : 'title',
		type : 'string',
		nullable : true
	)]
    protected ?string $title = null;

    #[Column(
		name : 'description',
		type : 'string',
		nullable : true
	)]
    protected ?string $description = null;

    #[Column(
		name : 'status',
		type : 'string',
		nullable : true
	)]
    protected ?string $status = null;

    #[Column(
		name : 'assignee_id',
		type : 'string',
		nullable : true
	)]
    protected ?string $assigneeId = null;

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
     * Return title value.
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Return description value.
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Return status value.
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * Return assignee_id value.
     */
    public function getAssigneeId(): ?string
    {
        return $this->assigneeId;
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
		if (!$valueObject instanceof Task) {
			throw new ValueObjectInvalidException('TaskEntity can be assembled only with Task value object');
		}
		$this->uuid = $uuid;
		$this->title = $valueObject->getTitle()?->toNative();
		$this->description = $valueObject->getDescription()?->toNative();
		$this->status = $valueObject->getStatus()?->toNative();
		$this->assigneeId = $valueObject->getAssigneeId()?->toNative();
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
		$data["title"] = $this->title;
		$data["description"] = $this->description;
		$data["status"] = $this->status;
		$data["assignee_id"] = $this->assigneeId;
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
     * Create TaskReadModel by TaskEntity
     *
     * @throws Exception
     */
    public static function createByEntity(TaskEntityInterface $entity): TaskReadModelInterface
    {
        $readModel = new static();
        $readModel->assembleFromValueObject($entity->assembleToValueObject(), $entity->getUuid());

        return $readModel;
    }

    /**
     * Create TaskReadModel by Task value object.
     *
     * @throws ValueObjectInvalidException
     */
    public static function createByValueObject(Task $entityValueObject, Uuid $uuid): TaskReadModelInterface
    {
        $readModel = new static();
        $readModel->assembleFromValueObject($entityValueObject, $uuid);

        return $readModel;
    }

    /**
     * Update TaskReadModel by Task value object.
     *
     * @throws ValueObjectInvalidException
     */
    public function updateByValueObject(Task $entityValueObject, Uuid $uuid): void
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
