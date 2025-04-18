<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Presentation\Rest\V1;

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
 * Task Commands Controller (API V1)
 *
 * Handles task creation, status updates, and assignment
 */
#[ApiVersion('v1')]
#[Route('/api/v1/tasks')]
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
     * Create a new task
     *
     * Creates a task with the provided title, description, and status
     */
    #[Route('', methods: ['POST'])]
    #[OA\Response(
        response: 201,
        description: 'Task created successfully',
        content: new OA\JsonContent(properties: [
            new OA\Property(property: "uuid", type: "string", format: "uuid"),
        ])
    )]
    #[OA\RequestBody(
        description: "Task data",
        content: new OA\JsonContent(ref: new Model(type: TaskDto::class))
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

        return $this->createApiResponse(["uuid" => $result], 201);
    }

    /**
     * Update task status
     *
     * Changes the status of an existing task (todo, in_progress, done)
     */
    #[Route('/{uuid}/status', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'Task status updated successfully',
        content: new OA\JsonContent(properties: [
            new OA\Property(property: "uuid", type: "string", format: "uuid"),
        ])
    )]
    #[OA\RequestBody(
        description: "Status update data",
        content: new OA\JsonContent(properties: [
            new OA\Property(property: "status", type: "string", enum: ["todo", "in_progress", "done"]),
        ])
    )]
    #[OA\Tag(name: 'task-commands')]
    public function updateStatusAction(
        string $uuid,
        #[MapRequestPayload] TaskDto $taskDto
    ): JsonResponse {
        $taskDto = TaskDto::denormalize([
            'uuid' => $uuid,
            'status' => $taskDto->status,
        ]);

        $result = $this->commandBus->handle(
            $this->commandFactory->makeCommandInstanceByTypeFromDto(
                CommandFactoryInterface::TASK_UPDATE_STATUS_COMMAND,
                $taskDto
            )
        );

        return $this->createApiResponse(["uuid" => $result]);
    }

    /**
     * Assign task to user
     *
     * Assigns a task to a specific user by UUID
     */
    #[Route('/{uuid}/assign', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'Task assigned successfully',
        content: new OA\JsonContent(properties: [
            new OA\Property(property: "uuid", type: "string", format: "uuid"),
        ])
    )]
    #[OA\RequestBody(
        description: "Assignment data",
        content: new OA\JsonContent(properties: [
            new OA\Property(property: "assigneeId", type: "string", format: "uuid"),
        ])
    )]
    #[OA\Tag(name: 'task-commands')]
    public function assignAction(
        string $uuid,
        #[MapRequestPayload] TaskDto $taskDto
    ): JsonResponse {
        $taskDto = TaskDto::denormalize([
            'uuid' => $uuid,
            'assignee_id' => $taskDto->assigneeId,
        ]);

        $result = $this->commandBus->handle(
            $this->commandFactory->makeCommandInstanceByTypeFromDto(
                CommandFactoryInterface::TASK_ASSIGN_COMMAND,
                $taskDto
            )
        );

        return $this->createApiResponse(["uuid" => $result]);
    }
}
