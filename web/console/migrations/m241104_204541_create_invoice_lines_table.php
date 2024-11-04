<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%invoice_lines}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%invoices}}`
 * - `{{%card_transactions}}`
 * - `{{%product_transactions}}`
 */
class m241104_204541_create_invoice_lines_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%invoice_lines}}', [
            'id' => $this->primaryKey(),
            'invoice_id' => $this->integer()->notNull(),
            'price' => $this->float()->notNull(),
            'product_name' => $this->text()->notNull(),
            'quantity' => $this->integer()->notNull(),
            'card_transaction_id' => $this->integer()->unique(),
            'product_transaction_id' => $this->integer()->unique(),
        ], 'ENGINE=InnoDB');

        // creates index for column `invoice_id`
        $this->createIndex(
            '{{%idx-invoice_lines-invoice_id}}',
            '{{%invoice_lines}}',
            'invoice_id'
        );

        // add foreign key for table `{{%invoices}}`
        $this->addForeignKey(
            '{{%fk-invoice_lines-invoice_id}}',
            '{{%invoice_lines}}',
            'invoice_id',
            '{{%invoices}}',
            'id',
            'CASCADE'
        );

        // creates index for column `card_transaction_id`
        $this->createIndex(
            '{{%idx-invoice_lines-card_transaction_id}}',
            '{{%invoice_lines}}',
            'card_transaction_id'
        );

        // add foreign key for table `{{%card_transactions}}`
        $this->addForeignKey(
            '{{%fk-invoice_lines-card_transaction_id}}',
            '{{%invoice_lines}}',
            'card_transaction_id',
            '{{%card_transactions}}',
            'id',
            'CASCADE'
        );

        // creates index for column `product_transaction_id`
        $this->createIndex(
            '{{%idx-invoice_lines-product_transaction_id}}',
            '{{%invoice_lines}}',
            'product_transaction_id'
        );

        // add foreign key for table `{{%product_transactions}}`
        $this->addForeignKey(
            '{{%fk-invoice_lines-product_transaction_id}}',
            '{{%invoice_lines}}',
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
        // drops foreign key for table `{{%invoices}}`
        $this->dropForeignKey(
            '{{%fk-invoice_lines-invoice_id}}',
            '{{%invoice_lines}}'
        );

        // drops index for column `invoice_id`
        $this->dropIndex(
            '{{%idx-invoice_lines-invoice_id}}',
            '{{%invoice_lines}}'
        );

        // drops foreign key for table `{{%card_transactions}}`
        $this->dropForeignKey(
            '{{%fk-invoice_lines-card_transaction_id}}',
            '{{%invoice_lines}}'
        );

        // drops index for column `card_transaction_id`
        $this->dropIndex(
            '{{%idx-invoice_lines-card_transaction_id}}',
            '{{%invoice_lines}}'
        );

        // drops foreign key for table `{{%product_transactions}}`
        $this->dropForeignKey(
            '{{%fk-invoice_lines-product_transaction_id}}',
            '{{%invoice_lines}}'
        );

        // drops index for column `product_transaction_id`
        $this->dropIndex(
            '{{%idx-invoice_lines-product_transaction_id}}',
            '{{%invoice_lines}}'
        );

        $this->dropTable('{{%invoice_lines}}');
    }
}
