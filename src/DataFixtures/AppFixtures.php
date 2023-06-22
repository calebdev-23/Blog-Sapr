<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
       for ($i = 0; $i <10; $i++) {
           $user = new User();
           $user->setEmail($faker->email());
           $password = $this->hasher->hashPassword($user, 'azertyuiop');
           $user->setPassword($password);
            $manager->persist($user);
        }
        for ($i = 0; $i <20; $i++) {
            $product = new Product();
            $product->setName('product'.$i);
            $product->setPrice($faker->randomFloat(null,4586, 9452));
            $product->setDescription('Lorem ipsum dolor sit amet, consectetur adipisicing elit.Asperiores 
            dicta dolor enim exercitationem facilis impedit mollitia obcaecati porro quidem recusandae.');
            $manager->persist($product);
        }
        $manager->flush();
    }
}
