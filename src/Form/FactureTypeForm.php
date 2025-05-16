<?php

namespace App\Form;

use App\Entity\Facture;
use App\Entity\Client;
use App\Entity\InfosApplication;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class FactureTypeForm extends AbstractType
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
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => ['application/pdf'],
                        'mimeTypesMessage' => 'Veuillez fournir un PDF valide.',
                    ])
                ],
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date *'
            ])
            ->add('dateEcheance', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date d\'échéance *'
            ])
            ->add('statut', ChoiceType::class, [
                'label' => 'Statut *',
                'choices' => [
                    'En cours de saisie' => 'En cours de saisie',
                    'En attente de règlement' => 'En attente de règlement',
                    'Réglée' => 'Réglée',
                ],
            ])
            ->add('montantHT', NumberType::class, ['label' => 'Montant HT *'])
            ->add('montantTVA', NumberType::class, ['label' => 'Montant TVA *'])
            ->add('montantTTC', NumberType::class, ['label' => 'Montant TTC *'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
        ]);
    }
}
