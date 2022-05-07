<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;

abstract  class BaseFixture extends Fixture implements FixtureInterface
{
    protected Generator $faker;

    protected ObjectManager $manager;

    protected array $referenceIndex = [];

    protected EntityManagerInterface $entityManager;

    abstract protected function loadData(ObjectManager $manager): void;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->faker = Factory::create();
        $this->referenceIndex = [];
        $this->loadData($manager);
    }

    protected function createMany(int $count, string $groupName, callable $factory): void
    {
        for ($it = 1; $it <= $count; ++$it) {
            $entity = $factory($it);

            $this->entityManager->persist($entity);

            $this->addReference(sprintf('%s_%d', $groupName, $it), $entity);
        }
    }

    protected function getRandomReference(string $groupName): object
    {
        $referenceIndex = [];

        foreach ($this->referenceRepository->getReferences() as $label => $object) {
            $innerLabel = explode('_', $label)[0];
            if (!isset($referenceIndex[$innerLabel])) {
                $referenceIndex[$innerLabel] = 0;
            }
            $referenceIndex[$innerLabel]++;
        }

        $referenceParam = sprintf('%s_%d', $groupName, rand(1, $referenceIndex[$groupName]));
        return $this->getReference($referenceParam);
    }

    protected function getRandomReferences(string $groupName, int $count): array
    {
        $references = [];
        while (count($references) < $count) {
            $references[] = $this->getRandomReference($groupName);
        }

        return $references;
    }
}
