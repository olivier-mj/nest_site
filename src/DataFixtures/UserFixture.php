<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use Doctrine\ORM\EntityManager;
use App\DataFixtures\BaseFixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends BaseFixture implements FixtureGroupInterface
{
    private UserPasswordHasherInterface $passwordHasher;
    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface  $entityManager
    ) {
        parent::__construct($entityManager);
        $this->passwordHasher = $passwordHasher;
    }


    public function loadData(ObjectManager $manager): void
    {
        $userAdmin = new User();
        $userAdmin
            ->setEmail('olivier@donpadre.fr')
            ->setPassword(
                $this->passwordHasher->hashPassword(
                    $userAdmin,
                    'zama9zle'
                )
            )
            ->setRoles(['ROLE_ADMIN'])
            ->setUsername('donpadre')
            ->setNickname('donpadre')
            ->setDescription('Web développeur et blogueur originaire de la Guadeloupe, Malouin par adoption. 
            Amateur de Pop-culture, du Web, de High-tech, de culture japonais & de bonne cuisine.')
            ->setFacebook('donpadre.fr')
            ->setTwitter('don_padre')
            ->setTwitch('donp971')
            ->setSteam('donp971')
            ->setYoutube('https://www.youtube.com/channel/UCsCjtcyeAXeHxquFlAV64bA')
            ->setInstagram('don_padre');


        $manager->persist($userAdmin);


        $user = new User();
        $user
            ->setEmail('user@email.com')
            ->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    'password'
                )
            )
            ->setRoles(['ROLE_USER'])
            ->setUsername('johndoe')
            ->setNickname('johndoe');

        $manager->persist($user);

        $nbrUsers = 9;

        $this->referenceIndex['users'] = $nbrUsers;

        /** @phpstan-ignore-next-line */
        if (!empty($nbrUsers)) {
            $this->createMany($nbrUsers, 'users', function ($it) {
                $firstname = $this->faker->firstName;
                $lastname = $this->faker->lastName;
                $username = $firstname . '.' . $lastname;
                $user = new User();
                $user
                    ->setEmail(strtolower($username) . '@' . $this->faker->safeEmailDomain)
                    ->setUsername(strtolower($username))
                    ->setNickname(strtolower($username))
                    ->setPassword(
                        $this->passwordHasher->hashPassword(
                            $user,
                            '123456789'
                        )
                    )
                    ->setRoles(['ROLE_USER'])
                    ->setDescription('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quod eos id officiis hic tenetur.');

                //            $imagePath = $this->faker->image(sys_get_temp_dir(), 140, 140, 'people');
                //            if (!empty($imagePath)) {
                //                $uploadFile = new UploadedFile($imagePath, 'user.png', 'png', null, true);
                //
                //                $image = new Image();
                //                $image->setImageFile($uploadFile);
                //
                //                $user->setImage($image);
                //            }
                return $user;
            });
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['users'];
    }

    protected function createMany(int $count, string $groupName, callable $factory): void
    {
        for ($it = 1; $it <= $count; ++$it) {
            $entity = $factory($it);

            $this->entityManager->persist($entity);

            $this->addReference(sprintf('%s_%d', $groupName, $it), $entity);
        }
    }
}
