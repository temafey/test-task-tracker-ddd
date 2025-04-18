# TaskTracker Microservice

A backend service built as part of a microservice architecture that contains task management business logic.

## Implementations

- [x] Environment in Docker
- [x] Command Bus, Event Bus
- [x] Event Store
- [x] Read Model
- [ ] Async Event subscribers

## Stack

- PHP 8.4
- PostgreSQL 17
- Docker

## Architectural Design

### Overview
The Task Tracker service follows a clean architecture pattern with a multi-layered approach that provides clear separation of responsibilities.

### Architectural Layers

#### 1. Presentation Layer
- **Responsibility**: Handle HTTP requests, format responses, validate input data
- **Components**:
    - Controllers for commands (task creation, status updates, assignments)
    - Controllers for queries (retrieving tasks and users)

#### 2. Application Layer
- **Responsibility**: Coordinate business logic execution, translate requests and responses
- **Components**:
    - Command Handlers (process domain commands)
    - Query Handlers (retrieve and format data)
    - DTOs (data transfer objects for API interaction)
    - Projectors (update read models based on events)

#### 3. Domain Layer
- **Responsibility**: Contains business logic and domain rules
- **Components**:
    - Entities (TaskEntity, UserEntity)
    - Value Objects (Task, User, Status, etc.)
    - Repository Interfaces
    - Domain Events (TaskCreatedEvent, TaskAssignedEvent, etc.)
    - Commands and Queries

#### 4. Infrastructure Layer
- **Responsibility**: Implement interactions with external systems (DB, messaging)
- **Components**:
    - Repository Implementations
    - Data Mappers
    - Storage implementations
    - Migrations

### Component Interactions

```
HTTP Request → Controller → Command/Query Bus → Handler → Repository → Entity/Read Model → Database
                                                        ↓
                                           Event Bus → Projector → Read Model Storage
```

## Design Patterns Applied

1. **Command Pattern / CQRS**
    - Separation of operations into commands and queries
    - Allows for optimizing read and write operations independently

2. **Repository Pattern**
    - Abstracts data access with interfaces
    - Hides storage implementation details

3. **Factory Pattern**
    - Creates complex objects consistently
    - Used for commands, queries, entities, and read models

4. **DTO Pattern**
    - Simplifies data transfer between layers
    - Provides a clear API contract

5. **Event Sourcing**
    - Uses events as the source of truth
    - Enables rebuilding state and audit capabilities

6. **Value Objects**
    - Encapsulates domain concepts (Title, Description, Status)
    - Ensures immutability and validation

7. **Projection Pattern**
    - Creates optimized read models from events
    - Improves query performance

## Project Structure

```
├── .docker/               # Docker configuration files
├── bootstrap/             # Application bootstrap files
├── config/                # Configuration files and services
│   ├── domains/           # Domain-specific configurations
│   ├── packages/          # Package configurations
│   └── services/          # Service definitions
├── public/                # Public entry point
├── src/                   # Application source code
│   ├── Kernel.php         # Application kernel
│   └── Task/              # Task domain
│       ├── Application/   # Application layer components
│       │   ├── CommandHandler/
│       │   ├── Dto/
│       │   ├── Factory/
│       │   ├── Processor/
│       │   ├── Projector/
│       │   ├── QueryHandler/
│       │   └── Saga/
│       ├── Domain/        # Domain layer components
│       │   ├── Command/
│       │   ├── Entity/
│       │   ├── Event/
│       │   ├── Factory/
│       │   ├── Query/
│       │   ├── ReadModel/
│       │   ├── Repository/
│       │   └── ValueObject/
│       ├── Infrastructure/ # Infrastructure layer components
│       │   ├── Migrations/
│       │   ├── Repository/
│       │   └── Service/
│       └── Presentation/   # Presentation layer components
│           ├── Cli/
│           └── Rest/
└── tests/                  # Test files
```

## Scalability & Extensibility Plan

The architecture supports future extensions:

### Adding Comments for Tasks
1. Create new entities and value objects for comments
2. Define commands, events, and handlers for comment operations
3. Extend API controllers and create appropriate repositories

### Supporting User Roles
1. Extend the user entity with role information
2. Implement access control mechanisms
3. Update APIs to consider roles during operations

### Database Persistence
The architecture already supports this through:
1. Repository abstractions with interfaces
2. Ready-to-use DBAL configurations and migrations
3. Switching from in-memory to persistent storage requires minimal changes

## API Documentation

Get documentation in Swagger format (open in browser):

`http://localhost/api/doc/`

Get documentation in a user-friendly format (open in browser):

`http://localhost/api/docs/`

Get API documentation in JSON format:

`http://localhost/api/doc.json`

Get API documentation in YAML format:

`http://localhost/api/doc.yaml`

## API Endpoints

### Tasks

Create a task:
```bash
curl --location --request POST 'http://localhost/api/tasks' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--form 'title="task 1"' \
--form 'description="task description 1"' \
--form 'status="todo"'
```

Update task status:
```bash
curl --location --request PUT 'http://localhost/api/tasks/{uuid}/status' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--data '{
  "status": "in_progress"
}'
```

Assign task to user:
```bash
curl --location --request PUT 'http://localhost/api/tasks/{uuid}/assign' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--data '{
  "assigneeId": "{userUuid}"
}'
```

Get all tasks:
```bash
curl --location 'http://localhost/api/tasks'
```

Get task by ID:
```bash
curl --location 'http://localhost/api/tasks/{uuid}'
```

Get tasks with filters:
```bash
curl --location 'http://localhost/api/tasks?assigneeId=1f01bca9-7e99-68ae-a1c2-decba94b787f&status=todo'
```

### Users

Create a user:
```bash
curl --location --request POST 'http://localhost/api/users' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--form 'name=\"user 1\"' \
--form 'email=\"user1@email.com\"'
```

Get users with filters:
```bash
curl --location 'http://localhost/api/users?name=user%201&email=user1%40email.com'
```

## Project Setup

Up new environment:

```bash
make install
```

See all make commands:

```bash
make help
```

Enter in PHP container:

```bash
make php-shell
```

Watch containers logs:

```bash
make logs
```
