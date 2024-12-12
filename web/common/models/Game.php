<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "games".
 *
 * @property int $id
 * @property string $name
 * @property string $logo_url
 *
 * @property Card[] $cards
 * @property Product[] $products
 */
class Game extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'games';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'logo_url'], 'required'],
            [['name'], 'string', 'max' => 50],
            [['logo_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'logo_url' => 'Logo Url',
        ];
    }

    /**
     * Gets query for [[Cards]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCards()
    {
        return $this->hasMany(Card::class, ['game_id' => 'id']);
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::class, ['game_id' => 'id']);
    }

    public static function getAllGames()
    {
        return self::find()->all();
    }
}
