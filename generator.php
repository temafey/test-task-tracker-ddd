<?php

use MicroModule\MicroserviceGenerator\Generator\DataTypeInterface;
use MicroModule\MicroserviceGenerator\Service\ProjectBuilder;

set_time_limit(0);

require "vendor/autoload.php";

$domainNamespace = "Micro\Tracker\Task";
$mainDomainName = "task";

// Структура сущностей и их команды
$entityStructure = [
    $mainDomainName => [
        "task-create",
        "task-update-status",
        "task-assign",
    ],
    "user" => [
        "user-create",
        "user-update",
    ],
];

// Структура объектов передачи данных (DTO)
$dtoStructure = [
    $mainDomainName => [
        "process_uuid",
        "uuid",
        "title",
        "description",
        "status",
        "assignee_id",
        "created_at",
    ],
    "user" => [
        "process_uuid",
        "uuid",
        "name",
        "email",
        "created_at",
    ],
];

// Структура моделей чтения (Read Models)
$readModelStructure = [
    $mainDomainName => [
        "uuid",
        "title",
        "description",
        "status",
        "assignee_id",
        "created_at",
        "updated_at",
    ],
    "user" => [
        "uuid",
        "name",
        "email",
        "created_at",
        "updated_at",
    ],
];

// Структура объектов-значений (Value Objects)
$valueObjectStructure = [
    "process_uuid" => [
        "type" => DataTypeInterface::VALUE_OBJECT_TYPE_IDENTITY_UUID,
    ],
    "uuid" => [
        "type" => DataTypeInterface::VALUE_OBJECT_TYPE_IDENTITY_UUID,
    ],
    "title" => [
        "type" => DataTypeInterface::VALUE_OBJECT_TYPE_STRING,
    ],
    "description" => [
        "type" => DataTypeInterface::VALUE_OBJECT_TYPE_STRING,
    ],
    "status" => [
        "type" => DataTypeInterface::VALUE_OBJECT_TYPE_STRING,
    ],
    "assignee_id" => [
        "type" => DataTypeInterface::VALUE_OBJECT_TYPE_IDENTITY_UUID,
    ],
    "name" => [
        "type" => DataTypeInterface::VALUE_OBJECT_TYPE_STRING,
    ],
    "email" => [
        "type" => DataTypeInterface::VALUE_OBJECT_TYPE_STRING,
    ],
    "created_at" => [
        "type" => DataTypeInterface::VALUE_OBJECT_TYPE_DATETIME_DATETIME,
    ],
    "updated_at" => [
        "type" => DataTypeInterface::VALUE_OBJECT_TYPE_DATETIME_DATETIME,
    ],
    "find_criteria" => [
        "type" => DataTypeInterface::VALUE_OBJECT_TYPE_FIND_CRITERIA,
    ],
    $mainDomainName => [
        "type" => DataTypeInterface::VALUE_OBJECT_TYPE_ENTITY,
        DataTypeInterface::BUILDER_STRUCTURE_TYPE_ARGS => [
            "title",
            "description",
            "status",
            "assignee_id",
            "created_at",
            "updated_at",
        ],
    ],
    "user" => [
        "type" => DataTypeInterface::VALUE_OBJECT_TYPE_ENTITY,
        DataTypeInterface::BUILDER_STRUCTURE_TYPE_ARGS => [
            "name",
            "email",
            "created_at",
            "updated_at",
        ],
    ],
];

// Структура саг для управления распределенными транзакциями
$sagaStructure = [
    "task-assignment" => [
        DataTypeInterface::BUILDER_STRUCTURE_TYPE_ARGS => [
            DataTypeInterface::STRUCTURE_TYPE_COMMAND_BUS,
            DataTypeInterface::STRUCTURE_TYPE_FACTORY_COMMAND,
        ],
        DataTypeInterface::STRUCTURE_TYPE_EVENT => [
            'task-assign' => 'task-assign',
        ],
    ],
];

// Структура REST API
$restStructure = [
    "task-commands" => [
        "create" => [
            "route" => "/api/tasks",
            "method" => "post",
            DataTypeInterface::STRUCTURE_TYPE_DTO => "task",
            DataTypeInterface::STRUCTURE_TYPE_COMMAND => "task-create",
        ],
        "update-status" => [
            "route" => "/api/tasks/{id}/status",
            "method" => "put",
            DataTypeInterface::STRUCTURE_TYPE_DTO => "task",
            DataTypeInterface::STRUCTURE_TYPE_COMMAND => "task-update-status",
        ],
        "assign" => [
            "route" => "/api/tasks/{id}/assign",
            "method" => "put",
            DataTypeInterface::STRUCTURE_TYPE_DTO => "task",
            DataTypeInterface::STRUCTURE_TYPE_COMMAND => "task-assign",
        ],
    ],
    "task-queries" => [
        "get-all" => [
            "route" => "/api/tasks",
            "method" => "get",
            DataTypeInterface::STRUCTURE_TYPE_DTO => "task",
            DataTypeInterface::STRUCTURE_TYPE_QUERY => "find-by-criteria-task",
        ],
        "get-one" => [
            "route" => "/api/tasks/{id}",
            "method" => "get",
            DataTypeInterface::STRUCTURE_TYPE_DTO => "task",
            DataTypeInterface::STRUCTURE_TYPE_QUERY => "fetch-one-task",
        ],
    ],
    "user-commands" => [
        "create" => [
            "route" => "/api/users",
            "method" => "post",
            DataTypeInterface::STRUCTURE_TYPE_DTO => "user",
            DataTypeInterface::STRUCTURE_TYPE_COMMAND => "user-create",
        ],
    ],
    "user-queries" => [
        "get-all" => [
            "route" => "/api/users",
            "method" => "get",
            DataTypeInterface::STRUCTURE_TYPE_DTO => "user",
            DataTypeInterface::STRUCTURE_TYPE_QUERY => "find-by-criteria-user",
        ],
        "get-one" => [
            "route" => "/api/users/{id}",
            "method" => "get",
            DataTypeInterface::STRUCTURE_TYPE_DTO => "user",
            DataTypeInterface::STRUCTURE_TYPE_QUERY => "fetch-one-user",
        ],
    ],
];

// Структура сервисов для бизнес-логики
$serviceStructure = [
    "TaskService" => [
        "createTask" => [],
        "updateTaskStatus" => [],
        "assignTask" => [],
        "getTasks" => [],
        "getTaskById" => [],
    ],
    "UserService" => [
        "createUser" => [],
        "getUsers" => [],
        "getUserById" => [],
    ],
];

// Объединение всех структур в единую конфигурацию
$structure = [
    $mainDomainName => [
        DataTypeInterface::STRUCTURE_TYPE_ENTITY => $entityStructure,
        DataTypeInterface::STRUCTURE_TYPE_DTO => $dtoStructure,
        DataTypeInterface::STRUCTURE_TYPE_READ_MODEL => $readModelStructure,
        DataTypeInterface::STRUCTURE_TYPE_VALUE_OBJECT => $valueObjectStructure,
        DataTypeInterface::STRUCTURE_TYPE_SAGA => $sagaStructure,
        DataTypeInterface::STRUCTURE_TYPE_REST => $restStructure,
        DataTypeInterface::STRUCTURE_TYPE_SERVICE => $serviceStructure,
    ],
];

// Запуск генератора
$generatorProjectBuilder = new ProjectBuilder("/app/src", $domainNamespace, $structure);
$generatorProjectBuilder->generate();