<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\EmployeeAchievement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeAchievementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('achievement_type', null, [
                'label' => 'Logro'
            ])
            ->add('achievement_date', null, [
                'widget' => 'single_text',
            ])
            ->add('employee', EntityType::class, [
                'class' => Employee::class,
                'choice_label' => 'fullName',
                'data' => $options['employee'],
                'attr' => [
                    'disabled' => true
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EmployeeAchievement::class,
            'employee' => null
        ]);
    }
}
