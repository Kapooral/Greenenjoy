<?php

namespace Greenenjoy\CoreBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Greenenjoy\CoreBundle\Entity\Subscribers;
use Greenenjoy\PostBundle\Entity\Post;
use Greenenjoy\CoreBundle\Service\Mailing;

class NewPost
{
	private $mailer;

	public function __construct(Mailing $mailer)
	{
		$this->mailer = $mailer;
	}

	public function postPersist(LifecycleEventArgs $args)
	{
		$entity = $args->getEntity();

		if (!$entity instanceof Post) {
			return;
		}

		$this->mailer->newPost($entity); 
	}
}