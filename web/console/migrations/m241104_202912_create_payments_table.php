<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%payments}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m241104_202912_create_payments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payments}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'payment_method' => "ENUM('MbWay','PayPal') NOT NULL",
            'status' => "ENUM('Pending','Canceled', 'Completed') NOT NULL DEFAULT 'Pending'",
            'total' => $this->float()->notNull(),
            'date' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ], 'ENGINE=InnoDB');

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-payments-user_id}}',
            '{{%payments}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-payments-user_id}}',
            '{{%payments}}',
            'user_id',
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
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-payments-user_id}}',
            '{{%payments}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-payments-user_id}}',
            '{{%payments}}'
        );

        $this->dropTable('{{%payments}}');
    }
}
