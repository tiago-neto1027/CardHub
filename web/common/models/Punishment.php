<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "punishments".
 *
 * @property int $id
 * @property int $user_id
 * @property int $admin_id
 * @property string $reason
 * @property string $start_date
 * @property string|null $end_date
 * @property string $created_at
 * @property string $updated_at
 */
class Punishment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'punishments';
    }


    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new \yii\db\Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'admin_id', 'reason', 'start_date'], 'required'],
            [['user_id', 'admin_id'], 'integer'],
            [['start_date', 'end_date',], 'date', 'format' => 'php:Y-m-d'],
            [['reason'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'admin_id' => 'Admin ID',
            'reason' => 'Reason',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getAdmin()
    {
        return $this->hasOne(User::class, ['id' => 'admin_id']);
    }
}
