<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Presentation\Rest;

use League\Tactician\CommandBus;
use Micro\Tracker\Task\Application\Dto\TaskDto;
use Micro\Tracker\Task\Domain\Factory\CommandFactoryInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @class TaskCommandsController
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

class TaskCommandsController extends AbstractController
{
    /**
     * Constructor
     */
    public function __construct(
		#[Autowire(service: 'tactician.commandbus.command.task')] protected commandBus $commandBus,
		protected CommandFactoryInterface $commandFactory
	)
    {
        
    }

    /**
     * Request of 'TaskDto' to process 'taskCreate' command
     */
     #[Route('/api/tasks', methods: ['POST'])]
     #[OA\Response(
         response: 200,
         description: 'Request of \'TaskDto\' to process \'taskCreate\' command',
         content: new OA\JsonContent(
             type: 'array',
             items: new OA\Items(ref: new Model(type: TaskDto::class, groups: ['full']))
         )
    )]
    #[OA\Tag(name: 'task-commands')]
    #[OA\Parameter(
        name: 'title',
        description: 'The field \'title\' of \'TaskDto\'',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'description',
        description: 'The field \'description\' of \'TaskDto\'',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'status',
        description: 'The field \'status\' of \'TaskDto\'',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'assignee_id',
        description: 'The field \'assignee_id\' of \'TaskDto\'',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    public function createAction(#[MapRequestPayload] TaskDto $taskDto): JsonResponse
    {
		$result = $this->commandBus->handle(
            $this->commandFactory->makeCommandInstanceByTypeFromDto(
                CommandFactoryInterface::TASK_CREATE_COMMAND,
                $taskDto
            )
        );

        return new JsonResponse(["uuid" => $result]);
    }

    /**
     * Request of 'TaskDto' to process 'taskUpdateStatus' command
     */
     #[Route('/api/tasks/{uuid}/status', methods: ['PUT'])]
     #[OA\Response(
         response: 200,
         description: 'Request of \'TaskDto\' to process \'taskUpdateStatus\' command',
         content: new OA\JsonContent(
             type: 'array',
             items: new OA\Items(ref: new Model(type: TaskDto::class, groups: ['full']))
         )
    )]
    #[OA\Tag(name: 'task-commands')]
    #[OA\Parameter(
        name: 'status',
        description: 'The field \'status\' of \'TaskDto\'',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    public function updateStatusAction(string $uuid, #[MapRequestPayload] TaskDto $taskDto): JsonResponse
    {
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

        return new JsonResponse(["uuid" => $result]);
    }

    /**
     * Request of 'TaskDto' to process 'taskAssign' command
     */
     #[Route('/api/tasks/{uuid}/assign', methods: ['PUT'])]
     #[OA\Response(
         response: 200,
         description: 'Request of \'TaskDto\' to process \'taskAssign\' command',
         content: new OA\JsonContent(
             type: 'array',
             items: new OA\Items(ref: new Model(type: TaskDto::class, groups: ['full']))
         )
    )]
    #[OA\Tag(name: 'task-commands')]
    #[OA\Parameter(
        name: 'assignee_id',
        description: 'The field \'assigneeId\' of \'TaskDto\'',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    public function assignAction(string $uuid, #[MapRequestPayload] TaskDto $taskDto): JsonResponse
    {
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

        return new JsonResponse(["uuid" => $result]);
    }
}
