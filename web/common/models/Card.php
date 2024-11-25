<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cards".
 *
 * @property int $id
 * @property int $game_id
 * @property string $name
 * @property string $rarity
 * @property string $image_url
 * @property string|null $description
 * @property string $status
 * @property string $created_at
 *
 * @property Favorites[] $favorites
 * @property Games $game
 * @property Listings[] $listings
 */
class Card extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cards';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['game_id', 'name', 'rarity', 'image_url', 'status'], 'required'],
            [['game_id'], 'integer'],
            [['status'], 'string'],
            [['created_at'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['rarity'], 'string', 'max' => 50],
            [['image_url', 'description'], 'string', 'max' => 255],
            [['game_id'], 'exist', 'skipOnError' => true, 'targetClass' => Games::class, 'targetAttribute' => ['game_id' => 'id']],
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
            'rarity' => 'Rarity',
            'image_url' => 'Image Url',
            'description' => 'Description',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Favorites]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFavorites()
    {
        return $this->hasMany(Favorites::class, ['card_id' => 'id']);
    }

    /**
     * Gets query for [[Game]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGame()
    {
        return $this->hasOne(Games::class, ['id' => 'game_id']);
    }

    /**
     * Gets query for [[Listings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getListings()
    {
        return $this->hasMany(Listings::class, ['card_id' => 'id']);
    }
}
