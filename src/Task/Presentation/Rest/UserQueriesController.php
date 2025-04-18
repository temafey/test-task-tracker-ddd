<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Presentation\Rest;

use League\Tactician\CommandBus;
use Micro\Tracker\Task\Application\Dto\UserDto;
use Micro\Tracker\Task\Domain\Factory\QueryFactoryInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @class UserQueriesController
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

class UserQueriesController extends AbstractController
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
     * Request of 'UserDto' to process 'findByCriteriaUser' query
     */
     #[Route('/api/users', methods: ['GET'])]
     #[OA\Response(
         response: 200,
         description: 'Request of \'UserDto\' to process \'findByCriteriaUser\' query',
         content: new OA\JsonContent(
             type: 'array',
             items: new OA\Items(ref: new Model(type: UserDto::class, groups: ['full']))
         )
    )]
    #[OA\Parameter(
        name: 'name',
        description: 'The field \'name\' of \'UserDto\'',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'email',
        description: 'The field \'email\' of \'UserDto\'',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    public function getAllAction(#[MapQueryString] UserDto $userDto): JsonResponse
    {
		$result = $this->queryBus->handle(
            $this->queryFactory->makeQueryInstanceByTypeFromDto(
                QueryFactoryInterface::FIND_BY_CRITERIA_USER_QUERY,
                $userDto
            )
        );

        return new JsonResponse($result);
    }

    /**
     * Request of 'UserDto' to process 'fetchOneUser' query
     */
     #[Route('/api/users/{uuid}', methods: ['GET'])]
     #[OA\Response(
         response: 200,
         description: 'Request of \'UserDto\' to process \'fetchOneUser\' query',
         content: new OA\JsonContent(
             type: 'array',
             items: new OA\Items(ref: new Model(type: UserDto::class, groups: ['full']))
         )
    )]
    #[OA\Tag(name: 'user-queries')]
    #[OA\Parameter(
        name: 'email',
        description: 'The field \'email\' of \'UserDto\'',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
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

        return new JsonResponse($result);
    }
}
