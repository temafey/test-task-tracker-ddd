<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Application\QueryHandler;

use MicroModule\Base\Application\QueryHandler\QueryHandlerInterface;
use MicroModule\Base\Domain\Query\QueryInterface;
use Micro\Tracker\Task\Domain\Query\FindByCriteriaUserQuery;
use Micro\Tracker\Task\Domain\Repository\Query\UserRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * @class FindByCriteriaUserHandler
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
	'command' => 'Micro\Tracker\Task\Domain\Query\FindByCriteriaUserQuery',
	'bus' => 'query.task'
])]
class FindByCriteriaUserHandler implements QueryHandlerInterface
{
    /**
     * Constructor
     */
    public function __construct(
		protected UserRepositoryInterface $userRepository
	)
    {
        
    }

    /**
     * Handle FindByCriteriaUserQuery query.
	 *
	 * @param QueryInterface|FindByCriteriaUserQuery $findByCriteriaUserQuery
     */
    public function handle(QueryInterface $findByCriteriaUserQuery): ?array
    {
        return $this->userRepository->findByCriteria($findByCriteriaUserQuery->getFindCriteria());
    }
}
