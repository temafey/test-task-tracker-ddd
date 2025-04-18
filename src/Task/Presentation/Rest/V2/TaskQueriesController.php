<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Presentation\Rest\V2;

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
 * Task Queries Controller (API V2)
 *
 * Handles task retrieval with enhanced filtering and response format
 */
#[ApiVersion('v2')]
#[Route('/tasks')]
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
     * Get all tasks (V2)
     *
     * Retrieves tasks with enhanced filtering options and pagination
     */
    #[Route('', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'List of tasks',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: "data", type: "object", properties: [
                    new OA\Property(property: "items", type: "array", items: new OA\Items(
                        type: "object",
                        properties: [
                            new OA\Property(property: "uuid", type: "string"),
                            new OA\Property(property: "title", type: "string"),
                            new OA\Property(property: "description", type: "string"),
                            new OA\Property(property: "status", type: "string"),
                            new OA\Property(property: "assigneeId", type: "string", nullable: true),
                            new OA\Property(property: "priority", type: "string"),
                            new OA\Property(property: "dueDate", type: "string", format: "date-time", nullable: true),
                            new OA\Property(property: "createdAt", type: "string", format: "date-time"),
                            new OA\Property(property: "updatedAt", type: "string", format: "date-time")
                        ]
                    )),
                    new OA\Property(property: "pagination", type: "object", properties: [
                        new OA\Property(property: "total", type: "integer"),
                        new OA\Property(property: "page", type: "integer"),
                        new OA\Property(property: "perPage", type: "integer"),
                        new OA\Property(property: "pages", type: "integer")
                    ]),
                    new OA\Property(property: "_links", type: "object", properties: [
                        new OA\Property(property: "self", type: "string"),
                        new OA\Property(property: "first", type: "string"),
                        new OA\Property(property: "prev", type: "string", nullable: true),
                        new OA\Property(property: "next", type: "string", nullable: true),
                        new OA\Property(property: "last", type: "string")
                    ])
                ]),
                new OA\Property(property: "status", type: "string", example: "success")
            ]
        )
    )]
    #[OA\Parameter(name: 'status', description: 'Filter by status', in: 'query', schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'assigneeId', description: 'Filter by assignee', in: 'query', schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'priority', description: 'Filter by priority', in: 'query', schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'dueBefore', description: 'Due date before', in: 'query', schema: new OA\Schema(type: 'string', format: 'date-time'))]
    #[OA\Parameter(name: 'dueAfter', description: 'Due date after', in: 'query', schema: new OA\Schema(type: 'string', format: 'date-time'))]
    #[OA\Parameter(name: 'search', description: 'Search in title and description', in: 'query', schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'page', description: 'Page number', in: 'query', schema: new OA\Schema(type: 'integer', default: 1))]
    #[OA\Parameter(name: 'perPage', description: 'Items per page', in: 'query', schema: new OA\Schema(type: 'integer', default: 10))]
    #[OA\Parameter(name: 'sort', description: 'Sort field', in: 'query', schema: new OA\Schema(type: 'string', enum: ['title', 'status', 'priority', 'dueDate', 'createdAt', 'updatedAt']))]
    #[OA\Parameter(name: 'order', description: 'Sort order', in: 'query', schema: new OA\Schema(type: 'string', enum: ['asc', 'desc']))]
    #[OA\Tag(name: 'task-queries')]
    public function getAllAction(
        Request $request,
        #[MapQueryString] TaskDto $taskDto
    ): JsonResponse {
        // Add pagination parameters to the DTO
        $pagination = $this->getPaginationParams($request);
        $sort = $request->query->get('sort', 'createdAt');
        $order = $request->query->get('order', 'desc');

        // Add advanced filtering
        $search = $request->query->get('search');
        $dueBefore = $request->query->get('dueBefore');
        $dueAfter = $request->query->get('dueAfter');
        $priority = $request->query->get('priority');

        // Create enhanced query with all parameters
        $taskDtoArray = $taskDto->normalize();
        $taskDtoArray = array_merge($taskDtoArray, $pagination);
        $taskDtoArray['sort'] = $sort;
        $taskDtoArray['order'] = $order;

        if ($search) {
            $taskDtoArray['search'] = $search;
        }

        if ($dueBefore) {
            $taskDtoArray['dueBefore'] = $dueBefore;
        }

        if ($dueAfter) {
            $taskDtoArray['dueAfter'] = $dueAfter;
        }

        if ($priority) {
            $taskDtoArray['priority'] = $priority;
        }

        $enhancedTaskDto = TaskDto::denormalize($taskDtoArray);

        $result = $this->queryBus->handle(
            $this->queryFactory->makeQueryInstanceByTypeFromDto(
                QueryFactoryInterface::FIND_BY_CRITERIA_TASK_QUERY,
                $enhancedTaskDto
            )
        );

        // Build pagination data
        $total = $result['total'] ?? count($result['items'] ?? $result);

        // Format the response with items, pagination info, and links
        return $this->createApiResponse([
            'items' => $result['items'] ?? $result,
            'pagination' => $this->getPaginationMetadata(
                $total,
                $pagination['page'],
                $pagination['perPage']
            ),
            '_links' => $this->getPaginationLinks(
                $request,
                $pagination['page'],
                $pagination['perPage'],
                $total
            )
        ]);
    }

    /**
     * Get task by ID (V2)
     *
     * Retrieves detailed task information with enhanced response format
     */
    #[Route('/{uuid}', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Task details',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: "data", type: "object", properties: [
                    new OA\Property(property: "uuid", type: "string"),
                    new OA\Property(property: "title", type: "string"),
                    new OA\Property(property: "description", type: "string"),
                    new OA\Property(property: "status", type: "string"),
                    new OA\Property(property: "assigneeId", type: "string", nullable: true),
                    new OA\Property(property: "priority", type: "string"),
                    new OA\Property(property: "dueDate", type: "string", format: "date-time", nullable: true),
                    new OA\Property(property: "createdAt", type: "string", format: "date-time"),
                    new OA\Property(property: "updatedAt", type: "string", format: "date-time"),
                    new OA\Property(property: "_links", type: "object", properties: [
                        new OA\Property(property: "self", type: "string"),
                        new OA\Property(property: "update", type: "string"),
                        new OA\Property(property: "assignee", type: "string", nullable: true)
                    ])
                ]),
                new OA\Property(property: "status", type: "string", example: "success")
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Task not found'
    )]
    #[OA\Tag(name: 'task-queries')]
    public function getOneAction(string $uuid, Request $request): JsonResponse
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

        // Add HATEOAS links
        $baseUrl = $this->getBaseUrl($request);
        $links = [
            'self' => "{$baseUrl}/api/v2/tasks/{$uuid}",
            'update' => "{$baseUrl}/api/v2/tasks/{$uuid}/status",
        ];

        if (!empty($result['assigneeId'])) {
            $links['assignee'] = "{$baseUrl}/api/v2/users/{$result['assigneeId']}";
        }

        $result['_links'] = $links;

        return $this->createApiResponse($result);
    }

    /**
     * Search tasks (V2)
     *
     * Advanced search functionality for tasks
     */
    #[Route('/search', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Search results',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: "data", type: "object", properties: [
                    new OA\Property(property: "items", type: "array", items: new OA\Items(ref: new Model(type: TaskDto::class))),
                    new OA\Property(property: "count", type: "integer"),
                ]),
                new OA\Property(property: "status", type: "string", example: "success")
            ]
        )
    )]
    #[OA\Parameter(name: 'q', description: 'Search query', in: 'query', required: true, schema: new OA\Schema(type: 'string'))]
    #[OA\Tag(name: 'task-queries')]
    public function searchAction(Request $request): JsonResponse
    {
        $query = $request->query->get('q', '');

        $taskDto = TaskDto::denormalize([
            'search' => $query,
        ]);

        $result = $this->queryBus->handle(
            $this->queryFactory->makeQueryInstanceByTypeFromDto(
                QueryFactoryInterface::SEARCH_TASKS_QUERY,
                $taskDto
            )
        );

        return $this->createApiResponse([
            'items' => $result,
            'count' => count($result)
        ]);
    }
}
