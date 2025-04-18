TaskTracker microservice
============
 Backend service. Part of the microservice core that contains business logic.

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

## Requests

Get documentation in beauty Swagger format (open in browser).

`http://localhost/api/doc/`

Get documentation in beauty format (open in browser).

`http://localhost/api/docs/`

Get api documentation in JSON format.

`http://localhost/api/doc.json`

Get api documentation in yaml format.

`http://localhost/api/doc.yaml`


Curl rest POST '/api/tasks' request

`
curl --location --request POST 'http://localhost/api/tasks' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--form 'title="task 1"' \
--form 'description="task description 1"' \
--form 'status="todo"'
`

Curl rest POST '/api/users' request

`
curl --location --request POST 'http://localhost/api/users' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--form 'name=\"user 1\"' \
--form 'email=\"user1@email.com\"'
`

Curl rest PUT 'api/tasks/{uuid}/status' request

`
curl --location --request PUT 'http://localhost/api/tasks/{uuid}/status' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--data '{
  "status": "in_progress"
}'
`

Curl rest PUT '/api/tasks/{uuid}/assign' request

`
curl --location --request PUT 'http://localhost/api/tasks/{uuid}/assign' \
--header 'Content-Type: application/x-www-form-urlencoded' \
--data '{
  "assigneeId": "{userUuid}"
}'
`


Curl rest GET '/api/tasks' request

`
curl --location 'http://localhost/api/tasks'
`

`
curl --location 'http://localhost/api/tasks/{uuid}'
`

`
curl --location 'http://localhost/api/tasks?assigneeId=1f01bca9-7e99-68ae-a1c2-decba94b787f&status=todo'
`

Curl rest GET '/api/users' request

`
curl --location 'http://localhost/api/users?name=user%201&email=user1%40email.com'
`

## Project Setup

Up new environment:

`make install`

See all make commands

`make help`


Enter in php container:

`make php-shell`

Watch containers logs

`make logs`

### Implementation

```

```
