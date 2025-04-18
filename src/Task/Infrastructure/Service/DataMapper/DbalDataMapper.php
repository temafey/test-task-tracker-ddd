<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Infrastructure\Service\DataMapper;

use MicroModule\Base\Infrastructure\Service\DataMapper\AbstractDataMapper;
use MicroModule\Base\Infrastructure\Service\DataMapper\Types\BooleanType;
use MicroModule\Base\Infrastructure\Service\DataMapper\Types\JsonType;

class DbalDataMapper extends AbstractDataMapper
{
    protected const FIELD_TYPES = [
        'active' => BooleanType::class,
        //'games' => JsonType::class,
    ];

    protected function getFieldTypes(): array
    {
        return self::FIELD_TYPES;
    }
}
