<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "cards".
 *
 * @property int $id
 * @property int $game_id
 * @property string $name
 * @property string $rarity
 * @property string $image_url
 * @property string $status
 * @property string|null $description
 * @property string $created_at
 * @property int|null $user_id
 *
 * @property Favorite[] $favorites
 * @property Game $game
 * @property Listing[] $listings
 * @property User $user
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
            [['game_id', 'user_id'], 'integer'],
            [['status'], 'string'],
            [['name'], 'string', 'max' => 100],
            [['rarity'], 'string', 'max' => 50],
            [['image_url', 'description'], 'string', 'max' => 255],
            [['game_id'], 'exist', 'skipOnError' => true, 'targetClass' => Game::class, 'targetAttribute' => ['game_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
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
            'status' => 'Status',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Gets query for [[Favorites]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFavorites()
    {
        return $this->hasMany(Favorite::class, ['card_id' => 'id']);
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

    /**
     * Gets query for [[Listings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getListings()
    {
        return $this->hasMany(Listing::class, ['card_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function getPendingCardCount()
    {
        return self::find()->where(['status' => 'inactive'])->count();
    }
}
