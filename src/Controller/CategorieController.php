<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categorie')]
class CategorieController extends AbstractController
{
    #[Route('/save', name: 'app_categorie_save')]
    public function save(CategorieRepository $categorieRepository ,Request $request): Response
    {
        $categorie=new Categorie();
        $form=$this->createForm(CategorieType::class,$categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $categorieRepository->save($categorie,flush:true);
            return $this->redirectToRoute("app_categorie_index");
        }

        

        return $this->render('categorie/save.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/index', name: 'app_categorie_index')]
    public function index(CategorieRepository $categorieRepository): Response
    {

        $data=$categorieRepository->findAll();
        
        return $this->render('categorie/index.html.twig', [
            'data' => $data,
        ]);
    }

    #[Route('/modif/{id}', name: 'app_categorie_modif')]
    public function modif(Categorie $categorie, CategorieRepository $categorieRepository ,Request $request): Response
    {
        
        $form=$this->createForm(CategorieType::class,$categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $categorieRepository->save($categorie,flush:true);
            return $this->redirectToRoute("app_categorie_index");
        }

        

        return $this->render('categorie/save.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/delete/{id}', name: 'app_categorie_delete')]
    public function delete(Categorie $categorie, CategorieRepository $categorieRepository): Response
    {

        $categorieRepository->remove($categorie);
        
        return $this->redirectToRoute("app_categorie_index");
            
    }


}
