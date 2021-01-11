<?php

namespace App\Controller;

use App\Repository\AuteurRepository;
use App\Repository\EditeurRepository;
use App\Repository\LivreRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use function mysql_xdevapi\getSession;

class PanierController extends AbstractController
{
    /**
     * @Route("/bibliotheque/panier", name="panier_index")
     */
    public function index(SessionInterface $session , LivreRepository $livreRepository): Response
    {
        $panier = $session->get('panier',[]);
        $panierfull = [];
        foreach ($panier as $id => $qte)
        {
            $panierfull[]=[
                'livre'=>$livreRepository->find($id),
                'qte'=>$qte
            ];
        }
        //dd($panierfull);
        $totale =0;
        foreach ($panierfull as $item){
            $totaleitem = $item['livre']->getPrix() * $item ['qte'];
            $totale += $totaleitem;
        }

        return $this->render('bibliotheque/panier.html.twig', [
            'items' => $panierfull,
            'totale' =>$totale
        ]);
    }
    /**
     * @Route("/bibliotheque/panier/add/{id}", name="panier_add")
     */
    public function add($id,SessionInterface $session,LivreRepository $livreRepository,EditeurRepository $editeurRepository
        , AuteurRepository $auteurRepository,CategorieRepository $categorieReposiory)
    {
        $panier = $session->get('panier',[]);
        if (!empty($panier[$id])) {
            $panier[$id]++;
        }else {
            $panier[$id]=1;
        }
        $session->set('panier',$panier);
          // dd($session->get('panier'));
        return $this->redirectToRoute('livresection');
    }

    /**
     * @Route("/bibliotheque/panier/remove/{id}", name="panier_remove")
     */
    public function remove($id,SessionInterface $session)
    {
        $panier = $session->get('panier',[]);
        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }
        $session->set('panier',$panier);
        return $this->redirectToRoute('panier_index');
    }
}
