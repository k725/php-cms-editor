<?php

use Phoenix\Migration\AbstractMigration;

class ArticleParts extends AbstractMigration
{
    protected function up(): void
    {
        $this->execute("
            CREATE TABLE article_parts
            (
                article_id    INT NOT NULL COMMENT '記事のID',
                article_order INT NOT NULL COMMENT '記事の順番',
                type          INT NOT NULL COMMENT 'パーツのタイプ',
                data          TEXT NOT NULL COMMENT '内容',
                created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT '作成日時',
                updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT '更新日時',
                PRIMARY KEY (article_id, article_order),
                
                CONSTRAINT article_parts_article_id_fk
                    FOREIGN KEY (article_id) REFERENCES article (id)
                        ON UPDATE CASCADE ON DELETE CASCADE,

                CONSTRAINT article_parts_parts_type_id_fk
                    FOREIGN KEY (type) REFERENCES parts_type (id)
                        ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT '記事のパーツ';
        ");
    }

    protected function down(): void
    {
        $this->table('article_parts')
            ->drop();
    }
}
