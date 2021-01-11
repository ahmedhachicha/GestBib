<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\LivreType;
use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Image;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


/**
 * @Route("admin/livre")
 */
class LivreController extends AbstractController

{
    /**
     * @Route("/", name="livre_index", methods={"GET"})
     */
    public function index(LivreRepository $livreRepository,SluggerInterface $slugger): Response
    {
        return $this->render('livre/index.html.twig', [
            'titre'=>'Livre',
            'livres' => $livreRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="livre_new", methods={"GET","POST"})
     */
    public function new(Request $request,SluggerInterface $slugger): Response
    {
        $livre = new Livre();
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $ImagePath = $form->get('ImagePath')->getData();
            $originalFilename = pathinfo($ImagePath->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename . '-' . uniqid() . '.' . $ImagePath->guessExtension();

            // Move the file to the directory where brochures are stored
            try {
                $ImagePath->move(
                    $this->getParameter('books_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $newnewFilename = "/img/books/" . $newFilename;

//dd($newnewFilename);
            $livre->setImage($newnewFilename);
            //*******************************PDF*******************************************//
            //*******************************PDF*******************************************//

            $bookpdf = $form->get('bookpdf')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($bookpdf) {
                $originalPdfname = pathinfo($bookpdf->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safePdfname = $slugger->slug($originalPdfname);
                $newPdfname = $safePdfname . '-' . uniqid() . '.' . $bookpdf->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $bookpdf->move(
                        $this->getParameter('pdf_directory'),
                        $newPdfname
                    );
                } catch (FileException $e) {
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $livre->setBookpdf($newPdfname);
                $entityManager->persist($livre);
                $entityManager->flush();
                //  dd($livre->setImage($newnewFilename));

                $entityManager->persist($livre);
                $entityManager->flush();
            }

        return $this->redirectToRoute('livre_index');

    }
            return $this->render('livre/new.html.twig', [
                'titre' => 'Livre',
                'livre' => $livre,
                'form' => $form->createView(),
            ]);
        }


        /**
         * @Route("/{id}", name="livre_show", methods={"GET"})
         */
        public
        function show(Livre $livre): Response
        {
            return $this->render('livre/show.html.twig', [
                'titre' => 'Livre',
                'livre' => $livre,
            ]);
        }

        /**
         * @Route("/{id}/edit", name="livre_edit", methods={"GET","POST"})
         */
        public
        function edit(Request $request, Livre $livre, SluggerInterface $slugger): Response
        {
            $form = $this->createForm(LivreType::class, $livre);
            $form->handleRequest($request);
            //****************IMAGE************************/
            //****************IMAGE************************/

            $ImagePath = $form->get('ImagePath')->getData();


            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $entityManager = $this->getDoctrine()->getManager();
                $ImagePath = $form->get('ImagePath')->getData();
                $originalFilename = pathinfo($ImagePath->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $ImagePath->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $ImagePath->move(
                        $this->getParameter('books_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $newnewFilename = "/img/books/" . $newFilename;

                //dd($newnewFilename);
                $livre->setImage($newnewFilename);
                //*******************************PDF*******************************************//
                //*******************************PDF*******************************************//

                $bookpdf = $form->get('bookpdf')->getData();

                // this condition is needed because the 'brochure' field is not required
                // so the PDF file must be processed only when a file is uploaded
                if ($bookpdf) {
                    $originalPdfname = pathinfo($bookpdf->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safePdfname = $slugger->slug($originalPdfname);
                    $newPdfname = $safePdfname . '-' . uniqid() . '.' . $bookpdf->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $bookpdf->move(
                            $this->getParameter('pdf_directory'),
                            $newPdfname
                        );
                    } catch (FileException $e) {
                    }
                }
                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $livre->setBookpdf($newPdfname);
                $entityManager->persist($livre);
                $entityManager->flush();

                return $this->redirectToRoute('livre_index');

            }
            return $this->render('livre/edit.html.twig', [
                'titre' => 'Livre',
                'livre' => $livre,
                'form' => $form->createView(),
            ]);
        }


    /**
     * @Route("/{id}", name="livre_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Livre $livre): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livre->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($livre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('livre_index');
    }


}
