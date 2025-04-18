<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Domain\ValueObject;

use MicroModule\Base\Domain\Exception\ValueObjectInvalidNativeValueException;
use MicroModule\Base\Domain\ValueObject\BaseEntityValueObject;
use MicroModule\Base\Domain\ValueObject\CreatedAt;
use MicroModule\Base\Domain\ValueObject\UpdatedAt;
use Micro\Tracker\Task\Domain\ValueObject\Email;
use Micro\Tracker\Task\Domain\ValueObject\Name;

/**
 * @class User
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
class User extends BaseEntityValueObject
{
    /**
     * Fields, that should be compared.
     */
    public const COMPARED_FIELDS = [
		'name',
		'email',
	];

    /**
     * Return Name value object.
     */
    protected ?Name $name = null;

    /**
     * Return Email value object.
     */
    protected ?Email $email = null;

    /**
     * Return CreatedAt value object.
     */
    protected ?CreatedAt $createdAt = null;

    /**
     * Return UpdatedAt value object.
     */
    protected ?UpdatedAt $updatedAt = null;


    /**
     * Build User object from array.
     */
    public static function fromArray(array $data): static
    {
		$valueObject = new static();
		if (isset($data['name'])) {
			$valueObject->name = Name::fromNative($data['name']);
		}

		if (isset($data['email'])) {
			$valueObject->email = Email::fromNative($data['email']);
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
     * Build User object from array.
     */
    public function toArray(): array
    {
		$data = [];
		if (null !== $this->name) {
			$data['name'] = $this->name->toNative();
		}

		if (null !== $this->email) {
			$data['email'] = $this->email->toNative();
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
     * Return Name value object.
     */
    public function getName(): ?Name
    {
        return $this->name;
    }

    /**
     * Return Email value object.
     */
    public function getEmail(): ?Email
    {
        return $this->email;
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
