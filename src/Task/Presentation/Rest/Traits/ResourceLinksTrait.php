<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Presentation\Rest\Traits;

use Symfony\Component\HttpFoundation\Request;

/**
 * Trait for generating resource links
 */
trait ResourceLinksTrait
{
    /**
     * Generate task resource links
     *
     * @param string $uuid Task UUID
     * @param string $baseUrl Base API URL
     * @param string $version API version
     * @return array<string, string> Resource links
     */
    protected function getTaskLinks(string $uuid, string $baseUrl, string $version = 'v2'): array
    {
        return [
            'self' => "{$baseUrl}/api/{$version}/tasks/{$uuid}",
            'update' => "{$baseUrl}/api/{$version}/tasks/{$uuid}/status",
            'assign' => "{$baseUrl}/api/{$version}/tasks/{$uuid}/assign",
            'priority' => "{$baseUrl}/api/{$version}/tasks/{$uuid}/priority",
            'dueDate' => "{$baseUrl}/api/{$version}/tasks/{$uuid}/due-date"
        ];
    }

    /**
     * Generate user resource links
     *
     * @param string $uuid User UUID
     * @param string $baseUrl Base API URL
     * @param string $version API version
     * @return array<string, string> Resource links
     */
    protected function getUserLinks(string $uuid, string $baseUrl, string $version = 'v2'): array
    {
        return [
            'self' => "{$baseUrl}/api/{$version}/users/{$uuid}",
            'tasks' => "{$baseUrl}/api/{$version}/tasks?assigneeId={$uuid}",
            'update' => "{$baseUrl}/api/{$version}/users/{$uuid}"
        ];
    }

    /**
     * Get base URL from request
     */
    protected function getBaseUrl(Request $request): string
    {
        return $request->getSchemeAndHttpHost() . $request->getBasePath();
    }
}
