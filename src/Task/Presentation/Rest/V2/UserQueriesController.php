<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Presentation\Rest\V2;

use League\Tactician\CommandBus;
use Micro\Tracker\Task\Application\Dto\UserDto;
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
 * User Queries Controller (API V2)
 *
 * Handles user retrieval with enhanced filtering and response format
 */
#[ApiVersion('v2')]
#[Route('/api/v2/users')]
class UserQueriesController extends AbstractApiController
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
     * Get all users (V2)
     *
     * Retrieves users with enhanced filtering options and pagination
     */
    #[Route('', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'List of users',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: "data", type: "object", properties: [
                    new OA\Property(property: "items", type: "array", items: new OA\Items(
                        type: "object",
                        properties: [
                            new OA\Property(property: "uuid", type: "string"),
                            new OA\Property(property: "name", type: "string"),
                            new OA\Property(property: "email", type: "string"),
                            new OA\Property(property: "role", type: "string"),
                            new OA\Property(property: "avatarUrl", type: "string", nullable: true),
                            new OA\Property(property: "createdAt", type: "string", format: "date-time"),
                            new OA\Property(property: "updatedAt", type: "string", format: "date-time")
                        ]
                    )),
                    new OA\Property(property: "pagination", type: "object", properties: [
                        new OA\Property(property: "total", type: "integer"),
                        new OA\Property(property: "page", type: "integer"),
                        new OA\Property(property: "perPage", type: "integer"),
                        new OA\Property(property: "pages", type: "integer")
                    ])
                ]),
                new OA\Property(property: "status", type: "string", example: "success")
            ]
        )
    )]
    #[OA\Parameter(name: 'name', description: 'Filter by name', in: 'query', schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'email', description: 'Filter by email', in: 'query', schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'role', description: 'Filter by role', in: 'query', schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'page', description: 'Page number', in: 'query', schema: new OA\Schema(type: 'integer', default: 1))]
    #[OA\Parameter(name: 'perPage', description: 'Items per page', in: 'query', schema: new OA\Schema(type: 'integer', default: 10))]
    #[OA\Parameter(name: 'sort', description: 'Sort field', in: 'query', schema: new OA\Schema(type: 'string', enum: ['name', 'email', 'role', 'createdAt']))]
    #[OA\Parameter(name: 'order', description: 'Sort order', in: 'query', schema: new OA\Schema(type: 'string', enum: ['asc', 'desc']))]
    #[OA\Tag(name: 'user-queries')]
    public function getAllAction(
        Request $request,
        #[MapQueryString] UserDto $userDto
    ): JsonResponse {
        // Add pagination parameters
        $pagination = $this->getPaginationParams($request);
        $sort = $request->query->get('sort', 'name');
        $order = $request->query->get('order', 'asc');
        $role = $request->query->get('role');

        // Create enhanced query parameters
        $userDtoArray = $userDto->normalize();
        $userDtoArray = array_merge($userDtoArray, $pagination);
        $userDtoArray['sort'] = $sort;
        $userDtoArray['order'] = $order;

        if ($role) {
            $userDtoArray['role'] = $role;
        }

        $enhancedUserDto = UserDto::denormalize($userDtoArray);

        $result = $this->queryBus->handle(
            $this->queryFactory->makeQueryInstanceByTypeFromDto(
                QueryFactoryInterface::FIND_BY_CRITERIA_USER_QUERY,
                $enhancedUserDto
            )
        );

        // Extract pagination information
        $total = $result['total'] ?? count($result['items'] ?? $result);

        return $this->createApiResponse([
            'items' => $result['items'] ?? $result,
            'pagination' => $this->getPaginationMetadata(
                $total,
                $pagination['page'],
                $pagination['perPage']
            )
        ]);
    }

    /**
     * Get user by ID (V2)
     *
     * Retrieves detailed user information with associated tasks
     */
    #[Route('/{uuid}', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'User details',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: "data", type: "object", properties: [
                    new OA\Property(property: "user", type: "object", properties: [
                        new OA\Property(property: "uuid", type: "string"),
                        new OA\Property(property: "name", type: "string"),
                        new OA\Property(property: "email", type: "string"),
                        new OA\Property(property: "role", type: "string"),
                        new OA\Property(property: "avatarUrl", type: "string", nullable: true),
                        new OA\Property(property: "createdAt", type: "string", format: "date-time"),
                        new OA\Property(property: "updatedAt", type: "string", format: "date-time")
                    ]),
                    new OA\Property(property: "tasks", type: "array", items: new OA\Items(
                        type: "object", properties: [
                            new OA\Property(property: "uuid", type: "string"),
                            new OA\Property(property: "title", type: "string")
                        ]
                    )),
                    new OA\Property(property: "_links", type: "object", properties: [
                        new OA\Property(property: "self", type: "string"),
                        new OA\Property(property: "tasks", type: "string"),
                        new OA\Property(property: "update", type: "string")
                    ])
                ]),
                new OA\Property(property: "status", type: "string", example: "success")
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'User not found'
    )]
    #[OA\Parameter(name: 'includeTasks', description: 'Include assigned tasks', in: 'query', schema: new OA\Schema(type: 'boolean', default: false))]
    #[OA\Tag(name: 'user-queries')]
    public function getOneAction(
        string $uuid,
        Request $request
    ): JsonResponse {
        $includeTasks = filter_var($request->query->get('includeTasks', false), FILTER_VALIDATE_BOOLEAN);

        $userDto = UserDto::denormalize([
            'uuid' => $uuid,
            'includeTasks' => $includeTasks,
        ]);

        $result = $this->queryBus->handle(
            $this->queryFactory->makeQueryInstanceByTypeFromDto(
                QueryFactoryInterface::FETCH_ONE_USER_QUERY,
                $userDto
            )
        );

        if ($result === null) {
            return $this->createApiErrorResponse('User not found', 404);
        }

        // Add HATEOAS links
        $baseUrl = $this->getBaseUrl($request);

        // Format the response
        $response = [
            'user' => $result,
            '_links' => $this->getUserLinks($uuid, $baseUrl)
        ];

        // Add tasks if requested
        if ($includeTasks) {
            $tasksResult = $this->queryBus->handle(
                $this->queryFactory->makeQueryInstanceByType(
                    QueryFactoryInterface::FIND_BY_CRITERIA_TASK_QUERY,
                    null,
                    ['assigneeId' => $uuid]
                )
            );

            $response['tasks'] = array_map(function($task) {
                return [
                    'uuid' => $task['uuid'],
                    'title' => $task['title']
                ];
            }, $tasksResult);
        }

        return $this->createApiResponse($response);
    }
}
