<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Presentation\Rest\V1;

use League\Tactician\CommandBus;
use Micro\Tracker\Task\Application\Dto\UserDto;
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
 * User Commands Controller (API V1)
 *
 * Handles user creation operations
 */
#[ApiVersion('v1')]
#[Route('/api/v1/users')]
class UserCommandsController extends AbstractApiController
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
     * Create a new user
     *
     * Creates a user with the provided name and email
     */
    #[Route('', methods: ['POST'])]
    #[OA\Response(
        response: 201,
        description: 'User created successfully',
        content: new OA\JsonContent(properties: [
            new OA\Property(property: "uuid", type: "string", format: "uuid"),
        ])
    )]
    #[OA\RequestBody(
        description: "User data",
        content: new OA\JsonContent(ref: new Model(type: UserDto::class))
    )]
    #[OA\Tag(name: 'user-commands')]
    public function createAction(
        #[MapRequestPayload] UserDto $userDto,
        Request $request
    ): JsonResponse {
        $result = $this->commandBus->handle(
            $this->commandFactory->makeCommandInstanceByTypeFromDto(
                CommandFactoryInterface::USER_CREATE_COMMAND,
                $userDto
            )
        );

        return $this->createApiResponse(["uuid" => $result], 201);
    }
}
