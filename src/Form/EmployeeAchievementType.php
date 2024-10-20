<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\EmployeeAchievement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class EmployeeAchievementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', null, [
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('achievement_type', CheckboxType::class, [
                'label' => 'Logro',
                'required' => false, // Esto permite que el checkbox no sea obligatorio
                'attr' => [
                    'class' => 'custom-checkbox-wrapper'
                ]
            ])
            ->add('achievement_date', null, [
                'widget' => 'single_text',
                'label' => 'Fecha',
                'data' => new \DateTime(),
                'attr' => [
                    'class' => 'form-control',
                    'max' => (new \DateTime())->format('Y-m-d\TH:i')
                ]
            ])
            ->add('employee', EntityType::class, [
                'class' => Employee::class,
                'choice_label' => 'name',
                'data' => $options['employee'],
                'attr' => [
                    'disabled' => true,
                    'class' => 'form-select'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EmployeeAchievement::class,
            'employee' => null,
            'attr' => [
                'class' => 'achievement-form', 
            ],
        ]);
    }
}
