<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Infrastructure\Repository\Query;

use MicroModule\Base\Domain\Repository\ReadModelStoreInterface;
use MicroModule\Base\Domain\ValueObject\FindCriteria;
use MicroModule\Base\Domain\ValueObject\Uuid;
use MicroModule\Base\Infrastructure\Repository\Exception\NotFoundException;
use Micro\Tracker\Task\Domain\Factory\ReadModelFactoryInterface;
use Micro\Tracker\Task\Domain\Factory\ValueObjectFactoryInterface;
use Micro\Tracker\Task\Domain\ReadModel\UserReadModelInterface;
use Micro\Tracker\Task\Domain\Repository\Query\UserRepositoryInterface as QueryRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * @class UserRepository
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

class UserRepository implements QueryRepositoryInterface
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
     * Find and return User Read Model by Uuid
     *
     * @throws Exception
     */
    public function fetchOne(Uuid $uuid): ?UserReadModelInterface
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

    /**
     * Find and return array of User Read Models by FindCriteria
     *
	 * @return UserReadModelInterface[]|null
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
            $uuid = $data[UserReadModelInterface::KEY_UUID];
            unset($data[UserReadModelInterface::KEY_UUID]);
            $collection[] = $this->readModelFactory->makeUserActualInstance(
                $this->valueObjectFactory->makeUser($data),
                $this->valueObjectFactory->makeUuid($uuid)
            );
        }

        return $collection;
    }

    /**
     * Find and return User Read Model by Uuid
     *
     * @throws Exception
     */
    public function findOneBy(FindCriteria $findCriteria): ?UserReadModelInterface
    {
        try {
            $result = $this->readModelStore->findOneBy($findCriteria->toNative());
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
