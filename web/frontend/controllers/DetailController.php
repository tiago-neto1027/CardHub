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
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => \yii\filters\AccessControl::class,
                    'only' => ['index'],
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['index'],
                            'roles' => ['seller','buyer'],
                        ]
                    ],
                ],
            ]
        );
    }
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
        $user = User::findOne($id);
        return $this->render('details', [
            'user' => $user,
        ]);
    }

    public function actionChangeEmailForm($id)
    {
        if (Yii::$app->user->id != $id) {
            return $this->goHome();
        }
        $user = User::findOne($id);
        return $this->render('changeEmailForm', [
            'user' => $user,
        ]);
    }

    public function actionChangeUsernameForm($id)
    {
        if (Yii::$app->user->id != $id) {
            return $this->goHome();
        }
        $user = User::findOne($id);
        return $this->render('changeUsernameForm', [
            'user' => $user,
        ]);
    }

    public function actionChangePasswordForm($id)
    {
        if (Yii::$app->user->id != $id) {
            return $this->goHome();
        }
        $user = User::findOne($id);
        return $this->render('changePasswordForm', [
            'user' => $user,
        ]);
    }

    public function actionChangeEmail($id)
    {
        $user = User::findOne($id);

        if (!$user) {
            Yii::$app->session->setFlash('error', 'User not found.');
            return $this->redirect(['site/index']);
        }

        // Handle the form submission
        if (Yii::$app->request->isPost) {
            // Get the new email from the POST data
            $newEmail = Yii::$app->request->post('newEmail');

            if (filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
                // Check if the new email is already taken
                if (User::find()->where(['email' => $newEmail])->exists()) {
                    Yii::$app->session->setFlash('error', 'This email is already in use.');
                } else {
                    // If email is valid and not in use, update it
                    $user->email = $newEmail;

                    // Save the updated email
                    if ($user->save()) {
                        Yii::$app->session->setFlash('success', 'Your email has been updated successfully.');
                    } else {
                        Yii::$app->session->setFlash('error', 'There was an error updating your email.');
                    }
                }
            } else {
                Yii::$app->session->setFlash('error', 'The new email is not valid.');
            }
        }

        // Render the view with the user data
        return $this->render('/detail/details', ['user' => $user]);
    }

    public function actionChangeUsername($id)
    {
        $user = getUser($id);

        if (Yii::$app->request->isPost) {
            $newUsername = Yii::$app->request->post('newUsername');

            // Perform the username validation
            if ($newUsername != null && strlen($newUsername) >= 3) {
                // Optionally check if the username is unique
                $existingUser = User::findOne(['username' => $newUsername]);
                if ($existingUser) {
                    Yii::$app->session->setFlash('error', 'The username is already taken.');
                } else {
                    Yii::$app->session->setFlash('success', 'The new username is valid!');

                    // Update the username if it is valid
                    $user->username = $newUsername;
                    if ($user->save()) {
                        Yii::$app->session->setFlash('success', 'Your username has been updated successfully.');
                    } else {
                        Yii::$app->session->setFlash('error', 'There was an error updating your username.');
                    }
                }
            } else {
                Yii::$app->session->setFlash('error', 'The new username must be at least 3 characters long.');
            }
        }

        return $this->render('/detail/details', ['user' => $user]);
    }


    public function actionChangePassword($id)
    {
        $user = User::findOne($id);

        if (!$user) {
            Yii::$app->session->setFlash('error', 'User not found.');
            return $this->redirect(['site/index']); // Redirect to a safe place, like the homepage
        }

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
