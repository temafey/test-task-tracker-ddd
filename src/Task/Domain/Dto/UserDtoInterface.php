<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Domain\Dto;

use MicroModule\Base\Domain\Dto\DtoInterface;

/**
 * @interface UserDtoInterface
 *
 * @package Micro\Tracker\Task\Domain\Dto
 */
interface UserDtoInterface extends DtoInterface
{
	public const PROCESS_UUID = "process_uuid"; 
	public const UUID = "uuid"; 
	public const NAME = "name"; 
	public const EMAIL = "email";
}
