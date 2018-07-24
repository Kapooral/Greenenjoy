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
}
