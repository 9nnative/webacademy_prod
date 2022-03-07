<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserRightsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('roles', ChoiceType::class, [
            'choices' => [
                'Administrateur' => 'ROLE_ADMIN',
                'Super Administrateur' => 'ROLE_SUPER_ADMIN',
                'Abonné' => 'ROLE_SUBBED',
                'Novice' => 'ROLE_NOVICE',
                'Intermédiaire' => 'ROLE_INTERMEDIATE',
                'Expert' => 'ROLE_EXPERT',
                'Coach' => 'ROLE_COACH',
            ],
            'multiple' => true,
            'label' => 'Rôles',
            'attr' => ['class' => 'ui search dropdown'], 
        ])
        ;
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
