<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Domain\ValueObject;

use MicroModule\Base\Domain\Exception\ValueObjectInvalidNativeValueException;
use MicroModule\Base\Domain\ValueObject\BaseEntityValueObject;
use MicroModule\Base\Domain\ValueObject\CreatedAt;
use MicroModule\Base\Domain\ValueObject\UpdatedAt;
use Micro\Tracker\Task\Domain\ValueObject\AssigneeId;
use Micro\Tracker\Task\Domain\ValueObject\Description;
use Micro\Tracker\Task\Domain\ValueObject\Status;
use Micro\Tracker\Task\Domain\ValueObject\Title;

/**
 * @class Task
 *
 * @package Micro\Tracker\Task\Domain\ValueObject
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
class Task extends BaseEntityValueObject
{
    /**
     * Fields, that should be compared.
     */
    public const COMPARED_FIELDS = [
		'title',
		'description',
		'status',
		'assignee_id',
	];

    /**
     * Return Title value object.
     */
    protected ?Title $title = null;

    /**
     * Return Description value object.
     */
    protected ?Description $description = null;

    /**
     * Return Status value object.
     */
    protected ?Status $status = null;

    /**
     * Return AssigneeId value object.
     */
    protected ?AssigneeId $assigneeId = null;

    /**
     * Return CreatedAt value object.
     */
    protected ?CreatedAt $createdAt = null;

    /**
     * Return UpdatedAt value object.
     */
    protected ?UpdatedAt $updatedAt = null;


    /**
     * Build Task object from array.
     */
    public static function fromArray(array $data): static
    {
		$valueObject = new static();
		if (isset($data['title'])) {
			$valueObject->title = Title::fromNative($data['title']);
		}

		if (isset($data['description'])) {
			$valueObject->description = Description::fromNative($data['description']);
		}

		if (isset($data['status'])) {
			$valueObject->status = Status::fromNative($data['status']);
		}

		if (isset($data['assignee_id'])) {
			$valueObject->assigneeId = AssigneeId::fromNative($data['assignee_id']);
		}

		if (isset($data['created_at'])) {
			$valueObject->createdAt = CreatedAt::fromNative($data['created_at']);
		}

		if (isset($data['updated_at'])) {
			$valueObject->updatedAt = UpdatedAt::fromNative($data['updated_at']);
		}

        return $valueObject;
    }

    /**
     * Build Task object from array.
     */
    public function toArray(): array
    {
		$data = [];
		if (null !== $this->title) {
			$data['title'] = $this->title->toNative();
		}

		if (null !== $this->description) {
			$data['description'] = $this->description->toNative();
		}

		if (null !== $this->status) {
			$data['status'] = $this->status->toNative();
		}

		if (null !== $this->assigneeId) {
			$data['assignee_id'] = $this->assigneeId->toNative();
		}

		if (null !== $this->createdAt) {
			$data['created_at'] = $this->createdAt->toNative();
		}

		if (null !== $this->updatedAt) {
			$data['updated_at'] = $this->updatedAt->toNative();
		}

        return $this->enrich($data);
    }

    /**
     * Return Title value object.
     */
    public function getTitle(): ?Title
    {
        return $this->title;
    }

    /**
     * Return Description value object.
     */
    public function getDescription(): ?Description
    {
        return $this->description;
    }

    /**
     * Return Status value object.
     */
    public function getStatus(): ?Status
    {
        return $this->status;
    }

    /**
     * Return AssigneeId value object.
     */
    public function getAssigneeId(): ?AssigneeId
    {
        return $this->assigneeId;
    }

    /**
     * Return CreatedAt value object.
     */
    public function getCreatedAt(): ?CreatedAt
    {
        return $this->createdAt;
    }

    /**
     * Return UpdatedAt value object.
     */
    public function getUpdatedAt(): ?UpdatedAt
    {
        return $this->updatedAt;
    }

}
