<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\DataFixtures\BaseFixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class CategoryFixture extends BaseFixture implements FixtureGroupInterface
{
    private static array $categories = [
        1 => [
            'title' => 'Actualité',
        ],
        2 => [
            'title' => 'Jeux vidéo',
        ],
        3 => [
            'title' => 'Tech',
        ],
        4 => [
            'title' => 'Cinéma',
        ],
        5 => [
            'title' => 'Comic & Manga',
        ],
        6 => [
            'title' => 'Nerd & Geek',
        ],
        7 => [
            'title' => 'Divers',
        ],
        8 => [
            'title' => 'Events',
        ],
    ];
    

    public function loadData(ObjectManager $manager): void
    {
        foreach (self::$categories as $key => $value) {
            $title = $value['title'];

            $category = new Category();
            $category
                ->setName($title)
                ->setDescription('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quod eos id officiis hic tenetur.');

            $manager->persist($category);

            $this->addReference('category_' . $key, $category);
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['categories'];
    }
}
