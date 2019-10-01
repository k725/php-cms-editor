<?php

use Phoenix\Migration\AbstractMigration;

class DummyArticle1 extends AbstractMigration
{
    protected function up(): void
    {
        $this->execute("
            INSERT INTO article (id, title, description)
            VALUES (1, 'おいしい麦茶のいれかた', '夏といえば麦茶ですね！そこで今回は麦茶の入れ方を紹介します。');
        ");
        $this->execute("
            INSERT INTO article_parts (article_id, article_order, type, data) VALUES (1, 1, 1, '準備する物');
            INSERT INTO article_parts (article_id, article_order, type, data) VALUES (1, 2, 2, '熱湯と麦茶のパックが必要です！');

            INSERT INTO article_parts (article_id, article_order, type, data) VALUES (1, 3, 1, '作り方');
            INSERT INTO article_parts (article_id, article_order, type, data) VALUES (1, 4, 2, 'そのうち書きます。');
        ");
    }

    protected function down(): void
    {
        $this->delete('article', ['id' => 1]);
    }
}
