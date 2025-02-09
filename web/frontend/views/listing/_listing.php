<div class="product-item card h-100 shadow-sm">
    <!-- Product Image -->
    <div class="card-img-top position-relative overflow-hidden">
        <?php use yii\helpers\Html;

        if ($model->card !== null && $model->card->image_url !== null): ?>
            <?= Html::img($model->card->image_url, [
                'alt' => Html::encode($model->card->name),
                'class' => 'img-fluid w-100 p-2',
            ]); ?>
        <?php else: ?>
            <div class="bg-light p-5 text-center">
                <p class="text-muted mb-0">Image not available</p>
            </div>
        <?php endif; ?>

        <!-- Action Buttons -->
        <div class="product-action">
            <?php if (Yii::$app->user->isGuest || $model->seller_id != Yii::$app->user->identity->id): ?>
                <?= Html::a('<i class="fas fa-shopping-cart"></i>',
                    ['/cart/add-to-cart', 'itemId' => $model->id, 'type' => 'listing'],
                    ['class' => 'btn btn-outline-dark btn-square']
                ) ?>
            <?php else: ?>
                <?= Html::a('<i class="fas fa-trash"></i>',
                    ['/listing/delete', 'id' => $model->id],
                    ['class' => 'btn btn-outline-danger btn-square',
                        'data-method' => 'post',
                        'data-confirm' => 'Are you sure you want to remove this item from your listings?']
                ) ?>
            <?php endif; ?>

            <?php if (!$model->card->isFavorited()): ?>
                <?= Html::a('<i class="far fa-heart"></i>',
                    ['/favorite/create', 'id' => $model->card_id],
                    ['class' => 'btn btn-outline-dark btn-square']
                ) ?>
            <?php else: ?>
                <?= Html::a('<i class="fas fa-heart-broken"></i>',
                    ['/favorite/remove', 'id' => $model->card_id],
                    ['class' => 'btn btn-outline-dark btn-square',
                        'disabled' => true,
                        'title' => 'This item is already in your favorites']
                ) ?>
            <?php endif; ?>

            <?= Html::a('<i class="fas fa-search"></i>',
                ['/listing/view', 'id' => $model->id],
                ['class' => 'btn btn-outline-dark btn-square']
            ) ?>
        </div>
    </div>

    <!-- Product Details -->
    <div class="card-body text-center">
        <?= Html::a(Html::encode($model->card->name),
            ['/listing/view', 'id' => $model->id],
            ['class' => 'h6 text-decoration-none text-truncate d-block',
                'title' => Html::encode($model->card->name)]
        ) ?>
        <h5 class="text-primary mt-2"><?= Yii::$app->formatter->asCurrency($model->price, 'EUR') ?></h5>
    </div>
</div>

<style>
    .product-item {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .product-action {
        position: absolute;
        top: 10px;
        right: -50px;
        opacity: 0;
        transition: all 0.3s ease;
    }

    .product-item:hover .product-action {
        right: 10px;
        opacity: 1;
    }

    .btn-square {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin-bottom: 5px;
        transition: all 0.3s ease;
    }

    .btn-square:hover {
        transform: scale(1.1);
    }
</style>