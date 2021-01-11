<?php

namespace App\Controller;

use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Entity\Editeur;
use App\Entity\Livre;
use App\Entity\User;
//use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    /**
     * @Route("/admin", name="accueil")
     */
    public function index()
    {
        $nbAuteurs = count($this->getDoctrine()->getRepository(Auteur::class)
            ->findAll());
        $nbCategories = count($this->getDoctrine()->getRepository(Categorie::class)
            ->findAll());
        $nbEditeurs = count($this->getDoctrine()->getRepository(Editeur::class)
            ->findAll());
        $nbLivers = count($this->getDoctrine()->getRepository(Livre::class)
            ->findAll());
        $nbUsers = count($this->getDoctrine()->getRepository(User::class)
            ->findAll());
        //$currentuser = $this->getDoctrine()->getRepository(User::class);






        return $this->render('accueil/accueil.html.twig', [
            'titre'=>'Accueil',
            'nbAuteurs' => $nbAuteurs,
            'nbCategories' => $nbCategories,
            'nbEditeurs' => $nbEditeurs,
            'nbLivers' => $nbLivers,
            'nbUsers' => $nbUsers,

            //'currentuser' => $currentuser,

        ]);
    }
}
