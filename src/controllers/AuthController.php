<?php

declare(strict_types = 1);

namespace src\controllers;

use app\Request;
use app\Response;
use app\Session;
use src\models\schemas\Token;
use src\models\Validator;


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

        try {
            $userEmail = Validator::input(Request::body()['userEmail'])->rules(
                Validator::RULE_REQUIRED, Validator::RULE_EMAIL, [Validator::RULE_UNIQUENESS, [$this->user, "email"]]
            );
        } catch (\Exception $exception) {
            $isFormValid = false;
            $formErrors['email'] = $exception->getMessage();
        }

        try {
            $userPass = Validator::input(Request::body()['userPass'])->rules(
                Validator::RULE_REQUIRED, [Validator::RULE_MIN_LENGTH, 8], [Validator::RULE_MAX_LENGTH, 32], Validator::RULE_PASSWORD
            );

        } catch (\Exception $exception) {
            $isFormValid = false;
            $formErrors['pass'] = $exception->getMessage();
        }

        try {
            $userPassConf = Validator::input(Request::body()['userPassConf'])->rules(
                Validator::RULE_REQUIRED, [Validator::RULE_EQUAL, Request::body()['userPass']]
            );
        } catch (\Exception $exception) {
            $isFormValid = false;
            $formErrors['passConf'] = $exception->getMessage();
        }

        if ($isFormValid)
        {
            $body = [
                "uuid"         => uniqid(strval(time())),
                "email"        => $userEmail,
                "email_update" => date("Y-m-d G:i:s"),
                "pass"         => password_hash($userPass, PASSWORD_BCRYPT, ['cost' => 12]),
                "pass_update"  => date("Y-m-d G:i:s")
            ];
            $this->user->add($body);

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
        try {

            $userEmail = htmlspecialchars(Request::body()['userEmail']);
            $userPass = Request::body()['userPass'];

            if (strlen($userEmail) === 0 && strlen($userPass) === 0)
                throw new \Exception('Please enter your email address and password.', 422);
            if (strlen($userEmail) === 0 || strlen($userPass) === 0)
                throw new \Exception('Incorrect login or password.', 422);
            if (!filter_var(htmlspecialchars($userEmail), FILTER_VALIDATE_EMAIL))
                throw new \Exception('Incorrect login or password.', 422);

            $result = $this->user->get(["email" => $userEmail]);

            if (empty($result))
                throw new \Exception('Incorrect login or password.', 422);
            if (!password_verify($userPass, $result['pass']))
                throw new \Exception('Incorrect login or password.', 422);

            Session::setUserId($result['uuid']);
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
        try {
            $email = Request::body()['userEmail'];
            
            if (!filter_var(htmlspecialchars($email), FILTER_VALIDATE_EMAIL))
                throw new \Exception('Incorrect email address.', 422);
            if (!$this->user->get(["email" => htmlspecialchars($email)]))
                throw new \Exception('Email address not related with any existing account.', 422);

            $tokenObject = new Token();
            $token = $tokenObject->create($email);
            $tokenObject->add(["email" => $email, "token" => $token, "expire" => date("Y-m-d G:i:s", time() + 3600)]);

            shell_exec("php ".ROOT_DIR."/manage.php email:passwordreset $email $token > /dev/null &");

            Session::setRequestStatus(true);
        } catch (\Exception $exception) {
            Session::setInputs(Request::body());
            Session::setErrors($exception->getMessage());
        } finally {
            Response::redirect('/password-reset');
        }
    }

    // #[Post('/password-update')]
    public function passwordUpdate(): void
    {
        $isFormValid = true;
        $formErrors = [];

        try {
            $userPass = Validator::input(Request::body()['userPass'])->rules(
                Validator::RULE_REQUIRED, [Validator::RULE_MIN_LENGTH, 8], [Validator::RULE_MAX_LENGTH, 32], Validator::RULE_PASSWORD
            );
        } catch (\Exception $exception) {
            $isFormValid = false;
            $formErrors['pass'] = $exception->getMessage();
        }

        try {
            $userPassConf = Validator::input(Request::body()['userPassConf'])->rules(
                Validator::RULE_REQUIRED, [Validator::RULE_EQUAL, Request::body()['userPass']]
            );
        } catch (\Exception $exception) {
            $isFormValid = false;
            $formErrors['passConf'] = $exception->getMessage();
        }

        if ($isFormValid)
        {
            $tokenObject = new Token();
            $token       = Request::body()['token'];
            $email       = $tokenObject->get(["token" => $token])['email'];

            $this->user->set([
                "pass"        => password_hash($userPass, PASSWORD_BCRYPT, ['cost' => 12]),
                "pass_update" => date("Y-m-d G:i:s")
            ], ["email" => $email]);
            $tokenObject->remove(["email" => $email]);

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
        $formErrors  = [];

        try {
            if (strlen(htmlspecialchars(Request::body()['userPassOld'])) === 0)
                throw new \Exception("Old password is a required.", 422);

            $result = $this->user->get(["email" => $this->data['user']['email']]);

            if (!password_verify(Request::body()['userPassOld'], $result['pass']))
                throw new \Exception("Incorrect password.", 422);
        } catch (\Exception $exception) {
            $isFormValid = false;
            $formErrors['passOld'] = $exception->getMessage();
        }

        try {
            $userPass = Validator::input(Request::body()['userPass'])->rules(
                Validator::RULE_REQUIRED, [Validator::RULE_MIN_LENGTH, 8], [Validator::RULE_MAX_LENGTH, 32],
                Validator::RULE_PASSWORD, [Validator::RULE_NOT_EQUAL, Request::body()['userPassOld']]
            );
        } catch (\Exception $exception) {
            $isFormValid = false;
            $formErrors['pass'] = $exception->getMessage();
        }

        if ($isFormValid)
        {
            $this->user->set([
                "pass"        => password_hash($userPass, PASSWORD_BCRYPT, ['cost' => 12]),
                "pass_update" => date("Y-m-d G:i:s")
            ], ["email" => $this->data['user']['email']]);
            
            Session::setRequestStatus(true);
            Session::setFlashMessage("Password updated successfully.", "success");
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
        $token = new Token();

        $token->remove(["email" => $this->data['user']['email']]);
        $this->user->remove(["uuid" => $this->data['user']['uuid']]);

        Session::clear();
        Response::redirect('/');
    }
}