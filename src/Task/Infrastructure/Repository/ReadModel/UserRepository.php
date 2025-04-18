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
use Micro\Tracker\Task\Domain\ReadModel\UserReadModelInterface;
use Micro\Tracker\Task\Domain\Repository\ReadModel\UserRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * @class UserRepository
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

class UserRepository implements UserRepositoryInterface
{
    /**
     * Constructor
     */
    public function __construct(
		#[Autowire(service: 'user.infrastructure.repository.storage.read_model.dbal')]
		protected ReadModelStoreInterface $readModelStore,
		protected ReadModelFactoryInterface $readModelFactory,
		protected ValueObjectFactoryInterface $valueObjectFactory
	)
    {
        
    }

    /**
     * Add UserReadModel ReadModel in Storage.
     */
    public function add(UserReadModelInterface $userReadModel): void
    {
        try {
            $this->readModelStore->insertOne($userReadModel);
        } catch (DBALEventStoreException $e) {
            throw new ReadModelException(
                'UserReadModelInterface $userReadModel was not add in read model.',
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Update UserReadModel ReadModel in Storage.
     */
    public function update(UserReadModelInterface $userReadModel): void
    {
        try {
            $this->readModelStore->updateOne($userReadModel);
        } catch (DBALEventStoreException $e) {
            throw new ReadModelException(
                'UserReadModelInterface $userReadModel was not update in read model.',
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Find and return User Read Model by Uuid
     *
     * @throws Exception
     */
    public function get(Uuid $uuid): ?UserReadModelInterface
    {
        try {
            $result = $this->readModelStore->findOne($uuid->toString());
        } catch (NotFoundException) {
            return null;
        }
        $uuid = $result[UserReadModelInterface::KEY_UUID];
        unset($result[UserReadModelInterface::KEY_UUID]);

        return $this->readModelFactory->makeUserActualInstance(
            $this->valueObjectFactory->makeUser($result),
            $this->valueObjectFactory->makeUuid($uuid)
        );
    }
}
