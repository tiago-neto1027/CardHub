<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%invoices}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%payments}}`
 */
class m241104_203535_create_invoices_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%invoices}}', [
            'id' => $this->primaryKey(),
            'client_id' => $this->integer()->notNull(),
            'payment_id' => $this->integer()->notNull()->unique(),
            'date' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB');

        // creates index for column `client_id`
        $this->createIndex(
            '{{%idx-invoices-client_id}}',
            '{{%invoices}}',
            'client_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-invoices-client_id}}',
            '{{%invoices}}',
            'client_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `payment_id`
        $this->createIndex(
            '{{%idx-invoices-payment_id}}',
            '{{%invoices}}',
            'payment_id'
        );

        // add foreign key for table `{{%payments}}`
        $this->addForeignKey(
            '{{%fk-invoices-payment_id}}',
            '{{%invoices}}',
            'payment_id',
            '{{%payments}}',
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
            '{{%fk-invoices-client_id}}',
            '{{%invoices}}'
        );

        // drops index for column `client_id`
        $this->dropIndex(
            '{{%idx-invoices-client_id}}',
            '{{%invoices}}'
        );

        // drops foreign key for table `{{%payments}}`
        $this->dropForeignKey(
            '{{%fk-invoices-payment_id}}',
            '{{%invoices}}'
        );

        // drops index for column `payment_id`
        $this->dropIndex(
            '{{%idx-invoices-payment_id}}',
            '{{%invoices}}'
        );

        $this->dropTable('{{%invoices}}');
    }
}
