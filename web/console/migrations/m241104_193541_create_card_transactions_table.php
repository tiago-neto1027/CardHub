<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%card_transactions}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%user}}`
 * - `{{%listings}}`
 */
class m241104_193541_create_card_transactions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%card_transactions}}', [
            'id' => $this->primaryKey(),
            'seller_id' => $this->integer()->notNull(),
            'buyer_id' => $this->integer()->notNull(),
            'listing_id' => $this->integer()->notNull(),
            'status' => "ENUM('active','inactive') NOT NULL DEFAULT 'active'",
            'date' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB');

        // creates index for column `seller_id`
        $this->createIndex(
            '{{%idx-card_transactions-seller_id}}',
            '{{%card_transactions}}',
            'seller_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-card_transactions-seller_id}}',
            '{{%card_transactions}}',
            'seller_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `buyer_id`
        $this->createIndex(
            '{{%idx-card_transactions-buyer_id}}',
            '{{%card_transactions}}',
            'buyer_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-card_transactions-buyer_id}}',
            '{{%card_transactions}}',
            'buyer_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `listing_id`
        $this->createIndex(
            '{{%idx-card_transactions-listing_id}}',
            '{{%card_transactions}}',
            'listing_id'
        );

        // add foreign key for table `{{%listings}}`
        $this->addForeignKey(
            '{{%fk-card_transactions-listing_id}}',
            '{{%card_transactions}}',
            'listing_id',
            '{{%listings}}',
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
            '{{%fk-card_transactions-seller_id}}',
            '{{%card_transactions}}'
        );

        // drops index for column `seller_id`
        $this->dropIndex(
            '{{%idx-card_transactions-seller_id}}',
            '{{%card_transactions}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-card_transactions-buyer_id}}',
            '{{%card_transactions}}'
        );

        // drops index for column `buyer_id`
        $this->dropIndex(
            '{{%idx-card_transactions-buyer_id}}',
            '{{%card_transactions}}'
        );

        // drops foreign key for table `{{%listings}}`
        $this->dropForeignKey(
            '{{%fk-card_transactions-listing_id}}',
            '{{%card_transactions}}'
        );

        // drops index for column `listing_id`
        $this->dropIndex(
            '{{%idx-card_transactions-listing_id}}',
            '{{%card_transactions}}'
        );

        $this->dropTable('{{%card_transactions}}');
    }
}
