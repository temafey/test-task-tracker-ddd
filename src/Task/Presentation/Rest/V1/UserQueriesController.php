<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Presentation\Rest\V1;

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
 * User Queries Controller (API V1)
 *
 * Handles user retrieval operations
 */
#[ApiVersion('v1')]
#[Route('/api/v1/users')]
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
     * Get all users
     *
     * Retrieves all users with optional filtering by name and email
     */
    #[Route('', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'List of users',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(ref: new Model(type: UserDto::class))
                ),
                new OA\Property(property: 'status', type: 'string', example: 'success')
            ]
        )
    )]
    #[OA\Parameter(
        name: 'name',
        description: 'Filter by user name',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'email',
        description: 'Filter by user email',
        in: 'query',
        schema: new OA\Schema(type: 'string', format: 'email')
    )]
    #[OA\Tag(name: 'user-queries')]
    public function getAllAction(#[MapQueryString] UserDto $userDto): JsonResponse
    {
        $result = $this->queryBus->handle(
            $this->queryFactory->makeQueryInstanceByTypeFromDto(
                QueryFactoryInterface::FIND_BY_CRITERIA_USER_QUERY,
                $userDto
            )
        );

        return $this->createApiResponse($result);
    }

    /**
     * Get user by ID
     *
     * Retrieves a specific user by UUID
     */
    #[Route('/{uuid}', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'User details',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'data',
                    ref: new Model(type: UserDto::class)
                ),
                new OA\Property(property: 'status', type: 'string', example: 'success')
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'User not found'
    )]
    #[OA\Tag(name: 'user-queries')]
    public function getOneAction(string $uuid): JsonResponse
    {
        $userDto = UserDto::denormalize([
            'uuid' => $uuid,
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

        return $this->createApiResponse($result);
    }
}
