<?php

namespace frontend\controllers;

use yii\web\Controller;

class DetailController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Displays user account.
     *
     * @return mixed
     */
    public function actionDetails($id)
    {
        $user = \common\models\User::findOne($id);
        return $this->render('details', [
            'user' => $user,
        ]);
    }

    public function actionResetEmailForm($id)
    {
        $user = \common\models\User::findOne($id);
        return $this->render('resetEmailForm', [
            'user' => $user,
        ]);
    }

    public function actionResetEmail($id)
    {
        $user = \common\models\User::findOne($id);

        // Handling form submission
        if ($user->load(Yii::$app->request->post()) && $user->validate()) {
            // If the new email is valid, update the user's email
            $user->email = $user->newEmail;

            // Save the updated user model
            if ($user->save()) {
                Yii::$app->session->setFlash('success', 'Your email has been updated successfully.');
                return $this->redirect(['site/account']);
            } else {
                Yii::$app->session->setFlash('error', 'There was an error updating your email.');
            }
        }

        return $this->render('resetEmail', ['user' => $user]);
    }


}
