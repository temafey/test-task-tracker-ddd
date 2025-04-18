<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Domain\Factory;

use MicroModule\Base\Domain\Dto\DtoInterface;
use MicroModule\Base\Domain\Exception\FactoryException;
use MicroModule\Base\Domain\Query\QueryInterface as BaseQueryInterface;
use MicroModule\Base\Domain\ValueObject\FindCriteria;
use MicroModule\Base\Domain\ValueObject\ProcessUuid;
use MicroModule\Base\Domain\ValueObject\Uuid;
use Micro\Tracker\Task\Domain\Query\FetchOneTaskQuery;
use Micro\Tracker\Task\Domain\Query\FetchOneUserQuery;
use Micro\Tracker\Task\Domain\Query\FindByCriteriaTaskQuery;
use Micro\Tracker\Task\Domain\Query\FindByCriteriaUserQuery;
use Micro\Tracker\Task\Domain\Query\FindOneByTaskQuery;
use Micro\Tracker\Task\Domain\Query\FindOneByUserQuery;

/**
 * @class QueryFactory
 *
 * @package Micro\Tracker\Task\Domain\Factory
 *
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class QueryFactory implements QueryFactoryInterface
{
    protected const ALLOWED_QUERIES = [
        self::FETCH_ONE_TASK_QUERY, 
		self::FIND_BY_CRITERIA_TASK_QUERY, 
		self::FIND_ONE_BY_TASK_QUERY, 
		self::FETCH_ONE_USER_QUERY, 
		self::FIND_BY_CRITERIA_USER_QUERY, 
		self::FIND_ONE_BY_USER_QUERY,
    ];

    public function isQueryAllowed(string $queryType): bool
    {
        return in_array($queryType, static::ALLOWED_QUERIES);
    }

    /**
     * Make query by query constant.
     *
     * @throws FactoryException
     * @throws Exception
     */
    public function makeQueryInstanceByType(...$args): BaseQueryInterface
    {
        $type = (string)array_shift($args);

        return match ($type) {
            self::FETCH_ONE_TASK_QUERY => $this->makeFetchOneTaskQuery(...$args), 
			self::FIND_BY_CRITERIA_TASK_QUERY => $this->makeFindByCriteriaTaskQuery(...$args), 
			self::FIND_ONE_BY_TASK_QUERY => $this->makeFindOneByTaskQuery(...$args), 
			self::FETCH_ONE_USER_QUERY => $this->makeFetchOneUserQuery(...$args), 
			self::FIND_BY_CRITERIA_USER_QUERY => $this->makeFindByCriteriaUserQuery(...$args), 
			self::FIND_ONE_BY_USER_QUERY => $this->makeFindOneByUserQuery(...$args),
            default => throw new FactoryException(sprintf('Query for type `%s` not found!', $type)),
        };
    }

    /**
     * Make query from DTO.
     *
     * @throws FactoryException
     * @throws Exception
     */
    public function makeQueryInstanceByTypeFromDto(string $queryType, DtoInterface $dto): BaseQueryInterface
    {
        $data = $dto->normalize();
        $args = [];

        if (isset($data[DtoInterface::KEY_PROCESS_UUID])) {
            $args[] = $data[DtoInterface::KEY_PROCESS_UUID];
            unset($data[DtoInterface::KEY_PROCESS_UUID]);
        } else {
            $args[] = ProcessUuid::fromNative(null)->toNative();
        }
        if (isset($data[DtoInterface::KEY_UUID])) {
            $args[] = $data[DtoInterface::KEY_UUID];
            unset($data[DtoInterface::KEY_UUID]);
        }
        if (!empty($data)) {
            $args[] = $data;
        }

        return $this->makeQueryInstanceByType($queryType, ...$args);
    }

    /**
     * Create FetchOneTaskQuery Query.
     */
    public function makeFetchOneTaskQuery(string $processUuid, string $uuid): FetchOneTaskQuery
    {
        return new FetchOneTaskQuery(
			ProcessUuid::fromNative($processUuid), 
			Uuid::fromNative($uuid)
		);
    }

    /**
     * Create FindByCriteriaTaskQuery Query.
     */
    public function makeFindByCriteriaTaskQuery(string $processUuid, array $findCriteria = []): FindByCriteriaTaskQuery
    {
        return new FindByCriteriaTaskQuery(
			ProcessUuid::fromNative($processUuid), 
			FindCriteria::fromNative($findCriteria)
		);
    }

    /**
     * Create FindOneByTaskQuery Query.
     */
    public function makeFindOneByTaskQuery(string $processUuid, array $findCriteria): FindOneByTaskQuery
    {
        return new FindOneByTaskQuery(
			ProcessUuid::fromNative($processUuid), 
			FindCriteria::fromNative($findCriteria)
		);
    }

    /**
     * Create FetchOneUserQuery Query.
     */
    public function makeFetchOneUserQuery(string $processUuid, string $uuid): FetchOneUserQuery
    {
        return new FetchOneUserQuery(
			ProcessUuid::fromNative($processUuid), 
			Uuid::fromNative($uuid)
		);
    }

    /**
     * Create FindByCriteriaUserQuery Query.
     */
    public function makeFindByCriteriaUserQuery(string $processUuid, array $findCriteria = []): FindByCriteriaUserQuery
    {
        return new FindByCriteriaUserQuery(
			ProcessUuid::fromNative($processUuid), 
			FindCriteria::fromNative($findCriteria)
		);
    }

    /**
     * Create FindOneByUserQuery Query.
     */
    public function makeFindOneByUserQuery(string $processUuid, array $findCriteria): FindOneByUserQuery
    {
        return new FindOneByUserQuery(
			ProcessUuid::fromNative($processUuid), 
			FindCriteria::fromNative($findCriteria)
		);
    }

}
