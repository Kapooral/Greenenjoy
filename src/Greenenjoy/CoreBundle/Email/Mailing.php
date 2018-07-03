<?php

Namespace Greenenjoy\CoreBundle\Email;

use Greenenjoy\SecurityBundle\Entity\User;

class Mailing
{
	/**
	 * @var \Swift_Mailer
	 */
	private $mailer;
	private $mailerUser;
	private $templating;

	public function __construct(\Swift_Mailer $mailer, $mailerUser, \Twig_Environment $templating)
	{
		$this->mailer = $mailer;
		$this->mailerUser = $mailerUser;
		$this->templating = $templating;
	}

	public function recoveryPassword(User $user)
	{
		$message = new \Swift_Message('Mot de passe oublié ?');
		$message->setBody($this->templating->render('@GreenenjoyCore/Email/email_recovery.html.twig', array('email' => $user->getEmail(), 'identifier' => $user->getToken())), 'text/html');

		$message->setFrom([$this->mailerUser => 'Greenenjoy - Slow your life'])->setTo($user->getEmail());
		$this->mailer->send($message);
	}

	public function confirmEditPassword(User $user)
	{
		$message = new \Swift_Message('Mot de passe mis à jour !');
		$message->setBody($this->templating->render('@GreenenjoyCore/Email/confirm_pass.html.twig'), 'text/html');
		$message->setFrom([$this->mailerUser => 'Greenenjoy - Slow your life'])->setTo($user->getEmail());
		$this->mailer->send($message);
	}
}