<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Domain\Repository\Query;

use MicroModule\Base\Domain\ValueObject\FindCriteria;
use MicroModule\Base\Domain\ValueObject\Uuid;
use Micro\Tracker\Task\Domain\ReadModel\TaskReadModelInterface;

/**
 * @interface TaskRepositoryInterface
 *
 * @package Micro\Tracker\Task\Domain\Repository\Query
 */
interface TaskRepositoryInterface
{
    /**
     * 
     */
    public function fetchOne(Uuid $uuid): ?TaskReadModelInterface;

    /**
     * 
     */
    public function findByCriteria(FindCriteria $findCriteria): ?array;

    /**
     * 
     */
    public function findOneBy(FindCriteria $findCriteria): ?TaskReadModelInterface;
}
