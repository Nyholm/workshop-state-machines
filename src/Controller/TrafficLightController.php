<?php

namespace App\Controller;

use App\Entity\TrafficLight;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Workflow\Exception\ExceptionInterface;
use Symfony\Component\Workflow\StateMachine;

/**
 * @Route("/traffic-light")
 */
class TrafficLightController extends AbstractController
{
    private $trafficLightStateMachine;
    private $em;

    public function __construct(EntityManagerInterface $em /*,StateMachine $trafficLightStateMachine*/)
    {
        // $this->trafficLightStateMachine = $trafficLightStateMachine;
        $this->em = $em;
    }


    /**
     * @Route("/", name="traffic_light_index")
     */
    public function indexAction()
    {
        return $this->render('traffic_light/index.html.twig', [
            'traffic_lights' => $this->get('doctrine')->getRepository(TrafficLight::class)->findAll(),
        ]);
    }

    /**
     * @Route("/create", methods={"POST"}, name="traffic_light_create")
     */
    public function createAction(Request $request)
    {
        $task = new TrafficLight($request->request->get('street', 'First street'));

        $this->em->persist($task);
        $this->em->flush();

        return $this->redirect($this->generateUrl('traffic_light_show', ['id' => $task->getId()]));
    }

    /**
     * @Route("/show/{id}", name="traffic_light_show")
     */
    public function showAction(TrafficLight $trafficLight)
    {
        return $this->render('traffic_light/show.html.twig', [
            'traffic_light' => $trafficLight,
        ]);
    }

    /**
     * @Route("/apply-transition/{id}", methods={"POST"}, name="traffic_light_apply_transition")
     */
    public function applyTransitionAction(Request $request, TrafficLight $trafficLight)
    {
        try {
            $this->trafficLightStateMachine
                ->apply($trafficLight, $request->request->get('transition'));

            $this->em->flush();
        } catch (ExceptionInterface $e) {
            $this->addFlash('danger', $e->getMessage());
        }

        return $this->redirect(
            $this->generateUrl('traffic_light_show', ['id' => $trafficLight->getId()])
        );
    }

    /**
     * @Route("/reset-marking/{id}", methods={"POST"}, name="traffic_light_reset_marking")
     */
    public function resetMarkingAction(TrafficLight $task)
    {
        $task->setState(null);
        $this->em->flush();

        return $this->redirect($this->generateUrl('traffic_light_show', ['id' => $task->getId()]));
    }
}
