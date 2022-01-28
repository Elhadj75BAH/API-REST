<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Product;
use App\Entity\User;
use Bezhanov\Faker\Provider\Commerce;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder=$encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new Commerce($faker));
        for($p = 0; $p<25; $p++){
            $product = new Product();
            $product->setName($faker->productName)
                ->setDescription($faker->paragraph())
                ->setImage($faker->imageUrl)
                ->setPrice(mt_rand(100 , 600));
            $manager->persist($product);
        }
        $clients = [];
        for($c =0; $c<10; $c++){
            $client = new Client();
            $hash = $this->encoder->encodePassword($client,'password');
            $client ->setName($faker->company())
                ->setEmail("client$c@gmail.com")
                ->setPassword($hash)
                ->setRoles(['ROLE_OPERATOR'])
                ->setLogo($faker->imageUrl())
            ;
            $manager->persist($client);
            $clients[]= $client;
        }


         for($u = 0; $u<15; $u++ ){
            $user = new User();
            $user->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setEmail("user$u@user.fr")
                ->setAvatar($faker->imageUrl())
                ->setClient($clients [array_rand($clients )])
            ;

            $manager->persist($user);
        }

        $manager->flush();
    }

}
