<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\models\User;

    /**
     * Gets user from id
     */
    function getUser($id)
    {
        $user = User::findOne($id);
        if ($user === null) {
            throw new NotFoundHttpException('User not found.');
        }
        return $user;
    }

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
        if (Yii::$app->user->id != $id) {
            return $this->goHome();
        }
        $user = getUser($id);
        return $this->render('details', [
            'user' => $user,
        ]);
    }

    public function actionChangeEmailForm($id)
    {
        if (Yii::$app->user->id != $id) {
            return $this->goHome();
        }
        $user = getUser($id);
        return $this->render('changeEmailForm', [
            'user' => $user,
        ]);
    }

    public function actionChangeUsernameForm($id)
    {
        if (Yii::$app->user->id != $id) {
            return $this->goHome();
        }
        $user = getUser($id);
        return $this->render('changeUsernameForm', [
            'user' => $user,
        ]);
    }

    public function actionChangePasswordForm($id)
    {
        if (Yii::$app->user->id != $id) {
            return $this->goHome();
        }
        $user = getUser($id);
        return $this->render('changePasswordForm', [
            'user' => $user,
        ]);
    }

    public function actionChangeEmail($id)
    {
        $user = getUser($id);

        // Handle the form submission
        if (Yii::$app->request->isPost) {
            // Get the new email from the POST data
            $newEmail = Yii::$app->request->post('newEmail'); // Access the 'newEmail' field

            // Perform the email validation
            if (filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
                Yii::$app->session->setFlash('success', 'The new email is valid!');

                // Optionally, update the user email if you wish to save it
                $user->email = $newEmail;
                if ($user->save()) {
                    Yii::$app->session->setFlash('success', 'Your email has been updated successfully.');
                } else {
                    Yii::$app->session->setFlash('error', 'There was an error updating your email.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'The new email is not valid.');
            }
        }
        return $this->render('/detail/details', ['user' => $user]);
    }

    public function actionChangeUsername($id)
    {
        $user = getUser($id);

        if (Yii::$app->request->isPost) {
            $newUsername = Yii::$app->request->post('newUsername');

            // Perform the email validation
            if ($newUsername != null) {
                Yii::$app->session->setFlash('success', 'The new username is valid!');

                // Optionally, update the user email if you wish to save it
                $user->username = $newUsername;
                if ($user->save()) {
                    Yii::$app->session->setFlash('success', 'Your username has been updated successfully.');
                } else {
                    Yii::$app->session->setFlash('error', 'There was an error updating your username.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'The new username is not valid.');
            }
        }
        return $this->render('/detail/details', ['user' => $user]);
    }

    public function actionChangePassword($id)
    {
        $user = getUser($id);

        if (Yii::$app->request->isPost) {
            $newPassword = Yii::$app->request->post('newPassword');
            $confirmPassword = Yii::$app->request->post('confirmPassword');


            if ($newPassword != null && $newPassword == $confirmPassword) {
                Yii::$app->session->setFlash('success', 'The new password is valid!');

                // Optionally, update the user email if you wish to save it
                $user->setPassword($newPassword);
                if ($user->save()) {
                    Yii::$app->session->setFlash('success', 'Your password has been updated successfully.');
                } else {
                    Yii::$app->session->setFlash('error', 'There was an error updating your password.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'The passwords dont match.');
                return $this->render('/detail/changePasswordForm', ['user' => $user]);
            }
        }
        return $this->render('/detail/details', ['user' => $user]);
    }

}
