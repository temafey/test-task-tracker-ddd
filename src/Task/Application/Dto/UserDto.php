<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Application\Dto;

use Micro\Tracker\Task\Domain\Dto\UserDtoInterface;

/**
 * @class UserDto
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

class UserDto implements UserDtoInterface
{
    /**
     * Constructor
     */
    public function __construct(
		public readonly ?string $processUuid,
		public readonly ?string $uuid,
		public readonly ?string $name,
		public readonly ?string $email
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

		$name = null;
		if (array_key_exists(static::NAME, $data)) {
			$name = $data[static::NAME];
		}

		$email = null;
		if (array_key_exists(static::EMAIL, $data)) {
			$email = $data[static::EMAIL];
		}

        return new static(
			$processUuid, 
			$uuid, 
			$name, 
			$email
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

		if (null !== $this->name) {
			$data[static::NAME] = $this->name;
		}

		if (null !== $this->email) {
			$data[static::EMAIL] = $this->email;
		}

        return $data;
    }
}
