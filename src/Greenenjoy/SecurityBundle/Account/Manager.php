<?php

namespace Greenenjoy\SecurityBundle\Account;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Greenenjoy\SecurityBundle\Entity\User;
use Greenenjoy\CoreBundle\Service\Mailing;

class Manager
{
	private $mailer;
	private $flashMessage;
	private $validator;
	private $em;
	private $encoder;

	public function __construct(Mailing $mailer, RequestStack $requestStack, ValidatorInterface $validator, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
	{
		$this->mailer = $mailer;
		$this->flashMessage = $requestStack->getCurrentRequest()->getSession()->getFlashBag();
		$this->validator = $validator;
		$this->em = $em;
		$this->encoder = $encoder;
	}

	public function getUser($email)
	{
		$user = $this->em->getRepository('GreenenjoySecurityBundle:User')->findOneBy(array('email' => $email));

		if (!$user instanceof User) {
			return;
		}

		return $user;
	}

	public function verify($email, $token)
	{
		$user = $this->getUser($email);

		if ($user == null || $user->getToken() != $token) {
			$this->flashMessage->add('error', 'Votre jeton de sécurité a expiré.');
			return;
		}

		return $user;
	}

	public function recovery($email)
	{
		$user = $this->getUser($email);

		if ($user == null) {
			$this->flashMessage->add('error', 'Cet identifiant est incorrect.');
			return;
		}

		$this->mailer->recoveryPassword($user);
		$user->setToken(bin2hex(random_bytes(16)));
		$this->em->flush();
		$this->flashMessage->add('success', 'Vous allez recevoir un mail de réinitialisation.');
	}

	public function resetPassword($email, $password)
	{
		$user = $this->getUser($email);

		if ($user == null) {
			$this->flashMessage->add('error', 'Impossible de mettre à jour le mot de passe. L\'utilisateur n\'a pas été reconnu.');
			return;
		}

		$this->encodePassword($user, $password);
		$user->setToken(null);
		$this->em->flush();
		$this->mailer->confirmEditPassword($user);
		$this->flashMessage->add('success', 'Mot de passe mis à jour.');
	}

	public function encodePassword($user, $password)
	{
		$encoded = $this->encoder->encodePassword($user, $password);
		$user->setPassword($encoded);

		return $user;
	}

	public function edit_infos($user, $form)
	{
		if (!$this->encoder->isPasswordValid($user, $form->get('current_password')->getData())) {
			$return = array('success' => false, 'message' => 'Le mot de passe actuel est incorrect !');
			return $return;
		}

		if ($form->get('profilePicture')) {
			$user->setProfilePicture($form->get('profilePicture')->getData());
		}

		if ($form->get('email')) {
			$existingUser = $this->getUser($form->get('email')->getData());
			if ($existingUser instanceof User) {
				return;
			}

			$user->setEmail($form->get('email')->getData());
		}

		if ($form->get('username')) {
			$user->setUsername($form->get('username')->getData());
		}

		if ($form->get('instagram')) {
			$user->getInstagram($form->get('instagram')->getData());
		}

		if ($form->get('coverBiography')) {
			$user->setCoverBiography($form->get('coverBiography')->getData());
		}

		if ($form->get('biography')) {
			$user->setBiography($form->get('biography')->getData());
		}

		if ($form->get('password')) {
			$this->encodePassword($user, $form->get('password')->getData());
		}
	}
}