<?php

/** @var \yii\web\View $this */
/** @var string $content */

use frontend\assets\AppAsset;
use yii\bootstrap5\Html;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<main role="main" class="flex-shrink-0">
    <div>
        <?= $this->render('header') ?>
        <div class="container-fluid">
            <?php foreach (Yii::$app->session->getAllFlashes() as $type => $message): ?>
                <div class="alert alert-<?= $type ?> alert-dismissible fade show mt-3" role="alert">
                    <?= $message ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endforeach; ?>

            <?= $content ?>
        </div>
    </div>
</main>

<?= $this->render('footer') ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var alertList = document.querySelectorAll('.alert');
        alertList.forEach(function(alert) {
            var closeButton = alert.querySelector('.btn-close');
            if (closeButton) {
                closeButton.addEventListener('click', function() {
                    alert.classList.remove('show');
                    setTimeout(function() { alert.remove(); }, 300);
                });
            }
        });
    });
</script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();