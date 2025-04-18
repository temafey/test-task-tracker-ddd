<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Domain\Query;

use MicroModule\Base\Domain\Query\AbstractQuery;
use MicroModule\Base\Domain\ValueObject\FindCriteria;
use MicroModule\Base\Domain\ValueObject\ProcessUuid;

/**
 * @class FindByCriteriaTaskQuery
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

class FindByCriteriaTaskQuery extends AbstractQuery
{
    /**
     * Constructor
     */
    public function __construct(
		ProcessUuid $processUuid, 
		protected FindCriteria $findCriteria)
    {
		parent::__construct($processUuid);
        
    }

    /**
     * Return FindCriteria value object.
     */
    public function getFindCriteria(): FindCriteria
    {
        return $this->findCriteria;
    }
}
