<?php

use Phoenix\Migration\AbstractMigration;

class DummyArticle3 extends AbstractMigration
{
    protected function up(): void
    {
        $this->execute("
            INSERT INTO article (id, title, description)
            VALUES (3, 'おいしい緑茶のいれかた', 'お茶といえば緑茶ですね！そこで今回は緑茶の入れ方を紹介します。');
        ");
        $this->execute("
            INSERT INTO article_parts (article_id, article_order, type, data) VALUES (3, 1, 1, '準備する物');
            INSERT INTO article_parts (article_id, article_order, type, data) VALUES (3, 2, 2, '熱湯と緑茶の茶葉が必要です！');

            INSERT INTO article_parts (article_id, article_order, type, data) VALUES (3, 3, 1, '作り方');
            INSERT INTO article_parts (article_id, article_order, type, data) VALUES (3, 4, 2, 'そのうち書きます。');

            INSERT INTO article_parts (article_id, article_order, type, data) VALUES (3, 5, 1, '麦茶とか紅茶の入れ方もどうぞ');
            INSERT INTO article_parts (article_id, article_order, type, data) VALUES (3, 6, 3, '1');
            INSERT INTO article_parts (article_id, article_order, type, data) VALUES (3, 7, 3, '2');
        ");
    }

    protected function down(): void
    {
        $this->delete('article', ['id' => 3]);
    }
}
