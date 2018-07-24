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

class CommentController extends Controller
{
	public function reportAction(Request $request)
	{
		if ($request->isXmlHttpRequest() && $this->isCsrfTokenValid('authenticate', $request->request->get('authenticate'))) {
			$em = $this->getDoctrine()->getManager();
			$comment = $em->getRepository('GreenenjoyPostBundle:Comment')->findOneBy(array('id' => $request->request->get('comment_id')));
			if ($comment === null) {
				return $this->json(array('success' => false, 'message' => 'Commentaire introuvable !'));
			}

			$comment->setReported($comment->getReported() +1);
			$em->flush();
			return $this->json(array('success' => true, 'message' => 'Commentaire signalé !'));
		}

		return $this->redirectToroute('greenenjoy_homepage');
	}

	public function reportedAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$comments_reported = $em->getRepository('GreenenjoyPostBundle:Comment')->getReported();
		$token = $request->request->get('_csrf_token');

		return $this->render('@GreenenjoyPost/Backoffice/reported.html.twig', array('comments' => $comments_reported, 'authenticate' => $token));
	}

	public function authorizeAction(Request $request)
	{
		if ($request->isXmlHttpRequest()) {

			if ($this->isCsrfTokenValid('authenticate', $request->request->get('authenticate'))) {
				$em = $this->getDoctrine()->getManager();
				$comment = $em->getRepository('GreenenjoyPostBundle:Comment')->findOneBy(array('id' => $request->request->get('comment_id')));

				if ($comment === null) {
					return $this->json(array('success' => false, 'message' => 'Commentaire introuvable !'));
				}

				$comment->setReported(0);
				$em->flush();

				return $this->json(array('success' => true, 'message' => 'Commentaire autorisé.'));
			}

			return $this->json(array('success' => false, 'message' => 'Clé de sécurité invalide.'));
		}

		return $this->redirectToroute('greenenjoy_homepage');
	}

	public function deleteAction(Request $request)
	{
		if ($request->isXmlHttpRequest()) {

			if ($this->isCsrfTokenValid('authenticate', $request->request->get('authenticate'))) {
				$em = $this->getDoctrine()->getManager();
				$comment = $em->getRepository('GreenenjoyPostBundle:Comment')->findOneBy(array('id' => $request->request->get('comment_id')));

				if ($comment === null) {
					return $this->json(array('success' => false, 'message' => 'Commentaire introuvable !'));
				}

				$em->remove($comment);
				$em->flush();

				return $this->json(array('success' => true, 'message' => 'Commentaire supprimé.'));
			}

			return $this->json(array('success' => false, 'message' => 'Clé de sécurité invalide.'));
		}

		return $this->redirectToroute('greenenjoy_homepage');
	}
}
