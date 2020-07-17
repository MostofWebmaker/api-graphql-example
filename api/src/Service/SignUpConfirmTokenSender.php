<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\User\Entity\Email;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class SignUpConfirmTokenSender
{
	private \Swift_Mailer $mailer;
	private Environment $twig;

    public function __construct(\Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

	/**
	 * @param Email $email
	 * @param string $token
	 * @throws LoaderError
	 * @throws RuntimeError
	 * @throws SyntaxError
	 */
    public function send(Email $email, string $token): void
    {
        $message = (new \Swift_Message('Подтверждение регистрации'))
            ->setTo($email->getValue())
            ->setBody($this->twig->render('mail/user/signup.html.twig', [
                'token' => $token
            ]), 'text/html');

        if (!$this->mailer->send($message)) {
            throw new \RuntimeException('Невозможно отправить сообщение');
        }
    }
}
