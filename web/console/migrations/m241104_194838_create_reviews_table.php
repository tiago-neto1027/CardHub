<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%reviews}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%card_transactions}}`
 */
class m241104_194838_create_reviews_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%reviews}}', [
            'id' => $this->primaryKey(),
            'transaction_id' => $this->integer()->notNull()->unique(),
            'rating' => "ENUM('1','2','3','4','5') NOT NULL",
            'comment' => $this->text(),
            'date' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB');

        // creates index for column `transaction_id`
        $this->createIndex(
            '{{%idx-reviews-transaction_id}}',
            '{{%reviews}}',
            'transaction_id'
        );

        // add foreign key for table `{{%card_transactions}}`
        $this->addForeignKey(
            '{{%fk-reviews-transaction_id}}',
            '{{%reviews}}',
            'transaction_id',
            '{{%card_transactions}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%card_transactions}}`
        $this->dropForeignKey(
            '{{%fk-reviews-transaction_id}}',
            '{{%reviews}}'
        );

        // drops index for column `transaction_id`
        $this->dropIndex(
            '{{%idx-reviews-transaction_id}}',
            '{{%reviews}}'
        );

        $this->dropTable('{{%reviews}}');
    }
}
