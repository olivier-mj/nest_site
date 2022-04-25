<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Post;
use App\Entity\User;
use Faker\Generator;
use App\Entity\Comment;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentFixture extends BaseFixture implements DependentFixtureInterface, FixtureGroupInterface
{

    public function loadData(ObjectManager $manager): void
    {
        $nbrComments = 15;
        $this->referenceIndex['comments'] = $nbrComments;

        $this->createMany($nbrComments,'comments', function ($it) {
            /** @var User $user */
            $user = $this->getRandomReference('users');

            /** @var Post $post */
            $post = $this->getReference('post_'.$this->faker->numberBetween(1,17));

            $comment = new Comment();
            $comment
                ->setContent('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi beatae commodi 
                consequuntur dicta dignissimos est eum eveniet expedita libero maiores molestiae nam quasi rem sint sit, 
                ut velit vero voluptates!')
                ->setIpAddress($this->faker->ipv4)
                ->setIsSpam($this->faker->boolean(45))
                ->setPost($post)
                ->setUser($user);

            return $comment;
        });
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            PostFixture::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['comments'];
    }

    
}
