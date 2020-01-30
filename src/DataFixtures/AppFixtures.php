<?php

namespace App\DataFixtures;

use App\Entity\Persona;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private $faker;
    public function __construct() {
        $this->faker = Factory::create();
    }
    
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 50; $i++){
            $persona = new Persona();
            $persona->setName($this->faker->firstName.' '.$this->faker->lastName);
            $persona->setAge($this->faker->numberBetween(0,100));
            $persona->setEmail($this->faker->email);
            $manager->persist($persona);
        }
        $manager->flush();
    }
}
