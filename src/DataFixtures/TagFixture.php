<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class TagFixture extends BaseFixture implements FixtureGroupInterface
{
    private static array $tags = [
        1 => [
            "title" => 'Pubg',
        ],
        2 => [
            "title" => 'Fps',
        ],
        3 => [
            "title" => 'Mmorpg',
        ],
        4 => [
            "title" => 'Rts',
        ],
        5 => [
            "title" => 'Battle Royal',
        ],
        6 => [
            "title" => 'Action',
        ],
        7 => [
            "title" => 'Survivor',
        ],
        8 => [
            "title" => 'Tech',
        ],
        9 => [
            "title" => "Movies",
        ],
        10 => [
            "title" => 'Cinema',
        ],
        11 => [
            "title" => 'Geek & Nerd',
        ],
        12 => [
            "title" => 'Adventure',
        ],
    ];

    public static function getGroups(): array
    {
        return ['tags'];
    }

    public function loadData(ObjectManager $manager): void
    {
        foreach (self::$tags as $key => $value) {
            $tag = new Tag();
            $tag
                ->setName($value['title'])
            ;
            $manager->persist($tag);
            $this->addReference('tag_'. $key, $tag);
        }

        $manager->flush();
    }


}
