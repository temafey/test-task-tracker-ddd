<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Application\QueryHandler;

use MicroModule\Base\Application\QueryHandler\QueryHandlerInterface;
use MicroModule\Base\Domain\Query\QueryInterface;
use Micro\Tracker\Task\Domain\Query\FetchOneTaskQuery;
use Micro\Tracker\Task\Domain\ReadModel\TaskReadModelInterface;
use Micro\Tracker\Task\Domain\Repository\Query\TaskRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * @class FetchOneTaskHandler
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
	'command' => 'Micro\Tracker\Task\Domain\Query\FetchOneTaskQuery',
	'bus' => 'query.task'
])]
class FetchOneTaskHandler implements QueryHandlerInterface
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
     * Handle FetchOneTaskQuery query.
	 *
	 * @param QueryInterface|FetchOneTaskQuery $fetchOneTaskQuery
     */
    public function handle(QueryInterface $fetchOneTaskQuery): ?TaskReadModelInterface
    {
        return $this->taskRepository->fetchOne($fetchOneTaskQuery->getUuid());
    }
}
