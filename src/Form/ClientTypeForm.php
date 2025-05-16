<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ClientTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('raisonSociale', TextType::class, [
                'label' => 'Raison sociale *',
            ])
            ->add('adresse1', TextType::class, [
                'label' => 'Adresse 1 *',
            ])
            ->add('adresse2', TextType::class, [
                'label' => 'Adresse 2',
                'required' => false,
            ])
            ->add('adresse3', TextType::class, [
                'label' => 'Adresse 3',
                'required' => false,
            ])
            ->add('codePostal', TextType::class, [
                'label' => 'Code postal *',
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville *',
            ])
            ->add('pays', TextType::class, [
                'label' => 'Pays *',
            ])
            ->add('formeJuridique', TextType::class, [
                'label' => 'Forme juridique *',
            ])
            ->add('activite', TextType::class, [
                'label' => 'Activité *',
            ])
            ->add('siret', TextType::class, [
                'label' => 'SIRET *',
            ])
            ->add('actif', CheckboxType::class, [
                'label' => 'Actif',
                'required' => false,
            ])
            ->add('nomPrenomRepresentant', TextType::class, [
                'label' => 'Nom et Prénom représentant légal *',
            ])
            ->add('emailRepresentant', EmailType::class, [
                'label' => 'Email représentant légal *',
            ])
            ->add('telephoneRepresentant', TelType::class, [
                'label' => 'Téléphone représentant légal *',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
