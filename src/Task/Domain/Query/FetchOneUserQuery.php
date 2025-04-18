<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Domain\Query;

use MicroModule\Base\Domain\Query\AbstractQuery;
use MicroModule\Base\Domain\ValueObject\ProcessUuid;
use MicroModule\Base\Domain\ValueObject\Uuid;

/**
 * @class FetchOneUserQuery
 *
 * @package Micro\Tracker\Task\Domain\Query
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */

class FetchOneUserQuery extends AbstractQuery
{
    /**
     * Constructor
     */
    public function __construct(
		ProcessUuid $processUuid, 
		protected Uuid $uuid)
    {
		parent::__construct($processUuid);
        
    }

    /**
     * Return Uuid value object.
     */
    public function getUuid(): Uuid
    {
        return $this->uuid;
    }
}
