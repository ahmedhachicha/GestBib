<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/user")
 */

class AdminController extends AbstractController
{
    /**
     * @Route("/index",name="user_index")
     */
    public function usersList(UserRepository $user) {
        return $this->render("user/users.html.twig",[
            'titre'=>'User',
            'users' => $user->findAll()
        ]);
    }
    /**
     * @Route("modifier/{id}", name="edit_user")
     */
    public function editUser(Request $request, User $user, EntityManagerInterface  $em) {

        $form = $this->createForm(EditUserType::class,$user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/editUser.html.twig',
            [
                'titre'=>'User',
                'formUser' => $form->createView()]);

    }
}