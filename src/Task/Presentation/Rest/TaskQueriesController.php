<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Presentation\Rest;

use League\Tactician\CommandBus;
use Micro\Tracker\Task\Application\Dto\TaskDto;
use Micro\Tracker\Task\Domain\Factory\QueryFactoryInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @class TaskQueriesController
 *
 * @package Micro\Tracker\Task\Presentation\Rest
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */

class TaskQueriesController extends AbstractController
{
    /**
     * Constructor
     */
    public function __construct(
		#[Autowire(service: 'tactician.commandbus.query.task')] protected commandBus $queryBus,
		protected QueryFactoryInterface $queryFactory
	)
    {
        
    }

    /**
     * Request of 'TaskDto' to process 'findByCriteriaTask' query
     */
     #[Route('/api/tasks', methods: ['GET'])]
     #[OA\Response(
         response: 200,
         description: 'Request of \'TaskDto\' to process \'findByCriteriaTask\' query',
         content: new OA\JsonContent(
             type: 'array',
             items: new OA\Items(ref: new Model(type: TaskDto::class, groups: ['full']))
         )
    )]
    #[OA\Tag(name: 'task-queries')]
    #[OA\Parameter(
        name: 'status',
        description: 'The field \'status\' of \'TaskDto\'',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'assigneeId',
        description: 'The field \'assigneeId\' of \'TaskDto\'',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    public function getAllAction(#[MapQueryString] TaskDto $taskDto): JsonResponse
    {
		$result = $this->queryBus->handle(
            $this->queryFactory->makeQueryInstanceByTypeFromDto(
                QueryFactoryInterface::FIND_BY_CRITERIA_TASK_QUERY,
                $taskDto
            )
        );

        return new JsonResponse($result);
    }

    /**
     * Request of 'TaskDto' to process 'fetchOneTask' query
     */
     #[Route('/api/tasks/{uuid}', methods: ['GET'])]
     #[OA\Response(
         response: 200,
         description: 'Request of \'TaskDto\' to process \'fetchOneTask\' query',
         content: new OA\JsonContent(
             type: 'array',
             items: new OA\Items(ref: new Model(type: TaskDto::class, groups: ['full']))
         )
    )]
    #[OA\Tag(name: 'task-queries')]
    public function getOneAction(string $uuid): JsonResponse
    {
        $taskDto = TaskDto::denormalize([
            'uuid' => $uuid,
        ]);
		$result = $this->queryBus->handle(
            $this->queryFactory->makeQueryInstanceByTypeFromDto(
                QueryFactoryInterface::FETCH_ONE_TASK_QUERY,
                $taskDto
            )
        );

        return new JsonResponse($result);
    }
}
