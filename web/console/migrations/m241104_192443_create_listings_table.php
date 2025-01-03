<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%listings}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%cards}}`
 */
class m241104_192443_create_listings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%listings}}', [
            'id' => $this->primaryKey(),
            'seller_id' => $this->integer()->notNull(),
            'card_id' => $this->integer()->notNull(),
            'price' => $this->float()->notNull(),
            'condition' => "ENUM('Brand new', 'Very good', 'Good', 'Played', 'Poor', 'Damaged') NOT NULL",
            'status' => "ENUM('active','inactive','sold') NOT NULL DEFAULT 'active'",
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], 'ENGINE=InnoDB');

        // creates index for column `seller_id`
        $this->createIndex(
            '{{%idx-listings-seller_id}}',
            '{{%listings}}',
            'seller_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-listings-seller_id}}',
            '{{%listings}}',
            'seller_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `card_id`
        $this->createIndex(
            '{{%idx-listings-card_id}}',
            '{{%listings}}',
            'card_id'
        );

        // add foreign key for table `{{%cards}}`
        $this->addForeignKey(
            '{{%fk-listings-card_id}}',
            '{{%listings}}',
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
            '{{%fk-listings-seller_id}}',
            '{{%listings}}'
        );

        // drops index for column `seller_id`
        $this->dropIndex(
            '{{%idx-listings-seller_id}}',
            '{{%listings}}'
        );

        // drops foreign key for table `{{%cards}}`
        $this->dropForeignKey(
            '{{%fk-listings-card_id}}',
            '{{%listings}}'
        );

        // drops index for column `card_id`
        $this->dropIndex(
            '{{%idx-listings-card_id}}',
            '{{%listings}}'
        );

        $this->dropTable('{{%listings}}');
    }
}
