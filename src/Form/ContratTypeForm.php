<?php

namespace App\Form;

use App\Entity\Contrat;
use App\Entity\Client;
use App\Entity\InfosApplication;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ContratTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'raisonSociale',
                'label' => 'Client *'
            ])
            ->add('application', EntityType::class, [
                'class' => InfosApplication::class,
                'choice_label' => 'nom',
                'label' => 'Application *'
            ])
            ->add('file', FileType::class, [
                'label' => 'Fichier PDF *',
                'mapped' => false, // ne pas le lier directement à l'entité (car le setter prend une string)
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => ['application/pdf'],
                        'mimeTypesMessage' => 'Veuillez uploader un fichier PDF valide.',
                    ])
                ],
            ])
            ->add('statut', ChoiceType::class, [
                'label' => 'Statut *',
                'choices' => [
                    'En cours de saisie' => 'En cours de saisie',
                    'En cours de signature' => 'En cours de signature',
                    'Actif' => 'Actif',
                    'Clôturé' => 'Clôturé',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contrat::class,
        ]);
    }
}
