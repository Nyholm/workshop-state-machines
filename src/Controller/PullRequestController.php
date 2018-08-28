<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\PullRequest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/pr")
 */
class PullRequestController extends Controller
{
    /**
     * @Route("", name="pull_request_index")
     */
    public function indexAction()
    {
        $pullRequests = $this->get('doctrine')->getRepository(PullRequest::class)->findAll();

        return $this->render('pull_request/index.html.twig', [
            'pull_requests' => $pullRequests,
        ]);
    }

    /**
     * @Route("/create", methods={"POST"}, name="pull_request_create")
     */
    public function createAction(Request $request)
    {
        $pullRequest = new PullRequest($request->request->get('name', 'First street'));

        $em = $this->get('doctrine')->getManager();
        $em->persist($pullRequest);
        $em->flush();

        return $this->redirect($this->generateUrl('pull_request_show', ['id' => $pullRequest->getId()]));
    }

    /**
     * @Route("/show/{id}", name="pull_request_show")
     */
    public function showAction(PullRequest $pullRequest)
    {
        // TODO add security to verify that the pull request has been submitted. If it is not submitted
        // it should only be accessable by the author. (We might need a security voter

        return $this->render('pull_request/show.html.twig', [
            'pull_request' => $pullRequest,
        ]);
    }

    /**
     * @Route("/submit/{id}", methods={"GET"}, name="pull_request_submit")
     */
    public function submitAction(PullRequest $pullRequest)
    {
        if (!$pullRequest->getPreSubmit()) {
            throw new BadRequestHttpException('You cannot submit a pull request that is already submitted. ');
        }

        $pullRequest->setPreSubmit(false);
        $pullRequest->setOpen(true);

        $em = $this->get('doctrine')->getManager();
        $em->persist($pullRequest);
        $em->flush();

        return $this->redirectToRoute('pull_request_show', ['id'=>$pullRequest->getId()]);
    }

    /**
     * @Route("/close/{id}", methods={"GET"}, name="pull_request_close")
     */
    public function closeAction(PullRequest $pullRequest)
    {
        if (!$pullRequest->getOpen()) {
            throw new BadRequestHttpException('You cannot close a pull request that not open.');
        }

        $pullRequest->setClosed(true);
        $pullRequest->setOpen(false);

        $em = $this->get('doctrine')->getManager();
        $em->persist($pullRequest);
        $em->flush();

        return $this->redirectToRoute('pull_request_show', ['id'=>$pullRequest->getId()]);
    }

    /**
     * @Route("/reopen/{id}", methods={"GET"}, name="pull_request_reopen")
     */
    public function reopenAction(PullRequest $pullRequest)
    {
        if (!$pullRequest->getClosed()) {
            throw new BadRequestHttpException('You cannot open a pull request that not closed.');
        }

        $pullRequest->setClosed(false);
        $pullRequest->setOpen(true);

        $em = $this->get('doctrine')->getManager();
        $em->persist($pullRequest);
        $em->flush();

        return $this->redirectToRoute('pull_request_show', ['id'=>$pullRequest->getId()]);
    }

    /**
     * @Route("/lock/{id}", methods={"GET"}, name="pull_request_lock")
     */
    public function lockAction(PullRequest $pullRequest)
    {
        if ($pullRequest->getPreSubmit()) {
            throw new BadRequestHttpException('You cannot lock a pull request that not submitted.');
        }

        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new BadRequestHttpException('Only admin can lock a pull request');
        }

        $pullRequest->setLocked(true);

        $em = $this->get('doctrine')->getManager();
        $em->persist($pullRequest);
        $em->flush();

        return $this->redirectToRoute('pull_request_show', ['id'=>$pullRequest->getId()]);
    }

    /**
     * @Route("/merge/{id}", methods={"GET"}, name="pull_request_merge")
     */
    public function mergeAction(PullRequest $pullRequest)
    {
        if (!$pullRequest->getOpen()) {
            throw new BadRequestHttpException('You cannot merge a pull request that not open.');
        }

        // TODO verify that the current user has permission to merge. Is that user admin or maintainer?

        $pullRequest->setMerged(true);
        $pullRequest->setOpen(false);

        $em = $this->get('doctrine')->getManager();
        $em->persist($pullRequest);
        $em->flush();

        return $this->redirectToRoute('pull_request_show', ['id'=>$pullRequest->getId()]);
    }

    /**
     * @Route("/comment/{id}", methods={"POST"}, name="pull_request_comment")
     */
    public function commentAction(PullRequest $pullRequest)
    {
        if ($pullRequest->getLocked()) {
            // TODO maybe only the author and admin can comment
            throw new BadRequestHttpException('You cannot comment on a locked pull request.');
        }

        // There is no need to implement this action. That is out of scope of the workshop.

        $em = $this->get('doctrine')->getManager();
        $em->persist($pullRequest);
        $em->flush();

        return $this->redirectToRoute('pull_request_show', ['id'=>$pullRequest->getId()]);
    }
}
