<?php

namespace App\Controller;

use App\Form\AuteurType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Auteur;
/**
 * @Route("admin/auteur")
 */
class AuteurController extends AbstractController
{
    /**
     * @Route("/index",name="index_auteur")
     */
    public function index()
    {
        $repAuteurs = $this->getDoctrine()->getRepository(Auteur::class);
        $lesAuteurs = $repAuteurs->findAll();

        return $this->render('auteur/index.html.twig', [
            'titre'=>'Auteur',
            'listAuteurs' => $lesAuteurs,
        ]);

    }
    /**
     * @Route("/new",name="new_auteur")
     */
    public function new(Request $request)
    {
        $auteur = new Auteur();

        $form = $this->createForm(AuteurType::class,$auteur);
        $form->add('Valider',SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($auteur);
            $em->flush();
            return $this->redirectToRoute('index_auteur');
        }
        return $this->render('auteur/new.html.twig',
        [
            'titre'=>'Auteur',
            'form' => $form->createView()]);

    }
    /**
     * @Route("/display/{id}",name="display_auteur")
     */
    public function display(int $id =- 1)
    {
        if ($id <= 0)
        {
            return $this->redirectToRoute('index_auteur');
        }
        else
        {
            $repAuteurs = $this->getDoctrine()->getRepository(Auteur::class);
            $unaut = $repAuteurs->findOneBy(['id'=> $id]);
            return $this->render('auteur/display.html.twig',[
                'titre'=>'Auteur',
                'aut' => $unaut]);
        }

    }
    /**
     * @Route("/edit/{id}",name="edit_auteur")
     */
        public function edit(int $id =- 1,Request $request)
        {
            if ($id <= 0) {
                return $this->redirectToRoute('index_auteur');
            } else {
                $repAuteurs = $this->getDoctrine()->getRepository(Auteur::class);
                $unaut = $repAuteurs->findOneBy(['id' => $id]);

                //creation du form
                $form = $this->createForm(AuteurType::class,$unaut);
                $form->add('Modifier',SubmitType::class,
                    ['attr'=>['class'=>'btn btn-warning'],'label'=>'Confirmer']);


                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid())
                {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($unaut);
                    $em->flush();
                    return $this->redirectToRoute('index_auteur');
                }


                return $this->render('auteur/edit.html.twig',
                [
                    'titre'=>'Auteur',
                    'form'=>$form->createView()]);

    }
        }
     /**
     * @Route ("/delete/{id}",name="delete_auteur")
     */
        public function delete(int $id =- 1)
        {
            if ($id <= 0) {
                return $this->redirectToRoute('index_auteur');
            } else
            {
                $repAuteurs = $this->getDoctrine()->getRepository(Auteur::class);
                $unaut = $repAuteurs->findOneBy(['id' => $id]);
                $em = $this->getDoctrine()->getManager();
                $em->remove($unaut);
                $em->flush();
                return $this->redirectToRoute('index_auteur');



                return $this->render('auteur/delete.html.twig');

            }
        }

    }
