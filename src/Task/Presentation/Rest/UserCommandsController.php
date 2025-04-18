<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Presentation\Rest;

use League\Tactician\CommandBus;
use Micro\Tracker\Task\Application\Dto\UserDto;
use Micro\Tracker\Task\Domain\Factory\CommandFactoryInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @class UserCommandsController
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

class UserCommandsController extends AbstractController
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
     * Request of 'UserDto' to process 'userCreate' command
     */
     #[Route('/api/users', methods: ['POST'])]
     #[OA\Response(
         response: 200,
         description: 'Request of \'UserDto\' to process \'userCreate\' command',
         content: new OA\JsonContent(
             type: 'array',
             items: new OA\Items(ref: new Model(type: UserDto::class, groups: ['full']))
         )
    )]
    #[OA\Tag(name: 'user-commands')]
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
    public function createAction(#[MapRequestPayload] UserDto $userDto): JsonResponse
    {
		$result = $this->commandBus->handle(
            $this->commandFactory->makeCommandInstanceByTypeFromDto(
                CommandFactoryInterface::USER_CREATE_COMMAND,
                $userDto
            )
        );

        return new JsonResponse(["uuid" => $result]);
    }
}
