<?php

use Phoenix\Migration\AbstractMigration;

class Article extends AbstractMigration
{
    protected function up(): void
    {
        $this->execute("
            CREATE TABLE article
            (
                id          INT AUTO_INCREMENT PRIMARY KEY COMMENT '記事ID',
                title       TEXT NOT NULL COMMENT '記事のタイトル',
                description TEXT NOT NULL COMMENT '記事の説明',
                created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT '作成日時',
                updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT '更新日時'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT '記事';
        ");
    }

    protected function down(): void
    {
        $this->table('article')
            ->drop();
    }
}
