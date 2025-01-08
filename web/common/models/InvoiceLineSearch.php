<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\InvoiceLine;

/**
 * InvoiceLineSearch represents the model behind the search form of `common\models\InvoiceLine`.
 */
class InvoiceLineSearch extends InvoiceLine
{
    public $start_date;
    public $end_date;
    public $status_filter;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'invoice_id', 'quantity', 'card_transaction_id', 'product_transaction_id'], 'integer'],
            [['price'], 'number'],
            [['product_name', 'start_date', 'end_date', 'status_filter'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $type = null)
    {
        $query = InvoiceLine::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'product_name',
                    'price',
                    'quantity',
                    'date',
                    'status',
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'invoice_id' => $this->invoice_id,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'card_transaction_id' => $this->card_transaction_id,
            'product_transaction_id' => $this->product_transaction_id,
        ]);

        $query->andFilterWhere(['like', 'product_name', $this->product_name]);

        //Checks the type and renders just the transactions of that type
        if ($type === 'cards') {
            $query->joinWith('cardTransaction');
            $query->andWhere(['IS NOT', 'card_transaction_id', null]);
            if ($this->status_filter) {
                $query->andWhere(['card_transactions.status' => $this->status_filter]);
            }
            if ($this->start_date && $this->end_date) {
                $query->andWhere(['between', 'card_transactions.date', $this->start_date, $this->end_date . ' 23:59:59']);
            } elseif ($this->start_date) {
                $query->andWhere(['>=', 'card_transactions.date', $this->start_date]);
            } elseif ($this->end_date) {
                $query->andWhere(['<=', 'card_transactions.date', $this->end_date . ' 23:59:59']);
            }
        } elseif ($type === 'products') {
            $query->joinWith('productTransaction');
            $query->andWhere(['IS NOT', 'product_transaction_id', null]);
            if ($this->status_filter) {
                $query->andWhere(['product_transactions.status' => $this->status_filter]);
            }
            if ($this->start_date && $this->end_date) {
                $query->andWhere(['between', 'product_transactions.date', $this->start_date, $this->end_date . ' 23:59:59']);
            } elseif ($this->start_date) {
                $query->andWhere(['>=', 'product_transactions.date', $this->start_date]);
            } elseif ($this->end_date) {
                $query->andWhere(['<=', 'product_transactions.date', $this->end_date . ' 23:59:59']);
            }
        }

        return $dataProvider;
    }
}
