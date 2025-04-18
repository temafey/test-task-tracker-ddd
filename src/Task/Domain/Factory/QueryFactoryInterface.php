<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Domain\Factory;

use MicroModule\Base\Domain\Factory\QueryFactoryInterface as BaseQueryFactoryInterface;
use MicroModule\Base\Domain\ValueObject\FindCriteria;
use Micro\Tracker\Task\Domain\Query\FetchOneTaskQuery;
use Micro\Tracker\Task\Domain\Query\FetchOneUserQuery;
use Micro\Tracker\Task\Domain\Query\FindByCriteriaTaskQuery;
use Micro\Tracker\Task\Domain\Query\FindByCriteriaUserQuery;
use Micro\Tracker\Task\Domain\Query\FindOneByTaskQuery;
use Micro\Tracker\Task\Domain\Query\FindOneByUserQuery;

/**
 * @interface QueryFactoryInterface
 *
 * @package Micro\Tracker\Task\Domain\Factory
 */
interface QueryFactoryInterface extends BaseQueryFactoryInterface
{
	public const FETCH_ONE_TASK_QUERY = "FetchOneTaskQuery"; 
	public const FIND_BY_CRITERIA_TASK_QUERY = "FindByCriteriaTaskQuery"; 
	public const FIND_ONE_BY_TASK_QUERY = "FindOneByTaskQuery"; 
	public const FETCH_ONE_USER_QUERY = "FetchOneUserQuery"; 
	public const FIND_BY_CRITERIA_USER_QUERY = "FindByCriteriaUserQuery"; 
	public const FIND_ONE_BY_USER_QUERY = "FindOneByUserQuery";

    /**
     * Create FetchOneTaskQuery Query.
     */
    public function makeFetchOneTaskQuery(string $processUuid, string $uuid): FetchOneTaskQuery;

    /**
     * Create FindByCriteriaTaskQuery Query.
     */
    public function makeFindByCriteriaTaskQuery(string $processUuid, array $findCriteria): FindByCriteriaTaskQuery;

    /**
     * Create FindOneByTaskQuery Query.
     */
    public function makeFindOneByTaskQuery(string $processUuid, array $findCriteria): FindOneByTaskQuery;

    /**
     * Create FetchOneUserQuery Query.
     */
    public function makeFetchOneUserQuery(string $processUuid, string $uuid): FetchOneUserQuery;

    /**
     * Create FindByCriteriaUserQuery Query.
     */
    public function makeFindByCriteriaUserQuery(string $processUuid, array $findCriteria): FindByCriteriaUserQuery;

    /**
     * Create FindOneByUserQuery Query.
     */
    public function makeFindOneByUserQuery(string $processUuid, array $findCriteria): FindOneByUserQuery;

}
