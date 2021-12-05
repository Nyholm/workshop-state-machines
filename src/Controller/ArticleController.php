<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Workflow\Exception\ExceptionInterface;
use Symfony\Component\Workflow\Workflow;

/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    private $articleWorkflow;
    private $em;

    public function __construct(Workflow $articleWorkflow, EntityManagerInterface $em)
    {
        $this->articleWorkflow = $articleWorkflow;
        $this->em = $em;
    }

    /**
     * @Route("", name="article_index")
     */
    public function indexAction()
    {
        return $this->render('article/index.html.twig', [
            'articles' => $this->get('doctrine')->getRepository('App:Article')->findAll(),
        ]);
    }

    /**
     * @Route("/create", methods={"POST"}, name="article_create")
     */
    public function createAction(Request $request)
    {
        $article = new Article($request->request->get('title', 'title'));

        $em = $this->em;
        $em->persist($article);
        $em->flush();

        return $this->redirect($this->generateUrl('article_show', ['id' => $article->getId()]));
    }

    /**
     * @Route("/show/{id}", name="article_show")
     */
    public function showAction(Article $article)
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/apply-transition/{id}", methods={"POST"}, name="article_apply_transition")
     */
    public function applyTransitionAction(Request $request, Article $article)
    {
        try {
            $this->articleWorkflow->apply($article, $request->request->get('transition'));

            $this->em->flush();
        } catch (ExceptionInterface $e) {
            $this->addFlash('danger', $e->getMessage());
        }

        return $this->redirect(
            $this->generateUrl('article_show', ['id' => $article->getId()])
        );
    }

    /**
     * @Route("/reset-marking/{id}", methods={"POST"}, name="article_reset_marking")
     */
    public function resetMarkingAction(Article $article)
    {
        $article->setMarking([]);
        $this->em->flush();

        return $this->redirect($this->generateUrl('article_show', ['id' => $article->getId()]));
    }
}
