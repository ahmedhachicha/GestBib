<?php

namespace App\Form;

use App\Entity\Auteur;
use App\Entity\Livre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;


class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre',TextType::class,['attr'=>['class'=>'form-control'],'label'=>'Titre'])
            //*****************************************//
            ->add('nbPage',TextType::class,['attr'=>['class'=>'form-control'],'label'=>'Nombre des pages'])
            ->add('dateEdition',DateType::class,['widget'=>'single_text'])
            ->add('nbExemplaire',TextType::class,['attr'=>['class'=>'form-control'],'label'=>'Nb Exemplaire'])
            ->add('prix',TextType::class,['attr'=>['class'=>'form-control'],'label'=>'Prix'])
            ->add('isbn',TextType::class,['attr'=>['class'=>'form-control'],'label'=>'ISBN'])
            ->add('editeur')
            ->add('categorie')
            ->add('auteurs',EntityType::class,
            [
                'class'=>Auteur::class,
                'multiple'=>true,
                'expanded'=>false,
                'choice_label'=>function($auteur)
                {
                    return $auteur->getPrenom()."".$auteur->getNom();
                }

            ]
            )
            ->add('ImagePath', FileType::class, [
                'label' => 'ImagePath',
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '10000k',

                    ])
                ],

            // ...

            ])/*
           ->add('Image',TextType::class,['attr'=>['class'=>'form-control'],'label'=>'Image Path'])
*/
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);
    }
}
