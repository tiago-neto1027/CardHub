<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property int $game_id
 * @property string $name
 * @property float $price
 * @property int $stock
 * @property string $status
 * @property string $image_url
 * @property string $type
 * @property string|null $description
 * @property string $created_at
 *
 * @property Game $game
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['game_id', 'name', 'price', 'stock', 'image_url', 'type'], 'required'],
            [['game_id', 'stock'], 'integer'],
            [['price'], 'number'],
            [['status', 'type'], 'string'],
            [['created_at'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['image_url', 'description'], 'string', 'max' => 255],
            [['game_id'], 'exist', 'skipOnError' => true, 'targetClass' => Game::class, 'targetAttribute' => ['game_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'game_id' => 'Game ID',
            'name' => 'Name',
            'price' => 'Price',
            'stock' => 'Stock',
            'status' => 'Status',
            'image_url' => 'Image Url',
            'type' => 'Type',
            'description' => 'Description',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Game]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGame()
    {
        return $this->hasOne(Game::class, ['id' => 'game_id']);
    }
}
