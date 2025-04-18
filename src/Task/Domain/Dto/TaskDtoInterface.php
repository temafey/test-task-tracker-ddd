<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Domain\Dto;

use MicroModule\Base\Domain\Dto\DtoInterface;

/**
 * @interface TaskDtoInterface
 *
 * @package Micro\Tracker\Task\Domain\Dto
 */
interface TaskDtoInterface extends DtoInterface
{
	public const PROCESS_UUID = "process_uuid"; 
	public const UUID = "uuid"; 
	public const TITLE = "title"; 
	public const DESCRIPTION = "description"; 
	public const STATUS = "status"; 
	public const ASSIGNEE_ID = "assignee_id";

}
