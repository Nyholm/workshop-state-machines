<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\UserProfile;
use App\Form\EmailAddressType;
use App\Form\FavoriteColorType;
use App\Form\NameType;
use App\Form\TwitterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SignupController extends AbstractController
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
            $sm = $this->get('state_machine.signup');
            $sm->apply($user, 'next');
            $this->em->persist($user);
            $this->em->flush();

            return $this->getNextStepRedirect($user);
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
        if (null !== $response = $this->verifyState($user, 'email')) {
            return $response;
        }
        $form = $this->createForm(EmailAddressType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $sm = $this->get('state_machine.signup');
            $sm->apply($user, 'next');
            $this->em->persist($user);
            $this->em->flush();

            return $this->getNextStepRedirect($user);
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
        if (null !== $response = $this->verifyState($user, 'twitter')) {
            return $response;
        }
        $form = $this->createForm(TwitterType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $sm = $this->get('state_machine.signup');
            $sm->apply($user, 'next');
            $this->em->persist($user);
            $this->em->flush();

            return $this->getNextStepRedirect($user);
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
        if (null !== $response = $this->verifyState($user, 'color')) {
            return $response;
        }
        $form = $this->createForm(FavoriteColorType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $sm = $this->get('state_machine.signup');
            $sm->apply($user, 'next');
            $this->em->persist($user);
            $this->em->flush();

            return $this->getNextStepRedirect($user);
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
        if (null !== $response = $this->verifyState($user, 'done')) {
            return $response;
        }

        return $this->render('signup/done.html.twig', [
            'user'=>$user,
        ]);
    }

    private function verifyState(UserProfile $user, $currentPlace)
    {
        $sm = $this->get('state_machine.signup');
        if (!$sm->getMarkingStore()->getMarking($user)->has($currentPlace)) {
            $metadata = $sm->getMetadataStore();
            $place = array_keys($sm->getMarkingStore()->getMarking($user)->getPlaces())[0];
            $route = $metadata->getPlaceMetadata($place)['route'];

            return $this->redirectToRoute($route, ['id'=>$user->getId()]);
        }
    }

    private function getNextStepRedirect(UserProfile $user): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $sm = $this->get('state_machine.signup');
        $metadata = $sm->getMetadataStore();
        $place = array_keys($sm->getMarkingStore()->getMarking($user)->getPlaces())[0];
        $route = $metadata->getPlaceMetadata($place)['route'];

        return $this->redirectToRoute($route, ['id'=>$user->getId()]);
    }
}
