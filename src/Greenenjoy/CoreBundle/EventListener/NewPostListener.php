<?php

namespace Greenenjoy\CoreBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Greenenjoy\PostBundle\Entity\Post;
use Greenenjoy\CoreBundle\Service\Mailing;

class NewPostListener
{
	private $mailer;

	public function __construct(Mailing $mailer)
	{
		$this->mailer = $mailer;
	}

	public function postPersist(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();

		if (!$entity instanceof Post) {
			return;
		}

		$this->mailer->newPost($entity); 
	}
}