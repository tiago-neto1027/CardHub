<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%reports}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%card_transactions}}`
 */
class m241104_195209_create_reports_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%reports}}', [
            'id' => $this->primaryKey(),
            'transaction_id' => $this->integer()->notNull()->unique(),
            'comment' => $this->text()->notNull(),
            'date' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'image_url' => $this->text()->notNull(),
        ], 'ENGINE=InnoDB');

        // creates index for column `transaction_id`
        $this->createIndex(
            '{{%idx-reports-transaction_id}}',
            '{{%reports}}',
            'transaction_id'
        );

        // add foreign key for table `{{%card_transactions}}`
        $this->addForeignKey(
            '{{%fk-reports-transaction_id}}',
            '{{%reports}}',
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
            '{{%fk-reports-transaction_id}}',
            '{{%reports}}'
        );

        // drops index for column `transaction_id`
        $this->dropIndex(
            '{{%idx-reports-transaction_id}}',
            '{{%reports}}'
        );

        $this->dropTable('{{%reports}}');
    }
}
