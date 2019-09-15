<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("is_granted('ROLE_USER')")
 * @Route("/following")
 */
class FollowingController extends AbstractController
{
    /**
     * @Route("/follow/{id}",name="following_follow")
     * @param User $userToFollow
     * @return RedirectResponse
     */
    public function follow(User $userToFollow)
    {
        /** @var User $currentUser */
        //getUser is a method extended from AbstractController class
        //to fetch the currently authenticated user
        // compared to the micropost we used TokenStorageInterface
        $currentUser=$this->getUser();
        //to prevent user to follow himself with url
        if ($userToFollow->getId() !== $currentUser->getId())
        {
            $currentUser->follow($userToFollow);
            //$currentUser->getFollowing()->add($userToFollow);
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->redirectToRoute('micro_post_user',[
            'username'=>$userToFollow->getUsername()
        ]);
    }

    /**
     * @Route("/unfollow/{id}",name="following_unfollow")
     * @param User $userToUnfollow
     * @return RedirectResponse
     */
    public function unfollow(User $userToUnfollow)
    {
        /** @var User $currentUser */
        $currentUser=$this->getUser();
        if ($userToUnfollow->getId() !== $currentUser->getId())
        {
            $currentUser->getFollowing()->removeElement($userToUnfollow);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->redirectToRoute('micro_post_user',[
            'username'=>$userToUnfollow->getUsername()
        ]);
    }

//    /**
//     * @Route("/following", name="following")
//     */
//    public function index()
//    {
//        return $this->render('following/index.html.twig', [
//            'controller_name' => 'FollowingController',
//        ]);
//    }
}
