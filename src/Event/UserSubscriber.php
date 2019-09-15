<?php

namespace App\Event;

use App\Entity\UserPreferences;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Environment;

class UserSubscriber implements EventSubscriberInterface
{

//    /**
//     * @var EntityManager
//     */
//    private $entityManager;
//    /**
//     * @var string
//     */
//    private $defaultLocale;

//    public function __construct(
//        Mailer $mailer,
//        EntityManagerInterface $entityManager,
//        string $defaultLocale
//    ) {
//        $this->mailer = $mailer;
//        $this->entityManager = $entityManager;
//        $this->defaultLocale = $defaultLocale;
//    }
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * UserSubscriber constructor.
     * @param \Swift_Mailer $mailer
     * @param Environment $twig
     * @param EntityManagerInterface $entityManager
     * @param string $defaultLocale
     */
    public function __construct(
        \Swift_Mailer $mailer,
        Environment $twig,
        EntityManagerInterface $entityManager,
        string $defaultLocale
    ) {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->entityManager = $entityManager;
        $this->defaultLocale = $defaultLocale;
    }

    public static function getSubscribedEvents()
    {
        return [
            UserRegisterEvent::NAME => 'onUserRegister',
        ];
    }

    public function onUserRegister(UserRegisterEvent $event)
    {

        $preferences = new UserPreferences();
        $preferences->setLocale($this->defaultLocale);

        $user = $event->getRegisteredUser();
        $user->setPreferences($preferences);

        $this->entityManager->flush();


        $body=$this->twig->render('email/registration.html.twig',[
            'user'=>$event->getRegisteredUser()
        ]);
        $message=(new \Swift_Message())
            ->setSubject('Welcome to Hello Bird')
            ->setFrom('Hello_Bird@Bird.com')
            ->setTo($event->getRegisteredUser()->getEmail())
            ->setBody($body,'text/html');
        $this->mailer->send($message);
//        $preferences = new UserPreferences();
//        $preferences->setLocale($this->defaultLocale);
//
//        $user = $event->getRegisteredUser();
//        $user->setPreferences($preferences);
//
//        $this->entityManager->flush();
//
//        $this->mailer->sendConfirmationEmail($event->getRegisteredUser());
    }
}