<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Domain\Repository\Query;

use MicroModule\Base\Domain\ValueObject\FindCriteria;
use MicroModule\Base\Domain\ValueObject\Uuid;
use Micro\Tracker\Task\Domain\ReadModel\UserReadModelInterface;

/**
 * @interface UserRepositoryInterface
 *
 * @package Micro\Tracker\Task\Domain\Repository\Query
 */
interface UserRepositoryInterface
{
    /**
     * 
     */
    public function fetchOne(Uuid $uuid): ?UserReadModelInterface;

    /**
     * 
     */
    public function findByCriteria(FindCriteria $findCriteria): ?array;

    /**
     * 
     */
    public function findOneBy(FindCriteria $findCriteria): ?UserReadModelInterface;
}
