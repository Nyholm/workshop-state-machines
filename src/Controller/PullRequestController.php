<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\PullRequest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/pr")
 */
class PullRequestController extends Controller
{
    /**
     * @Route("", name="pull_request_index")
     */
    public function index()
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
        $task = new PullRequest($request->request->get('name', 'First street'));

        $em = $this->get('doctrine')->getManager();
        $em->persist($task);
        $em->flush();

        return $this->redirect($this->generateUrl('pull_request_show', ['id' => $task->getId()]));
    }

    /**
     * @Route("/show/{id}", name="pull_request_show")
     */
    public function showAction(PullRequest $trafficLight)
    {
        return $this->render('pull_request/show.html.twig', [
            'pull_request' => $trafficLight,
        ]);
    }
}
