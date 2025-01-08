<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%punishments}}`.
 */
class m250108_141603_create_punishments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%punishments}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'admin_id' => $this->integer()->notNull(),
            'reason' => $this->string(255)->notNull(),
            'start_date' => $this->dateTime()->notNull(),
            'end_date' => $this->dateTime()->null(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        // Add foreign key for `user_id`
        $this->addForeignKey(
            'fk-punishments-user_id',
            '{{%punishments}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // Add foreign key for `admin_id`
        $this->addForeignKey(
            'fk-punishments-admin_id',
            '{{%punishments}}',
            'admin_id',
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
        // Drop foreign keys first
        $this->dropForeignKey('fk-punishments-user_id', '{{%punishments}}');
        $this->dropForeignKey('fk-punishments-admin_id', '{{%punishments}}');

        // Drop the table
        $this->dropTable('{{%punishments}}');
    }
}
