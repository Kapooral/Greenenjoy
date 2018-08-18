<?php

Namespace Greenenjoy\CoreBundle\Service;

use Greenenjoy\SecurityBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Greenenjoy\PostBundle\Entity\Post;

class Mailing
{
	/**
	 * @var \Swift_Mailer
	 */
	private $mailer;
	private $mailerUser;
	private $em;
	private $templating;

	public function __construct(\Swift_Mailer $mailer, \Twig_Environment $templating, EntityManagerInterface $em, $mailerUser)
	{
		$this->mailer = $mailer;
		$this->templating = $templating;
		$this->em = $em;
		$this->mailerUser = $mailerUser;
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

	public function newPost(Post $post)
	{
		$subscribers = $this->em->getRepository('GreenenjoyCoreBundle:Subscribers')->findAll();
		$message = new \Swift_Message('Nouvelle publication');
		$message->setBody($this->templating->render('@GreenenjoyCore/Email/new_post.html.twig', array('post' => $post)), 'text/html');
		$message->setFrom([$this->mailerUser => 'Greenenjoy - Slow your life']);

		foreach ($subscribers as $subscriber) {
			$message->setTo($subscriber->getEmail());
			$this->mailer->send($message);
		}
	}
}