<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Tag;
use App\Entity\Post;
use App\Entity\User;
use Faker\Generator;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PostFixture extends BaseFixture implements DependentFixtureInterface, FixtureGroupInterface
{
    private static array $titles = [
        1 => [
            "day" => 1,
            "title" => "De nouveaux titres PUBG sortiraient en 2021 et 2022",
            "image" => "pubg.jpg"
        ],
        2 => [
            "day" => 2,
            "title" => "Pourquoi tout le monde joue à Valheim ?",
            "image" => "valheim.jpg"
        ],
        3 => [
            "day" => 3,
            "title" => "Steam révèle ses plus grands succès de 2020",
            "image" => "steam.jpg"
        ],
        4 => [
            "day" => 4,
            "title" => "Why Asteroids Taste Like Bacon",
            "image" => "asteroide-psyche.jpg"
        ],
        5 => [
            "day" => 5,
            "title" => "Pubg 2 aka New State annoncer fin 2021 et debut 2022",
            "image" => "pubg2.jpg"
        ],
        6 => [
            "day" => 6,
            "title" => "SnowRunner: Le successeur de MudRunner répond aux attentes",
            "image" => "snowrunner.jpg"
        ],
        7 => [
            "day" => 7,
            "title" => "Star Wars Jedi: Fallen Order le jeu pour ce mettre dans la peau d'un apprenti Jedi",
            "image" => "starwars1.jpg"
        ],
        8 => [
            "day" => 8,
            "title" => "Vader Immortal : L'Episode II est désormais disponible",
            "image" => "Vador2.jpg"
        ],
        9 => [
            "day" => 9,
            "title" => "Dear God, they'll be killed on our doorstep! ",
            "image" => "fabio-silva.jpg"
        ],
        10 => [
            "day" => 10,
            "title" => "And there's no trash pickup until January 3rd.",
            "image" => "florian-olivo.jpg"
        ],
        11 => [
            "day" => 11,
            "title" => "Et iusto odio dignissimos ducimus qui blanditiis.",
            "image" => "mohamed-trabelsi.jpg"
        ],
        12 => [
            "day" => 12,
            "title" => "At vero eos et accusamus.",
            "image" => "carl-raw.jpg"
        ],
        13 => [
            "day" => 13,
            "title" => "Accusantium doloremque laudantium, totam rem .",
            "image" => "ella-don.jpg"
        ],
        14 => [
            "day" => 14,
            "title" => "Do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
            "image" => "christian-wiediger.jpg"
        ],
        15 => [
            "day" => 15,
            "title" => "Nihil molestiae consequatur, vel illum qui dolorem eum.",
            "image" => "javier-esteban.jpg"
        ],
        16 => [
            "day" => 16,
            "title" => "Inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.",
            "image" => "olivier-collet.jpg"
        ],
        17 => [
            "day" => 17,
            "title" => "Facere possimus, omnis voluptas assumenda.",
            "image" => "valheim2.jpg"
        ],

    ];

    private static string $content = <<<EOF
Corrupti quos dolores et quas molestias excepturi sint occaecati. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Excepteur sint occaecat cupidatat non proident, sunt in culpa. Inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.

Do eiusmod tempor incididunt ut labore et dolore magna aliqua. __Eaque ipsa quae ab illo inventore veritatis et quasi.__ *Do eiusmod tempor incididunt ut labore et dolore magna aliqua.* Architecto beatae vitae dicta sunt explicabo.

## Totam rem aperiam.

Et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque. Architecto beatae vitae dicta sunt explicabo. Architecto beatae vitae dicta sunt explicabo. Ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.

1. Corrupti quos dolores et quas molestias excepturi sint occaecati.
2. Eaque ipsa quae ab illo inventore veritatis et quasi.
3. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat.

### Et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque.

Laboris nisi ut aliquip ex ea commodo consequat. Nihil molestiae consequatur, vel illum qui dolorem eum. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam. Qui officia deserunt mollit anim id est laborum.

* Qui officia deserunt mollit anim id est laborum.
* Accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo.
* Quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.

Ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit.

Eaque ipsa quae ab illo inventore veritatis et quasi. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam. 
EOF;

    

    public function loadData(ObjectManager $manager): void
    {
        $source = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'post';
        $dest = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
        shell_exec("cp -R $source $dest");

        foreach (self::$titles as $key => $value) {
            $path = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'post' . DIRECTORY_SEPARATOR . $value['image'];
            // dump($path);
            $filename = $value['image'];
            $post = new Post();
            $post
                ->setTitle($value['title'])
                ->setContent(self::$content);

            /** @var User $user */
            $user = $this->getRandomReference('users');

            /** @var Category $category */
            $category = $this->getReference('category_'.$this->faker->numberBetween(1,8));

            $post
                ->setCreatedAt($this->faker->dateTimeBetween('-17 day', 'now'))
                ->setOnline($this->faker->boolean(75))
                ->setUser($user)
                ->setCategory($category)
                ->setImageFile(
                    new UploadedFile(
                        $path,
                        $filename,
                        null,
                        null,
                        true
                    )
                )
            ;
            // dump($post);
            /** @var Tag $tag */
            $tag = $this->getReference('tag_'.$this->faker->numberBetween(1,12));
            for ($i = 1; $i < rand(5,12); $i++) {
                $post->addTag($tag);
            }

            $manager->persist($post);
            $this->addReference('post_'. $key, $post);
        }

        $manager->flush();
    }
    public static function getGroups(): array
    {
        return ['posts'];
    }

    public function getDependencies(): array
    {
        return [
            UserFixture::class,
            CategoryFixture::class,
            TagFixture::class,
        ];
    }
}
