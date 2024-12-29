<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%products}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%games}}`
 */
class m241029_190527_create_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%products}}', [
            'id' => $this->primaryKey(),
            'game_id' => $this->integer()->notNull(),
            'name' => $this->string(100)->notNull(),
            'price' => $this->decimal(10,2)->notNull(),
            'stock' => $this->integer(4)->notNull(),
            'status' => "ENUM('active','inactive') NOT NULL DEFAULT 'active'",
            'image_url' => $this->string(255)->notNull(),
            'type' => "ENUM('booster', 'sleeve', 'playmat', 'storage', 'guide', 'apparel') NOT NULL",
            'description' => $this->text(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], 'ENGINE=InnoDB');

        // creates index for column `game_id`
        $this->createIndex(
            '{{%idx-products-game_id}}',
            '{{%products}}',
            'game_id'
        );

        // add foreign key for table `{{%games}}`
        $this->addForeignKey(
            '{{%fk-products-game_id}}',
            '{{%products}}',
            'game_id',
            '{{%games}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%games}}`
        $this->dropForeignKey(
            '{{%fk-products-game_id}}',
            '{{%products}}'
        );

        // drops index for column `game_id`
        $this->dropIndex(
            '{{%idx-products-game_id}}',
            '{{%products}}'
        );

        $this->dropTable('{{%products}}');
    }
}
