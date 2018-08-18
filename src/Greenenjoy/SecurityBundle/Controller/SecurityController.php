<?php

namespace Greenenjoy\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
// For Requests
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
// For Entities
use Greenenjoy\SecurityBundle\Form\ResetPasswordType;
use Greenenjoy\SecurityBundle\Form\InfosType;
// Services
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Greenenjoy\SecurityBundle\Account\Manager;

class SecurityController extends Controller
{
	/**
	 * @Route("/login", name="login")
	 */
    public function login(AuthorizationCheckerInterface $checker, AuthenticationUtils $authenticationUtils)
	{
		if ($checker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
			
			return $this->redirectToRoute('greenenjoy_dashboard');
		}

		$lastUsername = $authenticationUtils->getLastUsername();
		$error = $authenticationUtils->getLastAuthenticationError();

		return $this->render(
			'@GreenenjoySecurity/Default/login.html.twig', array(
				'last_username' => $lastUsername,
				'error' => $error));
	}

	/**
	 * @Route("/recovery", name="greenenjoy_recovery")
	 */
	public function recoveryAction(Request $request, AuthorizationCheckerInterface $checker, Manager $accountManager)
	{
		if ($checker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
			
			return $this->redirectToRoute('greenenjoy_dashboard');
		}

    	if($request->isMethod('POST')) {
    		$submittedToken = $request->request->get('_csrf_token');
    		if ($this->isCsrfTokenValid('authenticate', $submittedToken)) {
    			$email = $request->request->get('email');
	    		$accountManager->recovery($email);

	    		return $this->redirectToRoute('greenenjoy_recovery');
    		}
    	}

    	return $this->render('@GreenenjoySecurity/Default/recovery.html.twig');
	}

	/**
	 * @Route("/reset-password", name="greenenjoy_reset_password")
	 */
	public function resetPasswordAction(Request $request, Manager $accountManager)
	{
		$form = $this->createForm(ResetPasswordType::class);
		if ($request->isMethod('POST')) {
			if ($form->handleRequest($request)->isValid()) {
				$email = $request->request->get('email');
				$password = $form->get('password')->getData();
				$accountManager->resetPassword($email, $password);
				return $this->redirectToRoute('greenenjoy_login');
			}
			return $this->render('@GreenenjoySecurity/Default/reset_password.html.twig', array('form' => $form->createView(), 'email' => $request->request->get('email')));
		}

		elseif ($request->query->get('email') && $request->query->get('identifier')) {
			$email = $request->query->get('email');
			$token = $request->query->get('identifier');
			if ($accountService->verify($email, $token) != null) {

				return $this->render('@GreenenjoySecurity/Default/reset_password.html.twig', array('form' => $form->createView(), 'email' => $email));
			}
		}
		return $this->redirectToRoute('greenenjoy_homepage');
	}

	/**
	 * @Route("/dashboard/informations", name="greenenjoy_edit_infos")
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function editInfosAction(Request $request)
	{
		$user = $this->getUser();
		$form = $this->createForm(InfosType::class);
		
		if ($request->isMethod('POST') && $form->handleRequest($request)) {
			return new Response(var_dump($form->get('current_password')->getData()));
		}

		return $this->render('@GreenenjoySecurity/Default/modif_infos.html.twig', array('form' => $form->createView()));
	}
}
