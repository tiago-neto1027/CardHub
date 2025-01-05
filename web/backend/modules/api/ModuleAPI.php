<?php

namespace backend\modules\api;

use common\models\User;
use yii\filters\auth\HttpBasicAuth;

/**
 * api module definition class
 */
class ModuleAPI extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\api\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        \Yii::$app->user->enableSession = false;
        // custom initialization code goes here
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        if (!in_array(\Yii::$app->controller->id, ['favorite', 'listing'])) {
            $behaviors['authenticator'] = [
                'class' => HttpBasicAuth::className(),
                'auth' => [$this, 'auth']
            ];
        }
        return $behaviors;
    }

    public function auth($username, $password)
    {
        $user = User::findByUsername($username);
        if ($user && $user->validatePassword($password)) {
            return $user;
        }
        throw new \yii\web\ForbiddenHttpException('No authentication');
    }
}