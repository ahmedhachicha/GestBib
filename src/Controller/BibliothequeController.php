<?php

namespace App\Controller;

use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Entity\Editeur;
use App\Entity\Livre;
use App\Form\SearchForm;
use App\Repository\AuteurRepository;
use App\Repository\EditeurRepository;
use App\Repository\LivreRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class BibliothequeController extends AbstractController
{
    /**
     * @Route("/bibliotheque", name="bibliotheque")
     */

    public function index(LivreRepository $livreRepository): Response
    {
        return $this->render('bibliotheque/basebiblio.html.twig', [

        ]);
    }
    /**
     * @Route("/bibliotheque/livre_section", name="livresection")
     */
    public function livresection(LivreRepository $livreRepository,EditeurRepository $editeurRepository
        , AuteurRepository $auteurRepository,CategorieRepository $categorieReposiory): Response
    {

        return $this->render('bibliotheque/books.html.twig', [
            'titre'=>'Livre',
            'livres' => $livreRepository->findAll(),
           // 'editeurs' => $editeurRepository->findAll(),
            //'auteurs' => $auteurRepository->findAll(),
            'categories' => $categorieReposiory->findAll(),
          //  'livres' => $livreRepository->findByCategorie($idcat),

        ]);
    }

    /**
     * @Route("/bibliotheque/livre-{id}", name="livreshow", methods={"GET"})
     */
    public function show(Livre $livre): Response
    {
        return $this->render('bibliotheque/bibliodetails.html.twig', [
            'titre'=>'Livre',
            'livre' => $livre,
        ]);
    }
    /**
     * @Route("/bibliotheque/livre_section/search", name="livrefiltre")
     */
    public function livresfiltre(LivreRepository $livreRepository,EditeurRepository $editeurRepository
        , AuteurRepository $auteurRepository,CategorieRepository $categorieReposiory,Request $request): Response
    {
        $search = $request->request->get('search');
       // $livreslist = $livreRepository->filtreByBook($search);
        //dd($livreslist);


        return $this->render('bibliotheque/books.html.twig', [
            //dd($search),
            'titre' => 'Livre',
            'livres' => $livreRepository->filtreByBook($search),
          //  'editeurs' => $editeurRepository->findAll(),
           // 'auteurs' => $auteurRepository->findAll(),
            'categories' => $categorieReposiory->findAll(),
            //  'livres' => $livreRepository->findByCategorie($idcat),

        ]);
    }
    /**
     * @Route("/bibliotheque/livre_section/selected", name="livreselect")
     */
    public function livresselect(LivreRepository $livreRepository,EditeurRepository $editeurRepository
        ,CategorieRepository $categorieReposiory,Request $request): Response
    {

       $cat = $request->request->get('cat');
       // $edi = $request->request->get('edi');



        return $this->render('bibliotheque/books.html.twig', [
            //dd($cat,$edi),
            'titre' => 'Livre',
            'livres' => $livreRepository->filtreBySelected($cat),
           // 'editeurs' => $editeurRepository->findAll(),
            'categories' => $categorieReposiory->findAll(),

        ]);
    }



}
