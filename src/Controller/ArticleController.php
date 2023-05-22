<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticleAjax;
use App\Form\ArticleType;
use App\Repository\ArticleAjaxRepository;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


//#[Route('/article')]

class ArticleController extends AbstractController
{
    #[Route('/index', name: 'app_article_index')]
    public function index(ArticleRepository $articleRepository): Response
    {

        $data=$articleRepository->findAll();

        return $this->render('article/index.html.twig', [
            'data' => $data,
        ]);
    }


    #[Route('/saveArt', name: 'app_article_save')]
    public function save(ArticleRepository $articleRepository, Request $request): Response
    {

        $article=new Article();
        $form=$this->createForm(ArticleType::class,$article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            
            $articleRepository->save($article,flush:true);
            return $this->redirectToRoute("app_article_index");
        }

        return $this->render('article/save.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/modif/{id}', name: 'app_article_modif')]
    public function modif(Article $article,ArticleRepository $articleRepository, Request $request): Response
    {


        $form=$this->createForm(ArticleType::class,$article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $updateDate=new \DateTime();
            $article->setDateMiseaJour($updateDate);
            $articleRepository->save($article,flush:true);
            return $this->redirectToRoute("app_article_index");
        }

        return $this->render('article/save.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_article_delete')]
    public function delete(Article $article, ArticleRepository $articleRepository): Response
    {

        $articleRepository->remove($article);

        return $this->redirectToRoute("app_article_index");
    }

    #[Route('/article/save', name: 'app_article_ajax_form',methods:['GET'])]
    public function ajouterar()
    {
        return $this->render("article/ajaxForm.html.twig");
    }


    #[Route('/article/save', name: 'app_article_ajax_save', methods:['POST'])]
    public function AjaxSave(Request $request,ArticleAjaxRepository $articleAjaxRepository)
    {

        if ($request->isXMLHTTPRequest()) {
            $titre=$request->get('title');
            $contenue=$request->get('contenu');
    
            $ajaxArticle= new ArticleAjax();
    
            $ajaxArticle->setTitle($titre);
            $ajaxArticle->setContenu($contenue);
    
           $articleAjaxRepository->save($ajaxArticle,flush:true);
        
        }
     return new JsonResponse(['message'=>' l\'article a été bien enregistrer ']);
    }



}

