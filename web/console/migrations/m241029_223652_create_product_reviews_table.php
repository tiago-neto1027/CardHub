<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product_reviews}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%product_transactions}}`
 */
class m241029_223652_create_product_reviews_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%product_reviews}}', [
            'id' => $this->primaryKey(),
            'product_transaction_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'buyer_id' => $this->integer()->notNull(),
            'comment' => $this->text(),
            'rating' => "ENUM('1','2','3','4','5') NOT NULL",
            'date' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB');

        // creates index for column `product_transaction_id`
        $this->createIndex(
            '{{%idx-product_reviews-product_transaction_id}}',
            '{{%product_reviews}}',
            'product_transaction_id'
        );

        // add foreign key for table `{{%product_transactions}}`
        $this->addForeignKey(
            '{{%fk-product_reviews-product_transaction_id}}',
            '{{%product_reviews}}',
            'product_transaction_id',
            '{{%product_transactions}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%product_transactions}}`
        $this->dropForeignKey(
            '{{%fk-product_reviews-product_transaction_id}}',
            '{{%product_reviews}}'
        );

        // drops index for column `product_transaction_id`
        $this->dropIndex(
            '{{%idx-product_reviews-product_transaction_id}}',
            '{{%product_reviews}}'
        );

        $this->dropTable('{{%product_reviews}}');
    }
}
