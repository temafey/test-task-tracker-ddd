<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Presentation\Rest\V2;

use League\Tactician\CommandBus;
use Micro\Tracker\Task\Application\Dto\TaskDto;
use Micro\Tracker\Task\Domain\Factory\CommandFactoryInterface;
use Micro\Tracker\Task\Infrastructure\Api\ApiVersion;
use Micro\Tracker\Task\Presentation\Rest\AbstractApiController;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Task Commands Controller (API V2)
 *
 * Handles task operations with enhanced features
 */
#[ApiVersion('v2')]
#[Route('/tasks')]
class TaskCommandsController extends AbstractApiController
{
    /**
     * @param CommandBus $commandBus Command bus for dispatching commands
     * @param CommandFactoryInterface $commandFactory Factory for creating commands
     */
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly CommandFactoryInterface $commandFactory
    ) {
    }

    /**
     * Create a new task (V2)
     *
     * Creates a task with extended features including priority and due date
     */
    #[Route('', methods: ['POST'])]
    #[OA\Response(
        response: 201,
        description: 'Task created successfully',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: "data", type: "object", properties: [
                    new OA\Property(property: "uuid", type: "string", format: "uuid"),
                    new OA\Property(property: "created", type: "boolean"),
                    new OA\Property(property: "links", type: "object", properties: [
                        new OA\Property(property: "self", type: "string"),
                        new OA\Property(property: "update", type: "string"),
                        new OA\Property(property: "assign", type: "string")
                    ])
                ]),
                new OA\Property(property: "status", type: "string", example: "success")
            ]
        )
    )]
    #[OA\RequestBody(
        description: "Task data (V2)",
        content: new OA\JsonContent(properties: [
            new OA\Property(property: "title", type: "string"),
            new OA\Property(property: "description", type: "string"),
            new OA\Property(property: "status", type: "string", enum: ["todo", "in_progress", "done"]),
            new OA\Property(property: "assigneeId", type: "string", format: "uuid", nullable: true),
            new OA\Property(property: "priority", type: "string", enum: ["low", "medium", "high"]),
            new OA\Property(property: "dueDate", type: "string", format: "date-time")
        ])
    )]
    #[OA\Parameter(
        name: 'title',
        description: 'Task title',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'description',
        description: 'Task description',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'status',
        description: 'Task status',
        in: 'query',
        schema: new OA\Schema(type: 'string', enum: ["todo", "in_progress", "done"])
    )]
    #[OA\Parameter(
        name: 'assigneeId',
        description: 'UUID of task assignee',
        in: 'query',
        schema: new OA\Schema(type: 'string', format: 'uuid')
    )]
    #[OA\Parameter(
        name: 'priority',
        description: 'Task priority level',
        in: 'query',
        schema: new OA\Schema(type: 'string', enum: ["low", "medium", "high"])
    )]
    #[OA\Parameter(
        name: 'dueDate',
        description: 'Task due date',
        in: 'query',
        schema: new OA\Schema(type: 'string', format: 'date-time')
    )]
    #[OA\Tag(name: 'task-commands')]
    public function createAction(
        #[MapRequestPayload] TaskDto $taskDto,
        Request $request
    ): JsonResponse {
        $result = $this->commandBus->handle(
            $this->commandFactory->makeCommandInstanceByTypeFromDto(
                CommandFactoryInterface::TASK_CREATE_COMMAND,
                $taskDto
            )
        );

        $baseUrl = $this->getBaseUrl($request);

        // Enhanced response format with HATEOAS links
        return $this->createApiResponse([
            "uuid" => $result,
            "created" => true,
            "links" => $this->getTaskLinks($result, $baseUrl)
        ], 201);
    }

    /**
     * Update task status (V2)
     *
     * Updates the status with enhanced response format
     */
    #[Route('/{uuid}/status', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'Task status updated successfully',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: "data", type: "object", properties: [
                    new OA\Property(property: "uuid", type: "string", format: "uuid"),
                    new OA\Property(property: "status", type: "string"),
                    new OA\Property(property: "links", type: "object", properties: [
                        new OA\Property(property: "self", type: "string")
                    ])
                ]),
                new OA\Property(property: "status", type: "string", example: "success")
            ]
        )
    )]
    #[OA\RequestBody(
        description: "Status update",
        content: new OA\JsonContent(properties: [
            new OA\Property(property: "status", type: "string", enum: ["todo", "in_progress", "done"]),
            new OA\Property(property: "comment", type: "string", description: "Optional comment about the status change")
        ])
    )]
    #[OA\Parameter(
        name: 'title',
        description: 'Task title',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'description',
        description: 'Task description',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'status',
        description: 'Task status',
        in: 'query',
        schema: new OA\Schema(type: 'string', enum: ["todo", "in_progress", "done"])
    )]
    #[OA\Parameter(
        name: 'assigneeId',
        description: 'UUID of task assignee',
        in: 'query',
        schema: new OA\Schema(type: 'string', format: 'uuid')
    )]
    #[OA\Parameter(
        name: 'priority',
        description: 'Task priority level',
        in: 'query',
        schema: new OA\Schema(type: 'string', enum: ["low", "medium", "high"])
    )]
    #[OA\Parameter(
        name: 'dueDate',
        description: 'Task due date',
        in: 'query',
        schema: new OA\Schema(type: 'string', format: 'date-time')
    )]
    #[OA\Tag(name: 'task-commands')]
    #[OA\Parameter(
        name: 'status',
        description: 'New task status',
        in: 'query',
        schema: new OA\Schema(type: 'string', enum: ["todo", "in_progress", "done"])
    )]
    #[OA\Parameter(
        name: 'comment',
        description: 'Optional comment about the status change',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    public function updateStatusAction(
        string $uuid,
        #[MapRequestPayload] TaskDto $taskDto,
        Request $request
    ): JsonResponse {
        $taskDto = TaskDto::denormalize([
            'uuid' => $uuid,
            'status' => $taskDto->status,
            // V2 supports additional comment field
            'statusComment' => $request->request->get('comment')
        ]);

        $result = $this->commandBus->handle(
            $this->commandFactory->makeCommandInstanceByTypeFromDto(
                CommandFactoryInterface::TASK_UPDATE_STATUS_COMMAND,
                $taskDto
            )
        );

        $baseUrl = $this->getBaseUrl($request);

        return $this->createApiResponse([
            "uuid" => $result,
            "status" => $taskDto->status,
            "links" => [
                "self" => "{$baseUrl}/api/v2/tasks/{$result}"
            ]
        ]);
    }

    /**
     * Set task priority (V2)
     *
     * Sets the priority level for a task
     */
    #[Route('/{uuid}/priority', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'Task priority updated successfully',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: "data", type: "object", properties: [
                    new OA\Property(property: "uuid", type: "string", format: "uuid"),
                    new OA\Property(property: "priority", type: "string")
                ]),
                new OA\Property(property: "status", type: "string", example: "success")
            ]
        )
    )]
    #[OA\RequestBody(
        description: "Priority data",
        content: new OA\JsonContent(properties: [
            new OA\Property(property: "priority", type: "string", enum: ["low", "medium", "high"])
        ])
    )]
    #[OA\Parameter(
        name: 'title',
        description: 'Task title',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'description',
        description: 'Task description',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'status',
        description: 'Task status',
        in: 'query',
        schema: new OA\Schema(type: 'string', enum: ["todo", "in_progress", "done"])
    )]
    #[OA\Parameter(
        name: 'assigneeId',
        description: 'UUID of task assignee',
        in: 'query',
        schema: new OA\Schema(type: 'string', format: 'uuid')
    )]
    #[OA\Parameter(
        name: 'priority',
        description: 'Task priority level',
        in: 'query',
        schema: new OA\Schema(type: 'string', enum: ["low", "medium", "high"])
    )]
    #[OA\Parameter(
        name: 'dueDate',
        description: 'Task due date',
        in: 'query',
        schema: new OA\Schema(type: 'string', format: 'date-time')
    )]
    #[OA\Tag(name: 'task-commands')]
    #[OA\Parameter(
        name: 'priority',
        description: 'Task priority level',
        in: 'query',
        schema: new OA\Schema(type: 'string', enum: ["low", "medium", "high"])
    )]
    public function updatePriorityAction(
        string $uuid,
        #[MapRequestPayload] TaskDto $taskDto
    ): JsonResponse {
        $taskDto = TaskDto::denormalize([
            'uuid' => $uuid,
            'priority' => $taskDto->priority ?? 'medium',
        ]);

        $result = $this->commandBus->handle(
            $this->commandFactory->makeCommandInstanceByTypeFromDto(
                CommandFactoryInterface::TASK_UPDATE_PRIORITY_COMMAND,
                $taskDto
            )
        );

        return $this->createApiResponse([
            "uuid" => $result,
            "priority" => $taskDto->priority
        ]);
    }

    /**
     * Set task due date (V2)
     *
     * Sets a deadline for task completion
     */
    #[Route('/{uuid}/due-date', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'Task due date set successfully',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: "data", type: "object", properties: [
                    new OA\Property(property: "uuid", type: "string", format: "uuid"),
                    new OA\Property(property: "dueDate", type: "string", format: "date-time")
                ]),
                new OA\Property(property: "status", type: "string", example: "success")
            ]
        )
    )]
    #[OA\RequestBody(
        description: "Due date data",
        content: new OA\JsonContent(properties: [
            new OA\Property(property: "dueDate", type: "string", format: "date-time")
        ])
    )]
    #[OA\Parameter(
        name: 'title',
        description: 'Task title',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'description',
        description: 'Task description',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'status',
        description: 'Task status',
        in: 'query',
        schema: new OA\Schema(type: 'string', enum: ["todo", "in_progress", "done"])
    )]
    #[OA\Parameter(
        name: 'assigneeId',
        description: 'UUID of task assignee',
        in: 'query',
        schema: new OA\Schema(type: 'string', format: 'uuid')
    )]
    #[OA\Parameter(
        name: 'priority',
        description: 'Task priority level',
        in: 'query',
        schema: new OA\Schema(type: 'string', enum: ["low", "medium", "high"])
    )]
    #[OA\Parameter(
        name: 'dueDate',
        description: 'Task due date',
        in: 'query',
        schema: new OA\Schema(type: 'string', format: 'date-time')
    )]
    #[OA\Tag(name: 'task-commands')]
    #[OA\Parameter(
        name: 'dueDate',
        description: 'Task due date',
        in: 'query',
        schema: new OA\Schema(type: 'string', format: 'date-time')
    )]
    public function setDueDateAction(
        string $uuid,
        #[MapRequestPayload] TaskDto $taskDto
    ): JsonResponse {
        $taskDto = TaskDto::denormalize([
            'uuid' => $uuid,
            'dueDate' => $taskDto->dueDate,
        ]);

        $result = $this->commandBus->handle(
            $this->commandFactory->makeCommandInstanceByTypeFromDto(
                CommandFactoryInterface::TASK_SET_DUE_DATE_COMMAND,
                $taskDto
            )
        );

        return $this->createApiResponse([
            "uuid" => $result,
            "dueDate" => $taskDto->dueDate
        ]);
    }

    /**
     * Bulk update tasks (V2)
     *
     * Updates multiple tasks in a single request
     */
    #[Route('/bulk', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'Tasks updated successfully',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: "data", type: "object", properties: [
                    new OA\Property(property: "updatedCount", type: "integer"),
                    new OA\Property(property: "taskIds", type: "array", items: new OA\Items(type: "string"))
                ]),
                new OA\Property(property: "status", type: "string", example: "success")
            ]
        )
    )]
    #[OA\RequestBody(
        description: "Bulk update data",
        content: new OA\JsonContent(properties: [
            new OA\Property(property: "tasks", type: "array", items: new OA\Items(
                type: "object",
                properties: [
                    new OA\Property(property: "uuid", type: "string", format: "uuid"),
                    new OA\Property(property: "status", type: "string", nullable: true),
                    new OA\Property(property: "priority", type: "string", nullable: true),
                    new OA\Property(property: "assigneeId", type: "string", format: "uuid", nullable: true)
                ]
            ))
        ])
    )]
    #[OA\Parameter(
        name: 'title',
        description: 'Task title',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'description',
        description: 'Task description',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'status',
        description: 'Task status',
        in: 'query',
        schema: new OA\Schema(type: 'string', enum: ["todo", "in_progress", "done"])
    )]
    #[OA\Parameter(
        name: 'assigneeId',
        description: 'UUID of task assignee',
        in: 'query',
        schema: new OA\Schema(type: 'string', format: 'uuid')
    )]
    #[OA\Parameter(
        name: 'priority',
        description: 'Task priority level',
        in: 'query',
        schema: new OA\Schema(type: 'string', enum: ["low", "medium", "high"])
    )]
    #[OA\Parameter(
        name: 'dueDate',
        description: 'Task due date',
        in: 'query',
        schema: new OA\Schema(type: 'string', format: 'date-time')
    )]
    #[OA\Tag(name: 'task-commands')]
    #[OA\Parameter(
        name: 'tasks',
        description: 'Array of task updates',
        in: 'query',
        schema: new OA\Schema(
            type: 'array',
            items: new OA\Items(
                type: 'object',
                properties: [
                    new OA\Property(property: "uuid", type: "string", format: "uuid"),
                    new OA\Property(property: "status", type: "string", nullable: true),
                    new OA\Property(property: "priority", type: "string", nullable: true),
                    new OA\Property(property: "assigneeId", type: "string", format: "uuid", nullable: true)
                ]
            )
        )
    )]
    public function bulkUpdateAction(Request $request): JsonResponse
    {
        $tasks = $request->request->all()['tasks'] ?? [];
        $updatedTasks = [];

        foreach ($tasks as $task) {
            if (!isset($task['uuid'])) {
                continue;
            }

            $taskDto = TaskDto::denormalize($task);

            $result = $this->commandBus->handle(
                $this->commandFactory->makeCommandInstanceByTypeFromDto(
                    CommandFactoryInterface::TASK_BULK_UPDATE_COMMAND,
                    $taskDto
                )
            );

            if ($result) {
                $updatedTasks[] = $task['uuid'];
            }
        }

        return $this->createApiResponse([
            "updatedCount" => count($updatedTasks),
            "taskIds" => $updatedTasks
        ]);
    }
}
