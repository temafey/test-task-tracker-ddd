<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Application\QueryHandler;

use MicroModule\Base\Application\QueryHandler\QueryHandlerInterface;
use MicroModule\Base\Domain\Query\QueryInterface;
use Micro\Tracker\Task\Domain\Query\FindOneByUserQuery;
use Micro\Tracker\Task\Domain\ReadModel\UserReadModelInterface;
use Micro\Tracker\Task\Domain\Repository\Query\UserRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * @class FindOneByUserHandler
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
	'command' => 'Micro\Tracker\Task\Domain\Query\FindOneByUserQuery',
	'bus' => 'query.task'
])]
class FindOneByUserHandler implements QueryHandlerInterface
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
     * Handle FindOneByUserQuery query.
	 *
	 * @param QueryInterface|FindOneByUserQuery $findOneByUserQuery
     */
    public function handle(QueryInterface $findOneByUserQuery): ?UserReadModelInterface
    {
        return $this->userRepository->findOneBy($findOneByUserQuery->getFindCriteria());
    }
}
