<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            [['email'], 'email'],
            [['username'], 'string', 'max' => 255],
            [['status'], 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByUsernameAll($username)
    {
        return static::findOne(['username' => $username]);
    }


    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByEmailAll($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }




    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function checkForRole($role){
        $roles = Yii::$app->authManager->getRolesByUser(($this->id));
        if (isset($roles[$role])){
            return true;
        }else{
            return false;
        }
    }

    public function getRole(){
        $roles = Yii::$app->authManager->getRolesByUser(($this->id));
        return reset($roles) ? reset($roles)->name :null;
    }

    public function setRole($id, $roleName)
    {
        $auth = Yii::$app->authManager;

        $role = $auth->getRole($roleName);

        if ($role) {
            $auth->revokeAll($id);

            $auth->assign($role, $id);
        } else {
            throw new \Exception("The role '{$roleName}' does not exist.");
        }
    }


    public function getListings()
    {
        return \common\models\Listing::find()
            ->where(['seller_id' => $this->id])
            ->andWhere(['status' => 'active'])
            ->count();
    }

    public function getListingsCount()
    {
        return \common\models\Listing::find()
            ->where(['seller_id' => $this->id])
            ->count();
    }

    public function getSoldListingsCount()
    {
        return \common\models\Listing::find()
            ->where(['seller_id' => $this->id])
            ->andWhere(['status' => 'sold'])
            ->count();
    }

    public function getRevenue()
    {
        $revenue = \common\models\Listing::find()
            ->where(['seller_id' => $this->id])
            ->andWhere(['status' => 'sold'])
            ->sum('price');

        return $revenue !== null ? number_format($revenue, 2) : '0.00';
    }

    public function getCartItemCount()
    {
        $userId = Yii::$app->user->id;
        $cartItems = Yii::$app->cache->get('cart_' . $userId) ?: [];
        $count = 0;

        foreach ($cartItems as $item) {
            $count ++;
        }

        return $count;
    }

    /**
     * Get the user's favorites
     */
    public function getFavorites()
    {
        return $this->hasMany(Favorite::class, ['user_id' => 'id']);
    }

    /**
     * Get the count of the user's favorite items (cards).
     * @return int
     */
    public function getFavoritesItemCount()
    {
        return $this->getFavorites()->count();
    }

    public static function getRegisteredUsersCount()
    {
        return self::find()->count();
    }

    public function hasActivePunishment()
    {
        return Punishment::find()
            ->where(['user_id' => $this->id])
            ->andWhere(['or', ['end_date' => null], ['>', 'end_date', date('Y-m-d H:i:s')]])
            ->exists();
    }
}
