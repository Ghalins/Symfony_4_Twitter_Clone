<?php


namespace App\DataFixtures;

use App\Entity\MicroPost;
use App\Entity\User;
use App\Entity\UserPreferences;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private const USERS = [
        [
            'username' => 'wajih_ghali',
            'email' => 'wajih_ghali@gmail.com',
            'password' => 'wajih123',
            'fullName' => 'Wajih Ghali',
            'roles' => [User::ROLE_USER]
        ],
        [
            'username' => 'john_doe',
            'email' => 'john_doe@doe.com',
            'password' => 'john123',
            'fullName' => 'John Doe',
            'roles' => [User::ROLE_USER]
        ],
        [
            'username' => 'rob_smith',
            'email' => 'rob_smith@smith.com',
            'password' => 'rob123',
            'fullName' => 'Rob Smith',
            'roles' => [User::ROLE_USER]
        ],
        [
            'username' => 'marry_gold',
            'email' => 'marry_gold@gold.com',
            'password' => 'marry123',
            'fullName' => 'Marry Gold',
            'roles' => [User::ROLE_USER]
        ],
        [
            'username' => 'super_admin',
            'email' => 'super_admin@admin.com',
            'password' => 'admin123',
            'fullName' => 'Micro Admin',
            'roles' => [User::ROLE_ADMIN]
        ],
    ];

    private const POST_TEXT = [
        'Hello, how are you?',
        'It\'s nice sunny weather today',
        'I need to buy some ice cream!',
        'I wanna buy a new car',
        'There\'s a problem with my phone',
        'I need to go to the doctor',
        'What are you up to today?',
        'Did you watch the game yesterday?',
        'How was your day?'
    ];

    private const LANGUAGES = [
        'en',
        'fr'
    ];

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadMicroPosts($manager);
    }

    private function loadMicroPosts(ObjectManager $manager)
    {
        for ($i = 0; $i < 30; $i++) {
            $microPost = new MicroPost();
            $microPost->setText(
                self::POST_TEXT[rand(0,count(self::POST_TEXT)-1)]
            );
            $date=new \DateTime();
            $date->modify('-'.rand(0,10 ).' day ');
            $microPost->setTime($date);
            $microPost->setUser($this->getReference(
                self::USERS[rand(0,count(self::USERS)-1)]['username']
            ));
            $manager->persist($microPost);
        }

        $manager->flush();
    }

    private function loadUsers(ObjectManager $manager)
    {
        foreach (self::USERS as $userData) {
            $user = new User();
            $user->setUsername($userData['username']);
            $user->setFullName($userData['fullName']);
            $user->setEmail($userData['email']);
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $userData['password']
                )
            );
            $user->setRoles($userData['roles']);
            $user->setEnabled(true);

            $this->addReference(
                $userData['username'],
                $user
            );
            $preferences= new UserPreferences();
            $preferences->setLocale(self::LANGUAGES[rand(0, 1)]);
            $user->setPreferences($preferences);
//            $manager->persist($preferences); or cascade persist
            $manager->persist($user);
        }

        $manager->flush();
    }
}

//namespace App\DataFixtures;
//
//use App\Entity\MicroPost;
//use App\Entity\User;
//use Doctrine\Bundle\FixturesBundle\Fixture;
//use Doctrine\Common\Persistence\ObjectManager;
//use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
//
//class AppFixtures extends Fixture
//{
//    /**
//     * @var UserPasswordEncoderInterface
//     */
//    private $passwordEncoder;
//
//    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
//    {
//
//        $this->passwordEncoder = $passwordEncoder;
//    }
//
//    public function load(ObjectManager $manager)
//    {
//        $this->loadUsers($manager);
//        $this->loadMicroPost($manager);
//    }
//    public function loadMicroPost(ObjectManager $manager)
//    {
//        for ($i=0;$i<10;$i++)
//        {
//            $microPost=new MicroPost();
//            $microPost->setText('Some random text '.rand(0,100));
//            $microPost->setTime(new \DateTime('2018-03-15'));
//            $microPost->setUser($this->getReference('wajih_ghali'));
//            $manager->persist($microPost);
//        }
//
//        $manager->flush();
//    }
//
//    public function loadUsers(ObjectManager $manager)
//    {
//
//        $user=new User();
//        $user->setUsername('wajih_ghali');
//        $user->setEmail('wajih_ghali@gmail.com');
//        $user->setFullName('wajih ghali');
//        $user->setPassword($this->passwordEncoder->encodePassword(
//            $user,'wajih123'
//            ));
//
//        $this->addReference('wajih_ghali',$user);
//
//        $manager->persist($user);
//        $manager->flush();
//    }
//}
