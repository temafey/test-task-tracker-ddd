<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Application\QueryHandler;

use MicroModule\Base\Application\QueryHandler\QueryHandlerInterface;
use MicroModule\Base\Domain\Query\QueryInterface;
use Micro\Tracker\Task\Domain\Query\FindOneByTaskQuery;
use Micro\Tracker\Task\Domain\ReadModel\TaskReadModelInterface;
use Micro\Tracker\Task\Domain\Repository\Query\TaskRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * @class FindOneByTaskHandler
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
	'command' => 'Micro\Tracker\Task\Domain\Query\FindOneByTaskQuery',
	'bus' => 'query.task'
])]
class FindOneByTaskHandler implements QueryHandlerInterface
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
     * Handle FindOneByTaskQuery query.
	 *
	 * @param QueryInterface|FindOneByTaskQuery $findOneByTaskQuery
     */
    public function handle(QueryInterface $findOneByTaskQuery): ?TaskReadModelInterface
    {
        return $this->taskRepository->findOneBy($findOneByTaskQuery->getFindCriteria());
    }
}
