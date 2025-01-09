<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

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
            [['name'], 'string', 'max' => 100],
            [['description'], 'string'],
            [['image_url'], 'string', 'max' => 255],
            [['game_id'], 'exist', 'skipOnError' => true, 'targetClass' => Game::class, 'targetAttribute' => ['game_id' => 'id']],
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

    public static function getStock($productId)
    {
        $product = self::findOne($productId);
        return $product ? $product->stock : 0;
    }

    public function getProductType()
    {
        return $this->hasOne(Game::class, ['id' => 'game_id']);
    }

    public static function getProductTypes(){
    return self::find()
               ->select('type')
               ->distinct()
               ->column();
    }

    public static function getSoldProductsCount()
    {
        $quantity = (new \yii\db\Query())
            ->from('invoice_lines')
            ->where(['not', ['product_transaction_id' => null]])
            ->sum('quantity');

        return $quantity ?: 0;
    }

    public static function getTotalRevenue()
    {
        $total = (new \yii\db\Query())
            ->from('invoice_lines')
            ->where(['not', ['product_transaction_id' => null]])
            ->sum('price');

        $total = $total ?: 0;

        return Yii::$app->formatter->asCurrency($total, 'USD');
    }

    public static function getLowStockCount()
    {
        return self::find()->where(['<', 'stock', 10])->count();
    }

    public static function getNoStockCount()
    {
        return self::find()->where(['stock' => 0])->count();
    }
}

