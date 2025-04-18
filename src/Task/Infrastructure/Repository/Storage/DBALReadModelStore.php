<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Infrastructure\Repository\Storage;

use MicroModule\Base\Infrastructure\Repository\AbstractDBALReadModelStore;

//[Autoconfigure(bind: [
//    '$connection' => '@doctrine.dbal.default_connection',
//    '$tableName' => 'bet',
//    '$primaryKey' => 'uuid',
//    '$dataMapper' => '@skeleton.transaction_file.infrastructure.service.data_mapper.dbal'
//])]
class DBALReadModelStore extends AbstractDBALReadModelStore
{
    protected function getDefaultQuery(): string
    {
        return sprintf('SELECT * FROM %s', $this->tableName);
    }
}
