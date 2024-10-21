<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Positions;
use App\Entity\Store;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $maxDate = (new \DateTime())->modify('-18 years');
        $builder
            ->add('birth_date', null, [
                'widget' => 'single_text',
                'label' => 'Fecha de nacimiento',
                'data' => $maxDate,
                'attr' => [
                    'class' => 'form-control',
                    'max' => $maxDate->format("Y-m-d")
                ]
            ])
            ->add('salary', null, [
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('full_name', null, [
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('position', EntityType::class, [
                'class' => Positions::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-select',
                ]
            ])
            ->add('store', EntityType::class, [
                'class' => Store::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-select',
                ]
            ])
            ->add('image', FileType::class, [
                'label' => 'Fotografía',
                'mapped' => false,
                'required' => $options['is_create'],
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Por favor, sube una imagen válida.',
                    ]),
                    new NotBlank([
                        'message' => 'Este campo es obligatorio.',
                        'groups' => $options['is_create'] ? ['Default'] : []
                    ])
                ],
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
            'is_create' => false,
        ]);
    }
}
