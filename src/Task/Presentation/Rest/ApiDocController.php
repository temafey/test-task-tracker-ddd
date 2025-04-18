<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Presentation\Rest;

use Micro\Tracker\Task\Infrastructure\Api\ApiVersionResolver;
use Nelmio\ApiDocBundle\Controller\DocumentationController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * API Documentation Controller
 *
 * Provides versioned API documentation
 */
readonly class ApiDocController
{
    /**
     * @param DocumentationController $docController Nelmio documentation controller
     * @param ApiVersionResolver $versionResolver Version resolver service
     */
    public function __construct(
        private DocumentationController $docController,
        private ApiVersionResolver      $versionResolver
    ) {
    }

    /**
     * API V1 Documentation
     */
    #[Route('/api/v1/doc', name: 'api_v1_doc')]
    public function docV1(Request $request): Response
    {
        return $this->docController->__invoke($request, 'v1');
    }

    /**
     * API V2 Documentation
     */
    #[Route('/api/v2/doc', name: 'api_v2_doc')]
    public function docV2(Request $request): Response
    {
        return $this->docController->__invoke($request, 'v2');
    }

    /**
     * Latest API Documentation
     */
    #[Route('/api/doc', name: 'api_latest_doc')]
    public function docLatest(Request $request): Response
    {
        $latestVersion = $this->versionResolver->getLatestVersion();
        return $this->docController->__invoke($request, $latestVersion);
    }
}
