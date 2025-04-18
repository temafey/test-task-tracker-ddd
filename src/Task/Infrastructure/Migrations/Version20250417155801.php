<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
final class Version20250417155801 extends AbstractMigration
{
    /**
     * Migration description.
     */
    public function getDescription(): string
    {
        return 'Create tables tasks and users';
    }

    public function up(Schema $schema): void
    {
        // Создание таблицы task
        $this->addSql("CREATE TYPE task_status AS ENUM ('todo', 'in_progress', 'done')");

        $this->addSql('CREATE TABLE tasks (
            uuid UUID NOT NULL, 
            title VARCHAR(100) DEFAULT NULL, 
            description TEXT DEFAULT NULL, 
            status task_status NOT NULL DEFAULT \'todo\', 
            assignee_id UUID DEFAULT NULL, 
            created_at TIMESTAMP DEFAULT NULL, 
            updated_at TIMESTAMP DEFAULT NULL, 
            PRIMARY KEY(uuid)
        )');

        // Создание таблицы user
        $this->addSql('CREATE TABLE users (
            uuid UUID NOT NULL, 
            name VARCHAR(100) DEFAULT NULL, 
            email VARCHAR(100) DEFAULT NULL, 
            created_at TIMESTAMP DEFAULT NULL, 
            updated_at TIMESTAMP DEFAULT NULL, 
            PRIMARY KEY(uuid)
        )');

        $this->addSql('CREATE INDEX idx_task_status ON tasks (status)');
        $this->addSql('CREATE UNIQUE INDEX idx_user_email ON users (email)');
        $this->addSql('CREATE INDEX idx_task_assignee ON tasks (assignee_id)');
    }

    public function down(Schema $schema): void
    {
        // Удаление таблиц в обратном порядке
        $this->addSql('DROP TABLE tasks');
        $this->addSql('DROP TABLE users');
    }
}