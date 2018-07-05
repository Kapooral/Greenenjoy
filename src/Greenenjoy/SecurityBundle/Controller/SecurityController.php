<?php

namespace Greenenjoy\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Greenenjoy\SecurityBundle\Form\ResetPasswordType;

class SecurityController extends Controller
{
    public function loginAction()
	{
		if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
			
			return $this->redirectToRoute('greenenjoy_dashboard');
		}

		$authenticationUtils = $this->get('security.authentication_utils');

		return $this->render(
			'@GreenenjoySecurity/Default/login.html.twig', array(
				'last_username' => $authenticationUtils->getLastUsername(),
				'error' => $authenticationUtils->getLastAuthenticationError()));
	}

	public function recoveryAction(Request $request)
	{
		if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
			
			return $this->redirectToRoute('greenenjoy_dashboard');
		}
		
		$submittedToken = $request->request->get('_csrf_token');
    	if($request->isMethod('POST') && $this->isCsrfTokenValid('authenticate', $submittedToken))
    	{
    		$email = $request->request->get('email');
    		$accountService = $this->get('greenenjoy.account.recovery');
    		$accountService->recovery($email);

    		return $this->redirectToRoute('greenenjoy_recovery');
    	}

    	return $this->render('@GreenenjoySecurity/Default/recovery.html.twig');
	}

	public function resetPasswordAction(Request $request)
	{
		$accountService = $this->get('greenenjoy.account.recovery');
		$form = $this->createForm(ResetPasswordType::class);
		if ($request->isMethod('POST')) {

			if ($form->handleRequest($request)->isValid()) {
				$accountService->resetPassword($request->request->get('email'), $form->get('password')->getData());
				return $this->redirectToRoute('greenenjoy_login');
			}
			return $this->render('@GreenenjoySecurity/Default/reset_password.html.twig', array('form' => $form->createView(), 'email' => $request->request->get('email')));
		}

		if ($request->query->get('email') && $request->query->get('identifier')) {
			$email = $request->query->get('email');
			$token = $request->query->get('identifier');
			if ($accountService->verify($email, $token) != null) {

				return $this->render('@GreenenjoySecurity/Default/reset_password.html.twig', array('form' => $form->createView(), 'email' => $email));
			}
		}
		return $this->redirectToRoute('greenenjoy_homepage');
	}
}
