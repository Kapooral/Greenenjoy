<?php

namespace Greenenjoy\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class CoreController extends Controller
{
	public function indexAction()
	{
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
	}

    /**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function dashboardAction()
	{
		return $this->render('@GreenenjoyCore/Default/dashboard.html.twig');
	}
}
