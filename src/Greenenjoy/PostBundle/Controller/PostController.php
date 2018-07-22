<?php

namespace Greenenjoy\PostBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Greenenjoy\PostBundle\Entity\Post;
use Greenenjoy\PostBundle\Form\PostType;
use Greenenjoy\PostBundle\State\State;

class PostController extends Controller
{
	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
    public function addAction(Request $request)
    {
    	$post = new Post();
    	$form = $this->createForm(PostType::class, $post);

    	if ($request->isMethod('Post') && $form->handleRequest($request) && $form->isValid()) {
    		$post->setAuthor($this->getUser());
            $post->setState(State::POSTED);
    		$post->setPostDate(new \DateTime());
    		$em = $this->getDoctrine()->getManager();
    		$em->persist($post);
    		$em->flush();
    		$request->getSession()->getFlashBag()->add('success', 'Article publié !');

    		return $this->redirectToRoute('greenenjoy_dashboard');
    	}

    	return $this->render('@GreenenjoyPost/Backoffice/edit_post.html.twig', array('form' => $form->createView()));
    }

    public function viewAction($title, Request $request)
    {
        $post = $this->getDoctrine()->getManager()->getRepository('GreenenjoyPostBundle:Post')->findOneby(array('slug' => $title));

        if ($post === null) {
            $request->getSession()->getFlashBag()->add('error', 'Aucune annonce n\'a été trouvée avec ce titre.');
        }

    	return $this->render('@GreenenjoyPost/Frontoffice/post_view.html.twig', array('post' => $post));
    }
}
