<?php


namespace System\Main;


use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class EmailSender
{
    /**
     * @param array $to
     * @param string $title
     * @param string $message
     * @return int
     */
    public function send($to, $title, $message)
    {

        $transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, "ssl"))
            ->setUsername('nekrohipster@gmail.com')
            ->setPassword('Dfvgbhj1');

        $mailer = new Swift_Mailer($transport);

        // Create a message
        $message = (new Swift_Message($title))
            ->setFrom(['nekrohipster@gmail.com' => 'Сервис урн'])
            ->setTo($to)
            ->setBody($message);

        // Send the message
        return $mailer->send($message);
    }

}