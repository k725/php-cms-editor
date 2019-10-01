<?php

use Phoenix\Migration\AbstractMigration;

class DummyArticle2 extends AbstractMigration
{
    protected function up(): void
    {
        $this->execute("
            INSERT INTO article (id, title, description)
            VALUES (2, 'おいしい紅茶のいれかた', 'お茶といえば紅茶ですね！そこで今回は紅茶の入れ方を紹介します。');
        ");
        $this->execute("
            INSERT INTO article_parts (article_id, article_order, type, data) VALUES (2, 1, 1, '準備する物');
            INSERT INTO article_parts (article_id, article_order, type, data) VALUES (2, 2, 2, '熱湯と紅茶の茶葉が必要です！');

            INSERT INTO article_parts (article_id, article_order, type, data) VALUES (2, 3, 1, '作り方');
            INSERT INTO article_parts (article_id, article_order, type, data) VALUES (2, 4, 2, 'そのうち書きます。');

            INSERT INTO article_parts (article_id, article_order, type, data) VALUES (2, 5, 1, '麦茶の入れ方もどうぞ');
            INSERT INTO article_parts (article_id, article_order, type, data) VALUES (2, 6, 3, '1');
        ");
    }

    protected function down(): void
    {
        $this->delete('article', ['id' => 2]);
    }
}
