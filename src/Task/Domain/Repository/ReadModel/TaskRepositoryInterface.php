<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Domain\Repository\ReadModel;

use MicroModule\Base\Domain\ValueObject\Uuid;
use Micro\Tracker\Task\Domain\ReadModel\TaskReadModelInterface;

/**
 * @interface TaskRepositoryInterface
 *
 * @package Micro\Tracker\Task\Domain\Repository\ReadModel
 */
interface TaskRepositoryInterface
{
    /**
     * 
     */
    public function add(TaskReadModelInterface $taskReadModel): void;

    /**
     * 
     */
    public function update(TaskReadModelInterface $taskReadModel): void;

    /**
     * 
     */
    public function get(Uuid $uuid): ?TaskReadModelInterface;
}
