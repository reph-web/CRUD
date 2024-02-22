<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\CrudType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(ArticleRepository $repo): Response
    {
        $data = $repo -> findAll();
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'data' => $data
        ]);
    }

    /**
     * @Route("/create", name="create", methods={"GET", "POST"})
     */
    public function create(Request  $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(CrudType::class, $article);
        $form -> handleRequest($request);
        if($form->isSubmitted() && $form-> isValid()){
            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash('notice', 'article added !');

            return $this->redirectToRoute('main');
        }
        return $this->render('main/createForm.html.twig', [
            'controller_name' => 'MainController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/update/{id}", name="update", methods={"GET", "POST"})
     */
    public function update($id, Request  $request, EntityManagerInterface $entityManager): Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);
        if($article == null){
            $this->addFlash('notice', 'article id not found !');
            return $this->redirectToRoute('main');
        }
        $form = $this->createForm(CrudType::class, $article);
        $form -> handleRequest($request);
        if($form->isSubmitted() && $form-> isValid()){
            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash('notice', 'article updated !');

            return $this->redirectToRoute('main');
        }
        return $this->render('main/createForm.html.twig', [
            'controller_name' => 'MainController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"GET", "POST"})
     */
    public function delete($id, EntityManagerInterface $entityManager): Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);
        if($article == null){
            $this->addFlash('notice', 'article id not found !');
            return $this->redirectToRoute('main');
        }
        $entityManager->remove($article);
        $entityManager->flush($article);
        $this->addFlash('notice', 'article deleted !');
        return $this->redirectToRoute('main');
    }

}
