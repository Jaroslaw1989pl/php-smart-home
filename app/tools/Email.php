<?php

declare(strict_types = 1);

namespace app\tools;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


class Email
{
    private PHPMailer $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer();

        try
        {
            $this->mail->isSMTP();
            $this->mail->Host = 'smtp.gmail.com';
            $this->mail->Port = 465;
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            // SMTP credentials
            $this->mail->SMTPAuth = true;
            $this->mail->Username = 'dzidajaroslaw@gmail.com';
            $this->mail->Password = SMTP_CONFIG['pass'];
            // email body configs
            $this->mail->CharSet = 'UTF-8';
            $this->mail->setFrom('no-reply@gmail.com', 'No reply');
            $this->mail->addReplyTo('dzidajaroslaw@gmail.com', 'Playfab');
            $this->mail->isHTML(true);
        }
        catch (\Exception $exception)
        {
            echo "Mailer configuration error: {$this->mail->ErrorInfo}";
        }
    }

    function __toString()
    {
        $string = <<<FRAGMENT
        Email commands:
        > email:
        FRAGMENT.PHP_EOL;

        foreach (get_class_methods(__CLASS__) as $method)
            if (!str_starts_with($method, "_"))
                $string .= "\t$method".PHP_EOL;

        return $string;
    }

    function __call($name, $arguments)
    {
        throw new \Exception("Mmethod \"$name\" does not exists in class Email.", 4);
    }

    public function passwordreset(array $arguments): void
    {
        try {
            if (count($arguments) < 2)
                throw new \Exception("Send method requires at least 2 arguments (email address, token).");
            
            $emailAddress = $arguments[0];
            $token = $arguments[1];

            $this->mail->addAddress($emailAddress);
            $this->mail->Subject = "Password reset";
            $this->mail->Body = sprintf(PASSWORD_RESET, $token);
            // attachments
            // if ($attachment) $this->mail->addAttachment(...$attachment);
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), 4);
        }

        try {
            $this->mail->send();
        } catch (\Exception $exception) {
            throw new \Exception("Mailer send error: {$this->mail->ErrorInfo}", 4);
        }
    }
}


const PASSWORD_RESET = <<<TEMPLATE
<html lang="en">
    <head>
        <title>Password reset</title>
    </head>
    <body>
        <p>Password reset: <a href="http://smart-home.pl/password-update?q=%s">Set new password</a></p>
    </body>
</html>
TEMPLATE;