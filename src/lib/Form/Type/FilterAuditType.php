<?php

namespace Edgar\EzUIAudit\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterAuditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'audit_types',
                AuditTypeChoiceType::class,
                [
                    'label' => 'Signals',
                    'multiple' => true,
                    'expanded' => false,
                    'required' => false,
                ]
            )
            ->add(
                'date_start',
                TextType::class,
                [
                    'required' => true,
                    'attr'     => [
                        'class'      => 'flatpickr flatpickr-input ez-data-source__input form-control date-start',
                    ],
                ]
            )
            ->add(
                'date_end',
                TextType::class,
                [
                    'required' => true,
                    'attr'     => [
                        'class'      => 'flatpickr flatpickr-input ez-data-source__input form-control date-end',
                    ],
                ]
            )
            ->add('page', HiddenType::class)
            ->add('limit', HiddenType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
            ]);
    }
}
