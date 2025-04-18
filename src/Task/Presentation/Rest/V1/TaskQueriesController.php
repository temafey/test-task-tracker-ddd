<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Presentation\Rest\V1;

use League\Tactician\CommandBus;
use Micro\Tracker\Task\Application\Dto\TaskDto;
use Micro\Tracker\Task\Domain\Factory\QueryFactoryInterface;
use Micro\Tracker\Task\Infrastructure\Api\ApiVersion;
use Micro\Tracker\Task\Presentation\Rest\AbstractApiController;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Task Queries Controller (API V1)
 *
 * Handles task retrieval operations
 */
#[ApiVersion('v1')]
#[Route('/api/v1/tasks')]
class TaskQueriesController extends AbstractApiController
{
    /**
     * @param CommandBus $queryBus Query bus for dispatching queries
     * @param QueryFactoryInterface $queryFactory Factory for creating queries
     */
    public function __construct(
        private readonly CommandBus $queryBus,
        private readonly QueryFactoryInterface $queryFactory
    ) {
    }

    /**
     * Get all tasks
     *
     * Retrieves all tasks with optional filtering by status and assignee
     */
    #[Route('', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'List of tasks',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: TaskDto::class))
                ),
                new OA\Property(property: 'status', type: 'string', example: 'success')
            ]
        )
    )]
    #[OA\Parameter(
        name: 'status',
        description: 'Filter by task status',
        in: 'query',
        schema: new OA\Schema(type: 'string', enum: ['todo', 'in_progress', 'done'])
    )]
    #[OA\Parameter(
        name: 'assigneeId',
        description: 'Filter by assignee UUID',
        in: 'query',
        schema: new OA\Schema(type: 'string', format: 'uuid')
    )]
    #[OA\Tag(name: 'task-queries')]
    public function getAllAction(#[MapQueryString] TaskDto $taskDto): JsonResponse
    {
        $result = $this->queryBus->handle(
            $this->queryFactory->makeQueryInstanceByTypeFromDto(
                QueryFactoryInterface::FIND_BY_CRITERIA_TASK_QUERY,
                $taskDto
            )
        );

        return $this->createApiResponse($result);
    }

    /**
     * Get task by ID
     *
     * Retrieves a specific task by its UUID
     */
    #[Route('/{uuid}', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Task details',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'data',
                    ref: new Model(type: TaskDto::class)
                ),
                new OA\Property(property: 'status', type: 'string', example: 'success')
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Task not found'
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

        if ($result === null) {
            return $this->createApiErrorResponse('Task not found', 404);
        }

        return $this->createApiResponse($result);
    }
}
