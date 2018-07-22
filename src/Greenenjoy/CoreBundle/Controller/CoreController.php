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
		$categories = $this->getDoctrine()->getManager()->getRepository('GreenenjoyPostBundle:Categories')->findAll();
		return $this->render('@GreenenjoyCore/Default/index.html.twig', array('categories' => $categories));
        /*return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);*/
	}

	public function byCategorieAction($categorie, Request $request)
	{
		$categorieSelected = $this->getDoctrine()->getManager()->getRepository('GreenenjoyPostBundle:Categories')->findOneBy(array('slug' => $categorie));
		if ($categorieSelected === null) {
			$request->getSession()->getFlashBag()->add('error', 'Cette catégorie n\'existe pas.');
		}

		return $this->render('@GreenenjoyPost/Frontoffice/article_list.html.twig', array('categorie' => $categorieSelected));
	}

    /**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function dashboardAction()
	{
		return $this->render('@GreenenjoyCore/Default/dashboard.html.twig');
	}
}
