<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Application\QueryHandler;

use MicroModule\Base\Application\QueryHandler\QueryHandlerInterface;
use MicroModule\Base\Domain\Query\QueryInterface;
use Micro\Tracker\Task\Domain\Query\FindByCriteriaTaskQuery;
use Micro\Tracker\Task\Domain\Repository\Query\TaskRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * @class FindByCriteriaTaskHandler
 *
 * @package Micro\Tracker\Task\Application\QueryHandler
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */
#[AutoconfigureTag(name: 'tactician.handler', attributes: [
	'command' => 'Micro\Tracker\Task\Domain\Query\FindByCriteriaTaskQuery',
	'bus' => 'query.task'
])]
class FindByCriteriaTaskHandler implements QueryHandlerInterface
{
    /**
     * Constructor
     */
    public function __construct(
		protected TaskRepositoryInterface $taskRepository
	)
    {
        
    }

    /**
     * Handle FindByCriteriaTaskQuery query.
	 *
	 * @param QueryInterface|FindByCriteriaTaskQuery $findByCriteriaTaskQuery
     */
    public function handle(QueryInterface $findByCriteriaTaskQuery): ?array
    {
        return $this->taskRepository->findByCriteria($findByCriteriaTaskQuery->getFindCriteria());
    }
}
