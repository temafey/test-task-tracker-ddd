<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Domain\ValueObject;

use MicroModule\ValueObject\Enum\Enum;

/**
 * @class Status
 *
 * @package Micro\Tracker\Task\Domain\ValueObject
 *
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */

class Status extends Enum
{
    public const string TODO = 'todo';
    public const string IN_PROGRESS = 'in_progress';
    public const string DONE = 'done';

}
