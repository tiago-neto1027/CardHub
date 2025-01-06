<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product_transactions}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%products}}`
 * - `{{%user}}`
 */
class m241029_223514_create_product_transactions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%product_transactions}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'buyer_id' => $this->integer()->notNull(),
            'date' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'status' => "ENUM('active','inactive') NOT NULL DEFAULT 'pending'",
        ], 'ENGINE=InnoDB');

        // creates index for column `product_id`
        $this->createIndex(
            '{{%idx-product_transactions-product_id}}',
            '{{%product_transactions}}',
            'product_id'
        );

        // add foreign key for table `{{%products}}`
        $this->addForeignKey(
            '{{%fk-product_transactions-product_id}}',
            '{{%product_transactions}}',
            'product_id',
            '{{%products}}',
            'id',
            'CASCADE'
        );

        // creates index for column `buyer_id`
        $this->createIndex(
            '{{%idx-product_transactions-buyer_id}}',
            '{{%product_transactions}}',
            'buyer_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-product_transactions-buyer_id}}',
            '{{%product_transactions}}',
            'buyer_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%products}}`
        $this->dropForeignKey(
            '{{%fk-product_transactions-product_id}}',
            '{{%product_transactions}}'
        );

        // drops index for column `product_id`
        $this->dropIndex(
            '{{%idx-product_transactions-product_id}}',
            '{{%product_transactions}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-product_transactions-buyer_id}}',
            '{{%product_transactions}}'
        );

        // drops index for column `buyer_id`
        $this->dropIndex(
            '{{%idx-product_transactions-buyer_id}}',
            '{{%product_transactions}}'
        );

        $this->dropTable('{{%product_transactions}}');
    }
}
