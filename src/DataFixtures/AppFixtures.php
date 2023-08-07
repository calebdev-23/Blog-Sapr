<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;
    private $faker;
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
        $this->faker = Factory::create('fr_FR');
    }
    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
        $this->loadBlogPost($manager);
        $this->loadComments($manager);
    }
    public function loadBlogPost(ObjectManager $manager)
    {


        for ($i=1 ; $i < 100; $i++)
        {
                $user = $this->getReference("user_".rand(1,10));
                $blog_post = new BlogPost();
                $blog_post
                    ->setTitle($this->faker->realText)
                    ->setContent($this->faker->realText)
                    ->setAuthor($user)
                    ->setSlug($this->faker->slug)
                    ->setPublished($this->faker->dateTimeThisYear);
                $this->setReference("blog_post_$i", $blog_post);
                $manager->persist($blog_post);
        }

        $manager->flush();
    }
    public function loadUsers(ObjectManager $manager)
    {
        for ($i = 1; $i < 11; $i++)
        {
            $user = new User();
            $user->setUsername($this->faker->userName)
                ->setName($this->faker->name)
                ->setEmail($this->faker->email)
                ->setPassword($this->hasher->hashPassword($user, 'azerty123' ));

            $this->addReference("user_$i", $user);
            $manager->persist($user);
        }
        $manager->flush();
    }

    public function loadComments(ObjectManager $manager)
    {
        for($i = 1; $i<100; $i++){
                for ($j = 0; $j<rand(1, 10); $j++){
                    $comment = new Comment();
                    $comment->setContent($this->faker->realText)
                        ->setPublished($this->faker->dateTimeThisYear)
                        ->setBlogPost($this->getReference("blog_post_$i"))
                        ->setUser($this->getReference("user_".rand(1,10)));
                    $manager->persist($comment);
                }
        }
        $manager->flush();
    }

}
