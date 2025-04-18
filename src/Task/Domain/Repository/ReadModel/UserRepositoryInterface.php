<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Domain\Repository\ReadModel;

use MicroModule\Base\Domain\ValueObject\Uuid;
use Micro\Tracker\Task\Domain\ReadModel\UserReadModelInterface;

/**
 * @interface UserRepositoryInterface
 *
 * @package Micro\Tracker\Task\Domain\Repository\ReadModel
 */
interface UserRepositoryInterface
{
    /**
     * 
     */
    public function add(UserReadModelInterface $userReadModel): void;

    /**
     * 
     */
    public function update(UserReadModelInterface $userReadModel): void;

    /**
     * 
     */
    public function get(Uuid $uuid): ?UserReadModelInterface;
}
