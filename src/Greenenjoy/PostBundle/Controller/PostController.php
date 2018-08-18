<?php

namespace Greenenjoy\PostBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
// For Requests
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
// For Entities
use Greenenjoy\PostBundle\Entity\Post;
use Greenenjoy\PostBundle\Form\PostType;
use Greenenjoy\PostBundle\Entity\Comment;
use Greenenjoy\PostBundle\Form\CommentType;
use Greenenjoy\PostBundle\State\State;
// Services
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PostController extends Controller
{
	/**
     * @Route("/dashboard/add", name="greenenjoy_add_post")
	 * @Security("has_role('ROLE_ADMIN')")
	 */
    public function addAction(Request $request)
    {
    	$post = new Post();
    	$form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

    	if ($form->isSubmitted() && $form->isValid()) {
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

    /**
     * @Route("/dashboard/edit/{slug}", name="greenenjoy_edit_post")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editAction($slug, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('GreenenjoyPostBundle:Post')->findOneby(array('slug' => $slug));
        if ($post === null) {
            $request->getSession()->getFlashBag()->add('error', 'Aucun article trouvé !');
            return $this->redirectToRoute('greenenjoy_dashboard');
        }

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($post->getState() != State::POSTED) {
                $post->setState(State::POSTED);
            }
            if ($form->get('image')->getData() != null) {
                $post->setImage($form->get('image')->getData());
            }
            $post->setPostDate(new \DateTime());
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Article publié !');
            return $this->redirectToRoute('greenenjoy_dashboard');
        }

        return $this->render('@GreenenjoyPost/Backoffice/edit_post.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/dashboard/delete-post", name="greenenjoy_delete_post")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->request->get('authenticate');
            if ($this->isCsrfTokenValid('authenticate', $submittedToken)) {
                $em = $this->getDoctrine()->getManager();
                $post = $em->getRepository('GreenenjoyPostBundle:Post')->findOneBy(array('id' => $request->request->get('post_id')));
                if ($post === null) {

                    return $this->json(array('success' => false, 'message' => 'Article introuvable !'));
                }
                $comment_list = $em->getRepository('GreenenjoyPostBundle:Comment')->findBy(array('post' => $post));
                foreach ($comment_list as $comment) {
                    $em->remove($comment);
                }
                $em->remove($post);
                $em->flush();
                
                return $this->json(array('success' => true, 'message' => 'Article supprimé !'));
            }

            return $this->json(array('success' => false, 'message' => 'Clé de sécurité invalide.'));
        }

        return $this->redirectToRoute('greenenjoy_dashboard');
    }

    /**
     * @Route(
            "/{category}/{title}",
            name="greenenjoy_view_post",
            requirements={
                "category" : "slow-beauty|slow-conso|slow-food|slow-attitude|boite-a-pharmacie"})
     */
    public function viewAction($title, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('GreenenjoyPostBundle:Post')->findOneby(array('slug' => $title));
        if ($post === null) {
            $request->getSession()->getFlashBag()->add('error', 'Aucune annonce de ce titre n\'a été trouvée.');
            return $this->redirectToRoute('greenenjoy_homepage');
        }
        $post->setViews($post->getViews() + 1);
        $comment_list = $em->getRepository('GreenenjoyPostBundle:Comment')->findBy(array('post' => $post), array('commentDate' => 'desc'));
        $comment = new Comment();
        $comment_form = $this->createForm(CommentType::class, $comment);
        $comment_form->handleRequest($request);
        $token = $request->request->get('_csrf_token');
        if ($comment_form->isSubmitted() && $comment_form->isValid()) {
            $comment->setPost($post);
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('greenenjoy_view_post', array('category' => $post->getCategorie()->getSlug(), 'title' => $post->getSlug()));
        }
    	return $this->render('@GreenenjoyPost/Frontoffice/post_view.html.twig', array('post' => $post,'comment_list' => $comment_list, 'comment_form' => $comment_form->createView(), 'authenticate' => $token));
    }

    /**
     * @Route("/like", name="greenenjoy_like")
     */
    public function likeAction(Request $request)
    {
        if ($request->isXmlHttpRequest() && $this->isCsrfTokenValid('authenticate', $request->request->get('authenticate'))) {
            $ip = $request->getClientIp();
            $em = $this->getDoctrine()->getManager();
            $post = $em->getRepository('GreenenjoyPostBundle:Post')->findOneby(array('id' => $request->request->get('post_id')));
            if ($post === null){
                return $this->json(array('success' => false, 'message' => 'Article introuvable !'));
            }
            elseif (in_array($ip, $post->getLikes())) {
                return $this->json(array('success' => false, 'message' => 'Vous avez déjà aimé cet article !'));
            }
            $post->setLikes($ip);
            $em->flush();

            return $this->json(array('success' => true, 'message' => 'Article aimé !'));
        }

        return $this->redirectToRoute('greenenjoy_homepage');
    }
}
