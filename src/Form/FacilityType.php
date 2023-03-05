<?php

namespace App\Form;

use App\Entity\Facility;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FacilityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('location')
            ->add('phone')
            ->add('fax')
            ->add('rank')
            ->add('status')
            ->add('description')
            ->add('startTime')
            ->add('endTime')
            ->add('plannings')
            ->add('appointments')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facility::class,
        ]);
    }
}
