<?php

namespace Greenenjoy\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
// For Requests
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
// For Entities
use Greenenjoy\CoreBundle\Entity\Subscribers;
use Greenenjoy\CoreBundle\Form\SubscribersType;
// Services
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CoreController extends Controller
{
	/**
	 * @Route("/", name="greenenjoy_homepage")
	 */
	public function indexAction()
	{
		$posts = $this->getDoctrine()->getManager()->getRepository('GreenenjoyPostBundle:Post')->findBy(array('state' => 'posted'), array('postDate' => 'desc'), 6, 0);
		return $this->render('@GreenenjoyCore/Default/index.html.twig', array('posts' => $posts));
	}

	/**
	 * @Route(
	 		"/{slug}",
	 		name="greenenjoy_front_category",
	 		requirements={
	 			"slug" : "slow-beauty|slow-conso|slow-food|slow-attitude|boite-a-pharmacie"})
	 */
	public function frontCategoryAction(Request $request, $slug)
	{
		$category = $this->getDoctrine()->getManager()->getRepository('GreenenjoyPostBundle:Categories')->findOneBy(array('slug' => $slug));
		if ($category === null) {
			$request->getSession()->getFlashBag()->add('error', 'Cette catégorie n\'existe pas.');
			return $this->redirectToRoute('greenenjoy_homepage');
		}

		return $this->render('@GreenenjoyCore/Default/index.html.twig', array('category' => $category));
	}

	/**
	 * @Route("/subscribe", name="greenenjoy_subscribe")
	 */
	public function subscribeAction(Request $request, ValidatorInterface $validator)
	{
		if ($request->isXmlHttpRequest()) {
			$email = $request->request->get('email');
			$subscriber = new Subscribers();
			$subscriber->setEmail($email);

			$listErrors = $validator->validate($subscriber);
			if (count($listErrors) > 0) {

				return $this->json(array('success' => false, 'message' => $listErrors->get(0)->getMessage()));
			}
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($subscriber);
			$em->flush();

			return $this->json(array('success' => true, 'message' => 'Abonné(e) !'));
		}

		return $this->redirectToRoute('greenenjoy_homepage');
	}

    /**
     * @Route("/dashboard", name="greenenjoy_dashboard")
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function dashboardAction()
	{
		$mostViewed = $this->getDoctrine()->getManager()->getRepository('GreenenjoyPostBundle:Post')->findOneBy(array(), array('views' => 'desc'));
		$mostLiked = $this->getDoctrine()->getManager()->getRepository('GreenenjoyPostBundle:Post')->findOneBy(array(), array('likes' => 'desc'));
		return $this->render('@GreenenjoyCore/Default/dashboard.html.twig', array('mostViewed' => $mostViewed, 'mostLiked' => $mostLiked));
	}

	/**
	 * @Route("/dashboard/post-list", name="greenenjoy_post_list")
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function backPostListAction(Request $request)
	{
		$posts = $this->getDoctrine()->getManager()->getRepository('GreenenjoyPostBundle:Post')->findBy(array(), array('postDate' => 'desc'));
		return $this->render('@GreenenjoyPost/Backoffice/article_list.html.twig', array('posts' => $posts));
	}

	/**
	 * @Route("/search", name="greenenjoy_search")
	 */
	public function searchAction(Request $request)
	{
		if ($request->isMethod('post')) {
			$title = $request->request->get('search');
			$result_search = $this->getDoctrine()->getManager()->getRepository('GreenenjoyPostBundle:Post')->findBy(array('title' => $title), array('postDate' => 'desc'));
			return $this->render('@GreenenjoyCore/Default/search.html.twig', array('result_search' => $result_search));
		}

		return $this->redirectToRoute('greenenjoy_homepage');
	}

	public function menuAction()
	{
		$listCategory = $this->getDoctrine()->getManager()->getRepository('GreenenjoyPostBundle:Categories')->findAll();
		return $this->render('@GreenenjoyCore/Sections/menu.html.twig', array('listCategory' => $listCategory));
	}
}
