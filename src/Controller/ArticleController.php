<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticleAjax;
use App\Entity\Categorie;
use App\Form\ArticleType;
use App\Repository\ArticleAjaxRepository;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use SebastianBergmann\CodeCoverage\Report\Html\Renderer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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


    #[Route('/profile/{id}', name: 'app-user-profile')]
    public function profile(Request $request,UserRepository $userRepository): Response
    {

        $id=$request->get('id');
        $profile=$userRepository->findBy(['id'=>$id]);

        
        
        return $this->render('article/profile.html.twig', [
            'profile'=>$profile

        ]);
    }




    #[Route('/saveArt', name: 'app_article_save')]
    public function save(Security $security,ArticleRepository $articleRepository, Request $request): Response
    {
        $user=$security->getUser();
    
        $article=new Article();
        $form=$this->createForm(ArticleType::class,$article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $article->setUser($user);
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

    #[Route('/article/saveForm', name: 'app_article_ajax_form',methods:['GET'])]
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

    #[Route('/article/blog', name: 'app_article_blog')]
    public function blog(ArticleRepository $articleRepository ,CategorieRepository $categorieRepository): Response
    {
        $data=$articleRepository->findAll();
        
        $cat=$categorieRepository->findAll();

        return $this->render('article/blog.html.twig', [
            'data' => $data,
            'Categories'=>$cat,
        ]);
    }

    #[Route('/article/blogListe/{id}', name: 'app_article_blogListe')]
    public function blogListe(Categorie $categorie, ArticleRepository $articleRepository ,CategorieRepository $categorieRepository): Response
    {
        $data=$articleRepository->findBy(['categorie'=>$categorie]);
        $cat=$categorieRepository->findAll();

        return $this->render('article/blogListe.html.twig', [
            'data' => $data,
            'Categories'=>$cat,
        ]);
    }

    #[Route('/article/like/{id}', name: 'app_article_like',methods:['GET'])]
    public function like(Article $article,Request $request, ArticleRepository $articleRepository)
    {

        if ($request->isXmlHttpRequest()) {

            $now=$article->getNbrlike();
            $article->setNbrlike($now+1);
            $articleRepository->save($article,flush:true);


        }

        return new JsonResponse(['message'=>' le like a été bien enregistrer ',
            'nbrLike' => $article->getNbrlike(),
            'nbrDislike' => $article->getNbrdislike(),
        
                ]);
        
       
    }

    #[Route('/article/dislike/{id}', name: 'app_article_dislike', methods:['GET'])]
    public function dislike(Article $article,Request $request, ArticleRepository $articleRepository): Response
    {

        if ($request->isXmlHttpRequest()) {

            $now=$article->getNbrdislike();
            $article->setNbrdislike($now+1);
            $articleRepository->save($article,flush:true);


        }

        return new JsonResponse(['message'=>' le like a été bien enregistrer ',
            'nbrLike' => $article->getNbrlike(),
            'nbrDislike' => $article->getNbrdislike(),
        
                ]);
        
    }

    










//    #[Route('/test',name:'app_test')]
//     public function exo(Request $request){

//         //$nom=$request->get('nom');
//         $nom=$request->query->get('nom');
//         dd($nom);
//         return $this->render("article/test.html.twig");
//     }

//     #[Route('/session/add',name:'app_add_session')]
//     public function add_session(Request $request, SessionInterface $sessionInterface){

//        $session= $sessionInterface->set('name','Teddy');


//         // //$nom=$request->get('nom');
//         // $nom=$request->query->get('nom');
//         // dd($nom);
//         //return  new Response();
//        return $this->render("article/add_session.html.twig",['session'=>$session]);
//     }

//     #[Route('/session/get',name:'app_get_session')]
//     public function get_session(Request $request, SessionInterface $sessionInterface){

//         if ($sessionInterface->has('name')) {
//              $name=$sessionInterface->get('name');
//             dd($name);
//             # code...
//         }



//         // //$nom=$request->get('nom');
//         // $nom=$request->query->get('nom');
//         // dd($nom);
//         return $this->render("article/get_session.html.twig");
//     }

//     #[Route('/logger',name:'app_logger_session')]
//     public function logger(LoggerInterface $log, ArticleAjaxRepository $articleAjaxRepository, Request $request){

//         $article=new ArticleAjax();

//         $titre=$request->get('title');
//         $contenue=$request->get('contenu');

//         $ajaxArticle= new ArticleAjax();

//         $ajaxArticle->setTitle($titre);
//         $ajaxArticle->setContenu($contenue);

//        $articleAjaxRepository->save($article,flush:true);

       
//        $log->debug($article->getId()." l'article a été enregistrer");

      

//         return $this->render("article/get_session.html.twig");
//     }


}

