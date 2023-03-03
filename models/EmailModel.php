<?php

declare(strict_types = 1);

namespace models;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


class EmailModel
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

    public function address(string $email): EmailModel
    {
        $this->mail->addAddress($email);

        return $this;
    }

    public function message(string $subject, string $body, array $attachment = null): EmailModel
    {
        $this->mail->Subject = $subject;
        $this->mail->Body = $body;
        // attachments
        if ($attachment)
            $this->mail->addAttachment(...$attachment);

        return $this;
    }

    public function send(): void
    {
        try
        {
            $this->mail->send();
        }
        catch (\Exception $exception)
        {
            echo "Mailer send error: {$this->mail->ErrorInfo}";
        }
    }
}