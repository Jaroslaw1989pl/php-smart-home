<?php

declare(strict_types = 1);

namespace src\controllers;

use app\Request;
use app\Response;
use app\Session;
use models\TokenModel;
use models\ValidationModel as Validator;


class AuthController extends _Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    // #[Post('/registration')]
    public function registration()
    {
        $isFormValid = true;
        $formErrors = [];

        try
        {
            $userEmail = Validator::input(Request::body()['userEmail'])->rules(
                Validator::RULE_REQUIRED, Validator::RULE_EMAIL
            );
        }
        catch (\Exception $exception)
        {
            $isFormValid = false;
            $formErrors['email'] = $exception->getMessage();
        }

        try
        {
            $userPass = Validator::input(Request::body()['userPass'])->rules(
                Validator::RULE_REQUIRED, [Validator::RULE_MIN_LENGTH, 8], [Validator::RULE_MAX_LENGTH, 32], Validator::RULE_PASSWORD
            );
        }
        catch (\Exception $exception)
        {
            $isFormValid = false;
            $formErrors['pass'] = $exception->getMessage();
        }

        try
        {
            $userPassConf = Validator::input(Request::body()['userPassConf'])->rules(
                Validator::RULE_REQUIRED, [Validator::RULE_EQUAL, Request::body()['userPass']]
            );
        }
        catch (\Exception $exception)
        {
            $isFormValid = false;
            $formErrors['passConf'] = $exception->getMessage();
        }

        if ($isFormValid)
        {
            $this->userModel->add(Request::body());
            Session::setRequestStatus(true);
        }
        else
        {
            Session::setInputs(Request::body());
            Session::setErrors($formErrors);
        }

        Response::redirect('/registration');
    }

    // #[Post('/login')]
    public function login()
    {
        try
        {
            if (strlen(Request::body()['userEmail']) === 0 && strlen(Request::body()['userPass']) === 0)
                throw new \Exception('Please enter your email address and password.', 422);
            if (strlen(Request::body()['userEmail']) === 0 || strlen(Request::body()['userPass']) === 0)
                throw new \Exception('Incorrect login or password.', 422);
            if (!filter_var(htmlspecialchars(Request::body()['userEmail']), FILTER_VALIDATE_EMAIL))
                throw new \Exception('Incorrect login or password.', 422);

            $user = $this->userModel->find(htmlspecialchars(Request::body()['userEmail']));

            if (empty($user))
                throw new \Exception('Incorrect login or password.', 422);
            if (!password_verify(Request::body()['userPass'], $user['pass']))
                throw new \Exception('Incorrect login or password.', 422);

            Session::setUserId($user['user_id']);
            Session::setRequestStatus(true);
            Response::redirect('/');
        }
        catch (\Exception $exception)
        {
            Session::setInputs(Request::body());
            Session::setErrors($exception->getMessage());
            Response::redirect('/login');
        }
    }

    // #[Post('/password-reset')]
    public function authenticationEmail(): void
    {
        try
        {
            if (!filter_var(htmlspecialchars(Request::body()['userEmail']), FILTER_VALIDATE_EMAIL))
                throw new \Exception('Incorrect email address.', 422);
            if (!$this->userModel->find(htmlspecialchars(Request::body()['userEmail'])))
                throw new \Exception('Email address not related with any existing account.', 422);

            $email = Request::body()['userEmail'];
            $token = TokenModel::create(Request::body()['userEmail']);

            TokenModel::save($token);

            // TODO: wysyłanie emaila w tle 
            shell_exec("php ".ROOT_DIR."/scripts/email.php $email $token > /dev/null &");

            Session::setRequestStatus(true);
        }
        catch (\Exception $exception)
        {
            Session::setInputs(Request::body());
            Session::setErrors($exception->getMessage());
        }
        finally
        {
            Response::redirect('/password-reset');
        }
    }

    // #[Post('/password-update')]
    public function passwordUpdate(): void
    {
        $isFormValid = true;
        $formErrors = [];

        try
        {
            if (strlen(htmlspecialchars(Request::body()['userPass'])) < 8)
                throw new \Exception('Password does not met requirements.', 422);
            if (!preg_match('/(?=.*[A-Z])(?=.*[a-z])/', htmlspecialchars(Request::body()['userPass'])))
                throw new \Exception('Password does not met requirements.', 422);
            if (!preg_match('/(?=.*[0-9_])/', htmlspecialchars(Request::body()['userPass'])))
                throw new \Exception('Password does not met requirements.', 422);
            if (preg_match('/[^\w]/', htmlspecialchars(Request::body()['userPass'])))
                throw new \Exception('Password does not met requirements.', 422);
        }
        catch (\Exception $exception)
        {
            $isFormValid = false;
            $formErrors['pass'] = $exception->getMessage();
        }

        try
        {
            if (strlen(htmlspecialchars(Request::body()['userPassConf'])) === 0)
                throw new \Exception('Please confirm password.', 422);
            if (htmlspecialchars(Request::body()['userPass']) !== htmlspecialchars(Request::body()['userPassConf']))
                throw new \Exception('Passwords are not the same.', 422);
        }
        catch (\Exception $exception)
        {
            $isFormValid = false;
            $formErrors['passConf'] = $exception->getMessage();
        }

        if ($isFormValid)
        {
            $email = hex2bin(explode('.', Request::body()['token'])[0]);
            $pass = Request::body()['userPass'];
            $this->userModel->setPassword($email, $pass);
            TokenModel::remove(Request::body()['token']);
            Session::setRequestStatus(true);

        }
        else
        {
            Session::setInputs(Request::body());
            Session::setErrors($formErrors);
        }

        Response::redirect();
    }

    // #[Post('/password-new')]
    public function passwordNew()
    {
        $isFormValid = true;
        $formErrors = [];
        $user = null;

        try
        {
            if (strlen(htmlspecialchars(Request::body()['userPassOld'])) === 0)
                throw new \Exception('Old password is a required.', 422);

            $user = $this->userModel->find($this->data['user']['email']);

            if (!password_verify(Request::body()['userPassOld'], $user['pass']))
                throw new \Exception('Incorrect password.', 422);
        }
        catch (\Exception $exception)
        {
            $isFormValid = false;
            $formErrors['passOld'] = $exception->getMessage();
        }

        try
        {
            if (strlen(htmlspecialchars(Request::body()['userPass'])) === 0)
                throw new \Exception('Password is a required.', 422);
            if (password_verify(Request::body()['userPass'], $user['pass']))
                throw new \Exception('New password can not be the same as actual password.', 422);
            if (strlen(htmlspecialchars(Request::body()['userPass'])) < 8)
                throw new \Exception('Password does not met requirements.', 422);
            if (!preg_match('/(?=.*[A-Z])(?=.*[a-z])/', htmlspecialchars(Request::body()['userPass'])))
                throw new \Exception('Password does not met requirements.', 422);
            if (!preg_match('/(?=.*[0-9_])/', htmlspecialchars(Request::body()['userPass'])))
                throw new \Exception('Password does not met requirements.', 422);
            if (preg_match('/[^\w]/', htmlspecialchars(Request::body()['userPass'])))
                throw new \Exception('Password does not met requirements.', 422);
        }
        catch (\Exception $exception)
        {
            $isFormValid = false;
            $formErrors['pass'] = $exception->getMessage();
        }

        if ($isFormValid)
        {
            $this->userModel->setPassword($this->data['user']['email'], Request::body()['userPass']);
            Session::setRequestStatus(true);
        }
        else
        {
            Session::setInputs(Request::body());
            Session::setErrors($formErrors);
        }

        Response::redirect();
    }

    // #[Post('/logout')]
    public function logout()
    {
        Session::clear();
        Response::redirect('/');
    }

    // #[Post('/delete')]
    public function delete()
    {
        $this->userModel->remove($this->data['user']['id'], $this->data['user']['email']);

        Session::clear();
        Response::redirect('/');
    }
}