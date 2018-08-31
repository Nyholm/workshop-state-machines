<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\UserProfile;
use App\Form\EmailAddressType;
use App\Form\FavoriteColorType;
use App\Form\NameType;
use App\Form\TwitterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SignupController extends Controller
{
    private $em;

    /**
     *
     * @param $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/signup/start", name="signup_start")
     */
    public function start(Request $request)
    {
        $user = new UserProfile();

        $form = $this->createForm(NameType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($user);
            $this->em->flush();

            return $this->redirectToRoute('signup_email', ['id'=>$user->getId()]);
        }

        return $this->render('signup/start.html.twig', [
            'form'=>$form->createView(),
        ]);
    }


    /**
     * @Route("/signup/email/{id}", name="signup_email")
     */
    public function email(Request $request, UserProfile $user)
    {
        $form = $this->createForm(EmailAddressType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($user);
            $this->em->flush();

            return $this->redirectToRoute('signup_twitter', ['id'=>$user->getId()]);
        }

        return $this->render('signup/email.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/signup/twitter/{id}", name="signup_twitter")
     */
    public function twitter(Request $request, UserProfile $user)
    {
        $form = $this->createForm(TwitterType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($user);
            $this->em->flush();

            return $this->redirectToRoute('signup_color', ['id'=>$user->getId()]);
        }

        return $this->render('signup/twitter.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/signup/color/{id}", name="signup_color")
     */
    public function color(Request $request, UserProfile $user)
    {
        $form = $this->createForm(FavoriteColorType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($user);
            $this->em->flush();

            return $this->redirectToRoute('signup_done', ['id'=>$user->getId()]);
        }

        return $this->render('signup/color.html.twig', [
            'form'=>$form->createView(),
        ]);
    }
    /**
     * @Route("/signup/done/{id}", name="signup_done")
     */
    public function done(UserProfile $user)
    {
        return $this->render('signup/done.html.twig', [
            'user'=>$user,
        ]);
    }
}