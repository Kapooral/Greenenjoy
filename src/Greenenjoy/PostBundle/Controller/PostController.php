<?php

namespace Greenenjoy\PostBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Greenenjoy\PostBundle\Entity\Post;
use Greenenjoy\PostBundle\Form\PostType;
use Greenenjoy\PostBundle\Entity\Comment;
use Greenenjoy\PostBundle\Form\CommentType;
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
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('GreenenjoyPostBundle:Post')->findOneby(array('slug' => $title));

        if ($post === null) {
            $request->getSession()->getFlashBag()->add('error', 'Aucune annonce de ce titre n\'a été trouvée.');
            return new Response('PAGE 404');
        }

        $comment_list = $em->getRepository('GreenenjoyPostBundle:Comment')->findBy(array('post' => $post), array('commentDate' => 'desc'));
        $comment = new Comment();
        $comment_form = $this->createForm(CommentType::class, $comment);
        $token = $request->request->get('_csrf_token');

        if ($request->isXmlHttpRequest() && $comment_form->handleRequest($request) && $comment_form->isValid()) {
            $comment->setPost($post);
            $em->persist($comment);
            $em->flush();
            return $this->json(array('success' => 1, 'message' => 'Commentaire posté !'));
        }

    	return $this->render('@GreenenjoyPost/Frontoffice/post_view.html.twig', array('post' => $post,'comment_list' => $comment_list, 'comment_form' => $comment_form->createView(), 'authenticate' => $token));
    }
}
