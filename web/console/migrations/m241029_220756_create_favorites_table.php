<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%favorites}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%cards}}`
 */
class m241029_220756_create_favorites_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%favorites}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'card_id' => $this->integer()->notNull(),
        ], 'ENGINE=InnoDB');

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-favorites-user_id}}',
            '{{%favorites}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-favorites-user_id}}',
            '{{%favorites}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `card_id`
        $this->createIndex(
            '{{%idx-favorites-card_id}}',
            '{{%favorites}}',
            'card_id'
        );

        // add foreign key for table `{{%cards}}`
        $this->addForeignKey(
            '{{%fk-favorites-card_id}}',
            '{{%favorites}}',
            'card_id',
            '{{%cards}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-favorites-user_id}}',
            '{{%favorites}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-favorites-user_id}}',
            '{{%favorites}}'
        );

        // drops foreign key for table `{{%cards}}`
        $this->dropForeignKey(
            '{{%fk-favorites-card_id}}',
            '{{%favorites}}'
        );

        // drops index for column `card_id`
        $this->dropIndex(
            '{{%idx-favorites-card_id}}',
            '{{%favorites}}'
        );

        $this->dropTable('{{%favorites}}');
    }
}
