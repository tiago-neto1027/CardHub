<?php

namespace common\tests\unit\models;

use backend\models\SignupForm;
use common\models\User;
use common\tests\UnitTester;
use Yii;

class SignupFormTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    //Test signup with valid data
    public function testSignupWithValidData()
    {
        $signupForm = new SignupForm();
        $signupForm->username = 'testuser';
        $signupForm->email = 'testuser@example.com';
        $signupForm->password = 'Password123';

        if (!$signupForm->validate()) {
            var_dump($signupForm->errors);
            return;
        }
        $user = $signupForm->signup();
        $this->assertInstanceOf(User::class, $user, 'Signup should return a User instance.');
        $this->assertEquals('testuser', $user->username, 'Username should match input.');
        $this->assertEquals('testuser@example.com', $user->email, 'Email should match input.');
        $this->assertTrue($user->validatePassword('Password123'), 'Password should be hashed and validated correctly.');
        $this->assertEquals('buyer', Yii::$app->authManager->getRolesByUser($user->id)['buyer']->name, 'Default role should be assigned.');
    }

    // Test signup with invalid data
    public function testSignupFailsWithInvalidEmail()
    {
        $signupForm = new SignupForm();
        $signupForm->username = 'testuser';
        $signupForm->email = 'invalid-email';
        $signupForm->password = 'StrongPassword123';

        $user = $signupForm->signup();

        $this->assertNull($user, 'Signup should return null for invalid email.');
        $this->assertArrayHasKey('email', $signupForm->getErrors(), 'Email validation should fail.');
    }

    // Test signup without password
    public function testSignupFailsWithMissingPassword()
    {
        $signupForm = new SignupForm();
        $signupForm->username = 'testuser';
        $signupForm->email = 'testuser@example.com';
        $signupForm->password = '';

        $user = $signupForm->signup();

        $this->assertNull($user, 'Signup should return null for missing password.');
        $this->assertArrayHasKey('password', $signupForm->getErrors(), 'Password validation should fail.');
    }

    // Test signup with duplicate email
    public function testSignupFailsWithDuplicateEmail()
    {
        // Creating the first user
        $existingUser = new User();
        $existingUser->username = 'existinguser';
        $existingUser->email = 'duplicate@example.com';
        $existingUser->setPassword('Password123');
        $existingUser->generateAuthKey();
        $existingUser->status = 10;
        $existingUser->save();

        // Attempting to sign-up with an existing email
        $signupForm = new SignupForm();
        $signupForm->username = 'newuser';
        $signupForm->email = 'duplicate@example.com';
        $signupForm->password = 'Password123';

        $user = $signupForm->signup();

        $this->assertNull($user, 'Signup should return null for duplicate email.');
        $this->assertArrayHasKey('email', $signupForm->getErrors(), 'Email validation should fail for duplicate email.');
    }
}
