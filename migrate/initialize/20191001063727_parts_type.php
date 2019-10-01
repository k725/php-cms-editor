<?php

use Phoenix\Migration\AbstractMigration;

class PartsType extends AbstractMigration
{
    protected function up(): void
    {
        $this->execute("
            CREATE TABLE parts_type
            (
                id   INT AUTO_INCREMENT COMMENT 'パーツタイプID' PRIMARY KEY,
                name VARCHAR(32) NOT NULL COMMENT 'パーツタイプ文字列'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT 'パーツタイプ';
        ");
        $this->execute("
            INSERT INTO parts_type (id, name) VALUES (1, 'heading');
            INSERT INTO parts_type (id, name) VALUES (2, 'text');
            INSERT INTO parts_type (id, name) VALUES (3, 'reference');
        ");
    }

    protected function down(): void
    {
        $this->table('parts_type')
            ->drop();
    }
}
