<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cards}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%games}}`
 */
class m241029_183336_create_cards_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cards}}', [
            'id' => $this->primaryKey(),
            'game_id' => $this->integer()->notNull(),
            'name' => $this->string(100)->notNull(),
            'rarity' => $this->string(50)->notNull(),
            'image_url' => $this->string(255)->notNull(),
            'description' => $this->string(255),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB');

        // creates index for column `game_id`
        $this->createIndex(
            '{{%idx-cards-game_id}}',
            '{{%cards}}',
            'game_id'
        );

        // add foreign key for table `{{%games}}`
        $this->addForeignKey(
            '{{%fk-cards-game_id}}',
            '{{%cards}}',
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
            '{{%fk-cards-game_id}}',
            '{{%cards}}'
        );

        // drops index for column `game_id`
        $this->dropIndex(
            '{{%idx-cards-game_id}}',
            '{{%cards}}'
        );

        $this->dropTable('{{%cards}}');
    }
}