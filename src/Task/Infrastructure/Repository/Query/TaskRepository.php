<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Infrastructure\Repository\Query;

use MicroModule\Base\Domain\Repository\ReadModelStoreInterface;
use MicroModule\Base\Domain\ValueObject\FindCriteria;
use MicroModule\Base\Domain\ValueObject\Uuid;
use MicroModule\Base\Infrastructure\Repository\Exception\NotFoundException;
use Micro\Tracker\Task\Domain\Factory\ReadModelFactoryInterface;
use Micro\Tracker\Task\Domain\Factory\ValueObjectFactoryInterface;
use Micro\Tracker\Task\Domain\ReadModel\TaskReadModelInterface;
use Micro\Tracker\Task\Domain\Repository\Query\TaskRepositoryInterface as QueryRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * @class TaskRepository
 *
 * @package Micro\Tracker\Task\Infrastructure\Repository\Query
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */

class TaskRepository implements QueryRepositoryInterface
{
    /**
     * Constructor
     */
    public function __construct(
		#[Autowire(service: 'tracker.infrastructure.repository.storage.read_model.dbal')]
		protected ReadModelStoreInterface $readModelStore,
		protected ReadModelFactoryInterface $readModelFactory,
		protected ValueObjectFactoryInterface $valueObjectFactory
	)
    {
        
    }

    /**
     * Find and return Task Read Model by Uuid
     *
     * @throws Exception
     */
    public function fetchOne(Uuid $uuid): ?TaskReadModelInterface
    {
        try {
            $result = $this->readModelStore->findOne($uuid->toString());
        } catch (NotFoundException) {
            return null;
        }
        $uuid = $result[TaskReadModelInterface::KEY_UUID];
        unset($result[TaskReadModelInterface::KEY_UUID]);

        return $this->readModelFactory->makeTaskActualInstance(
            $this->valueObjectFactory->makeTask($result),
            $this->valueObjectFactory->makeUuid($uuid)
        );
    }

    /**
     * Find and return array of Task Read Models by FindCriteria
     *
	 * @return TaskReadModelInterface[]|null
     * @throws Exception
     */
    public function findByCriteria(FindCriteria $findCriteria): ?array
    {
        try {
            $result = $this->readModelStore->findBy($findCriteria->toNative());
        } catch (NotFoundException) {
            return null;
        }
        $collection = [];

        foreach ($result as $data) {
            $uuid = $data[TaskReadModelInterface::KEY_UUID];
            unset($data[TaskReadModelInterface::KEY_UUID]);
            $collection[] = $this->readModelFactory->makeTaskActualInstance(
                $this->valueObjectFactory->makeTask($data),
                $this->valueObjectFactory->makeUuid($uuid)
            );
        }

        return $collection;
    }

    /**
     * Find and return Task Read Model by Uuid
     *
     * @throws Exception
     */
    public function findOneBy(FindCriteria $findCriteria): ?TaskReadModelInterface
    {
        try {
            $result = $this->readModelStore->findOneBy($findCriteria->toNative());
        } catch (NotFoundException) {
            return null;
        }
        $uuid = $result[TaskReadModelInterface::KEY_UUID];
        unset($result[TaskReadModelInterface::KEY_UUID]);

        return $this->readModelFactory->makeTaskActualInstance(
            $this->valueObjectFactory->makeTask($result),
            $this->valueObjectFactory->makeUuid($uuid)
        );
    }
}
