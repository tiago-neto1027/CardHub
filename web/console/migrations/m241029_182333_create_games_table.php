<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%games}}`.
 */
class m241029_182333_create_games_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%games}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
            'logo_url' => $this->string(255)->notNull(),
        ], 'ENGINE=InnoDB');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%games}}');
    }
}
