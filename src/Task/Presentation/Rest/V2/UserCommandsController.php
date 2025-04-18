<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Presentation\Rest\V2;

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
 * User Commands Controller (API V2)
 *
 * Handles user management operations with enhanced features
 */
#[ApiVersion('v2')]
#[Route('/users')]
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
     * Create a new user (V2)
     *
     * Creates a user with enhanced fields like role and avatar
     */
    #[Route('', methods: ['POST'])]
    #[OA\Response(
        response: 201,
        description: 'User created successfully',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: "data", type: "object", properties: [
                    new OA\Property(property: "uuid", type: "string", format: "uuid"),
                    new OA\Property(property: "links", type: "object", properties: [
                        new OA\Property(property: "self", type: "string"),
                        new OA\Property(property: "tasks", type: "string")
                    ])
                ]),
                new OA\Property(property: "status", type: "string", example: "success")
            ]
        )
    )]
    #[OA\RequestBody(
        description: "User data (V2)",
        content: new OA\JsonContent(properties: [
            new OA\Property(property: "name", type: "string"),
            new OA\Property(property: "email", type: "string", format: "email"),
            new OA\Property(property: "role", type: "string", enum: ["user", "manager", "admin"]),
            new OA\Property(property: "avatarUrl", type: "string", nullable: true)
        ])
    )]
    #[OA\Parameter(
        name: 'name',
        description: 'User name',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'email',
        description: 'User email',
        in: 'query',
        schema: new OA\Schema(type: 'string', format: 'email')
    )]
    #[OA\Parameter(
        name: 'role',
        description: 'User role',
        in: 'query',
        schema: new OA\Schema(type: 'string', enum: ["user", "manager", "admin"])
    )]
    #[OA\Parameter(
        name: 'avatarUrl',
        description: 'User avatar URL',
        in: 'query',
        schema: new OA\Schema(type: 'string', nullable: true)
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

        $baseUrl = $this->getBaseUrl($request);

        return $this->createApiResponse([
            "uuid" => $result,
            "links" => $this->getUserLinks($result, $baseUrl)
        ], 201);
    }

    /**
     * Update user (V2)
     *
     * Updates user information
     */
    #[Route('/{uuid}', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'User updated successfully',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: "data", type: "object", properties: [
                    new OA\Property(property: "uuid", type: "string", format: "uuid"),
                    new OA\Property(property: "updated", type: "boolean")
                ]),
                new OA\Property(property: "status", type: "string", example: "success")
            ]
        )
    )]
    #[OA\RequestBody(
        description: "User update data",
        content: new OA\JsonContent(properties: [
            new OA\Property(property: "name", type: "string", nullable: true),
            new OA\Property(property: "email", type: "string", format: "email", nullable: true),
            new OA\Property(property: "role", type: "string", enum: ["user", "manager", "admin"], nullable: true),
            new OA\Property(property: "avatarUrl", type: "string", nullable: true)
        ])
    )]
    #[OA\Parameter(
        name: 'name',
        description: 'User name',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'email',
        description: 'User email',
        in: 'query',
        schema: new OA\Schema(type: 'string', format: 'email')
    )]
    #[OA\Parameter(
        name: 'role',
        description: 'User role',
        in: 'query',
        schema: new OA\Schema(type: 'string', enum: ["user", "manager", "admin"])
    )]
    #[OA\Parameter(
        name: 'avatarUrl',
        description: 'User avatar URL',
        in: 'query',
        schema: new OA\Schema(type: 'string', nullable: true)
    )]
    #[OA\Tag(name: 'user-commands')]
    #[OA\Parameter(
        name: 'name',
        description: 'Updated user name',
        in: 'query',
        schema: new OA\Schema(type: 'string', nullable: true)
    )]
    #[OA\Parameter(
        name: 'email',
        description: 'Updated user email',
        in: 'query',
        schema: new OA\Schema(type: 'string', format: 'email', nullable: true)
    )]
    #[OA\Parameter(
        name: 'role',
        description: 'Updated user role',
        in: 'query',
        schema: new OA\Schema(type: 'string', enum: ["user", "manager", "admin"], nullable: true)
    )]
    #[OA\Parameter(
        name: 'avatarUrl',
        description: 'Updated user avatar URL',
        in: 'query',
        schema: new OA\Schema(type: 'string', nullable: true)
    )]
    public function updateAction(
        string $uuid,
        #[MapRequestPayload] UserDto $userDto
    ): JsonResponse {
        // Add UUID to the DTO for update
        $userDtoArray = $userDto->normalize();
        $userDtoArray['uuid'] = $uuid;
        $enhancedUserDto = UserDto::denormalize($userDtoArray);

        $result = $this->commandBus->handle(
            $this->commandFactory->makeCommandInstanceByTypeFromDto(
                CommandFactoryInterface::USER_UPDATE_COMMAND,
                $enhancedUserDto
            )
        );

        return $this->createApiResponse([
            "uuid" => $result,
            "updated" => true
        ]);
    }

    /**
     * Update user role (V2)
     *
     * Updates a user's role
     */
    #[Route('/{uuid}/role', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'User role updated successfully',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(property: "data", type: "object", properties: [
                    new OA\Property(property: "uuid", type: "string", format: "uuid"),
                    new OA\Property(property: "role", type: "string")
                ]),
                new OA\Property(property: "status", type: "string", example: "success")
            ]
        )
    )]
    #[OA\RequestBody(
        description: "Role update data",
        content: new OA\JsonContent(properties: [
            new OA\Property(property: "role", type: "string", enum: ["user", "manager", "admin"])
        ])
    )]
#[OA\Parameter(
    name: 'name',
    description: 'User name',
    in: 'query',
    schema: new OA\Schema(type: 'string')
)]
#[OA\Parameter(
    name: 'email',
    description: 'User email',
    in: 'query',
    schema: new OA\Schema(type: 'string', format: 'email')
)]
#[OA\Parameter(
    name: 'role',
    description: 'User role',
    in: 'query',
    schema: new OA\Schema(type: 'string', enum: ["user", "manager", "admin"])
)]
#[OA\Parameter(
    name: 'avatarUrl',
    description: 'User avatar URL',
    in: 'query',
    schema: new OA\Schema(type: 'string', nullable: true)
)]
    #[OA\Tag(name: 'user-commands')]
#[OA\Parameter(
    name: 'role',
    description: 'New user role',
    in: 'query',
    schema: new OA\Schema(type: 'string', enum: ["user", "manager", "admin"])
)]
    public function updateRoleAction(
        string $uuid,
        Request $request
    ): JsonResponse {
        $role = $request->request->get('role');

        if (!in_array($role, ['user', 'manager', 'admin'])) {
            return $this->createApiErrorResponse('Invalid role specified', 400);
        }

        $userDto = UserDto::denormalize([
            'uuid' => $uuid,
            'role' => $role,
        ]);

        $result = $this->commandBus->handle(
            $this->commandFactory->makeCommandInstanceByTypeFromDto(
                CommandFactoryInterface::USER_UPDATE_ROLE_COMMAND,
                $userDto
            )
        );

        return $this->createApiResponse([
            "uuid" => $result,
            "role" => $role
        ]);
    }
}
