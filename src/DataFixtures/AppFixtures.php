<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Comment;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    private $faker;

    //What this hell is happening here? How can we just inject an interface???
    //Where are $passwordHasher's method actually defined?
    //Important: we are injecting a dependency into a constructor so that the object may become a property
    //This makes it so we can use the property later on
    //Then, we don't have to instantiate an object inside a method, which is BAD
    public function __construct(UserPasswordHasherInterface $passwordHasher){
        $this->passwordHasher=$passwordHasher;
        $this->faker=\Faker\Factory::create();
    }
    //This fn def was auto gen by Doctrine, so it is looked for specifically
    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
        $this->loadBlogPosts($manager);
        $this->loadComments($manager);
    }

    public function loadBlogPosts(ObjectManager $manager)
    {
        $user=$this->getReference("admin_user");

        for ($i=0; $i<100; $i++)
        {
            $blogPost=new BlogPost();
            $blogPost->setAuthor($user);
            $blogPost->setContent($this->faker->realText(300));
            $blogPost->setPublished($this->faker->dateTimeThisYear);
            $blogPost->setSlug("a-first-post");
            $blogPost->setTitle($this->faker->realText(30));

            $this->setReference("blog_post_$i", $blogPost);
            $manager->persist($blogPost);
        }
        $manager->flush();
    }

    public function loadComments(ObjectManager $manager)
    {
        for ($i=0; $i<100; $i++)
        {
            $post=$this->getReference("blog_post_$i");
            for ($x=0; $x<rand(1, 10); $x++){
                $n=rand(0,2);
                $author=$this->getReference("admin_user");
                $post=$this->getReference("blog_post_$i");

                $comment=new Comment();
                $comment->setPublished($this->faker->dateTimeThisYear);
                $comment->setAuthor($author);
                $comment->setContent($this->faker->realText(50));
                $comment->setBlogPost($post);

                $manager->persist($comment);
            }
        }
        $manager->flush();

    }

    public function loadUsers(ObjectManager $manager)
    {
        for ($i=0; $i<3; $i++)
        {
            $user=new User();
            $user->setEmail($this->faker->email);
            $user->setName($this->faker->name);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'secret123'));
            $user->setUsername($this->faker->userName);
            $this->setReference("admin_user", $user);
        }

        $manager->persist($user);
        $manager->flush();
    }
}
