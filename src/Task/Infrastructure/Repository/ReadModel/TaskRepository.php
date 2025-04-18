<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Infrastructure\Repository\ReadModel;

use MicroModule\Base\Domain\Exception\ReadModelException;
use MicroModule\Base\Domain\Repository\ReadModelStoreInterface;
use MicroModule\Base\Domain\ValueObject\Uuid;
use MicroModule\Base\Infrastructure\Repository\Exception\DBALEventStoreException;
use MicroModule\Base\Infrastructure\Repository\Exception\NotFoundException;
use Micro\Tracker\Task\Domain\Factory\ReadModelFactoryInterface;
use Micro\Tracker\Task\Domain\Factory\ValueObjectFactoryInterface;
use Micro\Tracker\Task\Domain\ReadModel\TaskReadModelInterface;
use Micro\Tracker\Task\Domain\Repository\ReadModel\TaskRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * @class TaskRepository
 *
 * @package Micro\Tracker\Task\Infrastructure\Repository\ReadModel
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */

class TaskRepository implements TaskRepositoryInterface
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
     * Add TaskReadModel ReadModel in Storage.
     */
    public function add(TaskReadModelInterface $taskReadModel): void
    {
        try {
            $this->readModelStore->insertOne($taskReadModel);
        } catch (DBALEventStoreException $e) {
            throw new ReadModelException(
                'TaskReadModelInterface $taskReadModel was not add in read model.',
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Update TaskReadModel ReadModel in Storage.
     */
    public function update(TaskReadModelInterface $taskReadModel): void
    {
        try {
            $this->readModelStore->updateOne($taskReadModel);
        } catch (DBALEventStoreException $e) {
            throw new ReadModelException(
                'TaskReadModelInterface $taskReadModel was not update in read model.',
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Find and return Task Read Model by Uuid
     *
     * @throws Exception
     */
    public function get(Uuid $uuid): ?TaskReadModelInterface
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
}
