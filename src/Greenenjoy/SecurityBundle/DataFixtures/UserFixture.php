<?php 

namespace Greenenjoy\SecurityBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Greenenjoy\SecurityBundle\Entity\User;
use Greenenjoy\SecurityBundle\Roles\Profil;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture implements ORMFixtureInterface
{
	private $encoder;

	public function __construct(UserPasswordEncoderInterface $encoder)
	{
		$this->encoder = $encoder;
	}

	public function load(ObjectManager $manager)
	{
		$user = new User();
		$user->setName('Ornella');
		$user->setLastname('Hagege');
		$user->setUsername('Ornella H.');
		$user->setEmail('hagege.ornella@hotmail.com');
		$user->setRoles(Profil::ADMIN);

		$password = $this->encoder->encodePassword($user, 'Jkl123');
		$user->setPassword($password);

		$manager->persist($user);
		$manager->flush();
	}
}