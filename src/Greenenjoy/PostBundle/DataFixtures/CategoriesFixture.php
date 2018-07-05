<?php 

namespace Greenenjoy\PostBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Greenenjoy\PostBundle\Entity\Categories;

class CategoriesFixture implements ORMFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$this->loadCategorie1($manager);
		$this->loadCategorie2($manager);
		$this->loadCategorie3($manager);
		$this->loadCategorie4($manager);
		$this->loadCategorie5($manager);
	}

	public function loadCategorie1(ObjectManager $manager)
	{
		$categorie = new Categories();
		$categorie->setName('Slow Attitude');

		$manager->persist($categorie);
		$manager->flush();
	}

	public function loadCategorie2(ObjectManager $manager)
	{
		$categorie = new Categories();
		$categorie->setName('Slow Food');

		$manager->persist($categorie);
		$manager->flush();
	}

	public function loadCategorie3(ObjectManager $manager)
	{
		$categorie = new Categories();
		$categorie->setName('Slow Conso');

		$manager->persist($categorie);
		$manager->flush();
	}

	public function loadCategorie4(ObjectManager $manager)
	{
		$categorie = new Categories();
		$categorie->setName('Slow Beauty');

		$manager->persist($categorie);
		$manager->flush();
	}

	public function loadCategorie5(ObjectManager $manager)
	{
		$categorie = new Categories();
		$categorie->setName('Boîte à pharmacie');

		$manager->persist($categorie);
		$manager->flush();
	}
}