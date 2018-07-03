<?php

namespace Greenenjoy\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class SecurityController extends Controller
{
	public function indexAction()
	{
		// replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
	}

    public function loginAction()
	{
		if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
			
			return $this->redirectToRoute('greenenjoy_homepage');
		}

		$authenticationUtils = $this->get('security.authentication_utils');

		return $this->render(
			'@GreenenjoySecurity/Default/login.html.twig', array(
				'last_username' => $authenticationUtils->getLastUsername(),
				'error' => $authenticationUtils->getLastAuthenticationError()));
	}

	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function dashboardAction()
	{
		return $this->render('@GreenenjoySecurity/Default/dashboard.html.twig');
	}
}
