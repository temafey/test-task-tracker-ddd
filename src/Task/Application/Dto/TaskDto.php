<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Application\Dto;

use Micro\Tracker\Task\Domain\Dto\TaskDtoInterface;

/**
 * @class TaskDto
 *
 * @package Micro\Tracker\Task\Application\Dto
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */

class TaskDto implements TaskDtoInterface
{
    /**
     * Constructor
     */
    public function __construct(
		public readonly ?string $processUuid,
		public readonly ?string $uuid,
		public readonly ?string $title,
		public readonly ?string $description,
		public readonly ?string $status,
		public readonly ?string $assigneeId
	)
    {
        
    }

    /**
     * Convert array to DTO object.
     */
    public static function denormalize(array $data): static
    {

		$processUuid = null;
		if (array_key_exists(static::PROCESS_UUID, $data)) {
			$processUuid = $data[static::PROCESS_UUID];
		}

		$uuid = null;
		if (array_key_exists(static::UUID, $data)) {
			$uuid = $data[static::UUID];
		}

		$title = null;
		if (array_key_exists(static::TITLE, $data)) {
			$title = $data[static::TITLE];
		}

		$description = null;
		if (array_key_exists(static::DESCRIPTION, $data)) {
			$description = $data[static::DESCRIPTION];
		}

		$status = null;
		if (array_key_exists(static::STATUS, $data)) {
			$status = $data[static::STATUS];
		}

		$assigneeId = null;
		if (array_key_exists(static::ASSIGNEE_ID, $data)) {
			$assigneeId = $data[static::ASSIGNEE_ID];
		}

        return new static(
			$processUuid, 
			$uuid, 
			$title, 
			$description, 
			$status, 
			$assigneeId
		);
    }

    /**
     * Convert dto object to array.
     */
    public function normalize(): array
    {
		$data = [];

		if (null !== $this->processUuid) {
			$data[static::PROCESS_UUID] = $this->processUuid;
		}

		if (null !== $this->uuid) {
			$data[static::UUID] = $this->uuid;
		}

		if (null !== $this->title) {
			$data[static::TITLE] = $this->title;
		}

		if (null !== $this->description) {
			$data[static::DESCRIPTION] = $this->description;
		}

		if (null !== $this->status) {
			$data[static::STATUS] = $this->status;
		}

		if (null !== $this->assigneeId) {
			$data[static::ASSIGNEE_ID] = $this->assigneeId;
		}

        return $data;
    }
}
