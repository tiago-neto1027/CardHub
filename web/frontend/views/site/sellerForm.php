<?php
use yii\helpers\Html;
?>

<div class="container my-5 text-light">
    <h3 class="text-primary">Important Notice for Sellers</h3>

    <p>
        By accepting to become a seller on our platform, you agree to comply with our marketplace guidelines.
        Failure to adhere to these rules may result in account suspension or permanent removal from our platform.
    </p>

    <p>As a seller, you must:</p>
    <ul>
        <li>Ensure all listed products comply with legal and ethical standards. The sale of prohibited, illegal, or inappropriate content is strictly forbidden.</li>
        <li>Provide accurate descriptions and truthful representations of your products or services. Misleading listings or false advertising will not be tolerated.</li>
        <li>Conduct all transactions in a fair and professional manner. Any attempts to scam, defraud, or deceive buyers will result in immediate action against your account.</li>
        <li>Respect intellectual property rights and avoid selling counterfeit or unauthorized goods.</li>
        <li>Maintain high customer service standards by responding to inquiries and fulfilling orders in a timely and professional manner.</li>
        <li>Adhere to all applicable laws and regulations related to online selling and consumer protection.</li>
    </ul>

    <p>
        By proceeding, you acknowledge and accept these conditions. Failure to comply may lead to penalties,
        including account suspension or legal action where applicable.
    </p>

    <?= Html::a('Yes, Become a Seller', ['site/become-seller','id' => $id], [
        'class' => 'btn btn-success text-light',
        'data' => [
            'confirm' => 'Are you sure you want to become a seller?',
            'method' => 'post',
        ],
    ]) ?>
</div>
