<?php

require 'vendor/phpmailer/phpmailer/PHPMailerAutoload.php';

/**
 * Class ControllerEmail
 *
 * Used to send an email
 */
class ControllerEmail
{

    /**
     * Simple private phpmailer object
     *
     * @var
     *  the email to send
     */
    private $mail;

    function init($email)
    {
        $mail = new PHPMailer;

        // config
//        $mail->SMTPDebug = Resources::DEBUG ? 3 : 0;
        $mail->isSMTP();
        $mail->SMTPAuth = true;

        // core
        $mail->Host = Resources::$json['email']['host'];
        $mail->Username = Resources::$json['email']['user'];
        $mail->Password = Resources::$json['email']['pass'];
        $mail->SMTPSecure = Resources::$json['email']['sec'];
        $mail->Port = Resources::$json['email']['port'];
        $mail->addAddress(Resources::$json['email']['contact']);
        $mail->setFrom($email['from'], $email['from-name']);
        $mail->isHTML(true);
        $mail->Subject = $email['subject'];
        $mail->Body = 'Vous avez un nouveau message de : ' . $email['from-name'] . ' * ' . $email['from'] . '<br /><br />' . $email['content'];

        // attribution
        $this->mail = $mail;
    }

    /**
     * try to send an email
     *
     * @return string
     *  empty if success
     *  otherwise returns the error message
     */
    function send()
    {
        if (!$this->mail->send())
        {
            return $this->mail->ErrorInfo;
        }

        return '';
    }
}