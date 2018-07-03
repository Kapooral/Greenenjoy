<?php

namespace Greenenjoy\SecurityBundle\Account;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use TS\NaoBundle\PasswordRecovery\PasswordRecovery;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\FileSystem\Exception\IOException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Greenenjoy\SecurityBundle\Entity\User;
use Greenenjoy\SecurityBundle\Profil;
use TS\NaoBundle\Email\Mailing;

class Account
{
	private $mailer;
	private $currentUser;
	private $flashMessage;
	private $targetDirectory;
	private $anonymousEmail;
	private $em;
	private $encoder;

	public function __construct(Mailing $mailer, TokenStorageInterface $tokenStorage, RequestStack $requestStack, $targetDirectory, $anonymousEmail, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
	{
		$this->mailer = $mailer;
		$this->currentUser = $tokenStorage->getToken()->getUser();
		$this->flashMessage = $requestStack->getCurrentRequest()->getSession()->getFlashBag();
		$this->targetDirectory = $targetDirectory;
		$this->anonymousEmail = $anonymousEmail;
		$this->em = $em;
		$this->encoder = $encoder;
	}

	public function getUser($email)
	{
		$user = $this->em->getRepository('TSNaoBundle:User')->findOneBy(array('email' => $email));

		if (!$user instanceof User) {
			return;
		}

		return $user;
	}

	public function verify($email, $token)
	{
		$user = $this->getUser($email);

		if ($user == null || $user->getConfirmToken() != $token) {
			$this->flashMessage->add('error', 'Impossible de réinitialiser le mot de passe. L\'utilisateur n\'est pas reconnu.');
			return;
		}

		return $user;
	}

	public function recovery($email)
	{
		$user = $this->getUser($email);

		if ($user == null) {
			$this->flashMessage->add('error', 'Impossible de réinitialiser votre mot de passe. L\'identifiant est inconnu.');
			return;
		}

		$user->setConfirmToken(bin2hex(random_bytes(16)));
		$this->em->flush();
		$this->mailer->recoveryPassword($user);
		$this->flashMessage->add('success', 'Le mail de récupération de mot de passe a bien été envoyé.');
	}

	public function resetPassword($email, $password)
	{
		$user = $this->getUser($email);

		if ($user == null) {
			$this->flashMessage->add('error', 'Impossible de mettre à jour le mot de passe. L\'utilisateur n\'a pas été reconnu.');
			return;
		}

		$this->encodePassword($user, $password);
		$user->setConfirmToken(null);
		$this->em->flush();
		$this->mailer->confirmEditPassword($user);
		$this->flashMessage->add('success', 'Mot de passe mis à jour.');
	}

	public function edit($email, $form)
	{
		$user = $this->getUser($email);

		if ($user == null) {
			$this->flashMessage->add('error', 'Impossible de mettre à jour vos informations.');
			return;
		}

		if (!$this->encoder->isPasswordValid($user, $form->get('current_password')->getData())) {
			$this->flashMessage->add('error', 'Votre mot de passe actuel est incorrect.');
			return;
		}

		$username = $form->get('username')->getData();
		$email = $form->get('email')->getData();
		$password = $form->get('password')->getData();

		if ($username) {
			$user->setUsername($username);
		}

		if ($email) {
			$existingUser = $this->getUser($email);

			if ($existingUser instanceof User) {
				$this->flashMessage->add('error', 'Cette adresse e-mail est déjà prise.');
				return;
			}

			$user->setEmail($email);
		}

		if ($password) {
			$this->encodePassword($user, $password);
			$this->mailer->confirmEditPassword($user);
		}

		$this->em->flush();
		$this->flashMessage->add('success', 'Mise à jour effectuée.');
	}
}