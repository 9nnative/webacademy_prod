<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Notification;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class NotificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class)
            ->add('header')
            ->add('type', ChoiceType::class, [
                'choices'  => [
                    'Information' => 'info',
                ],
            ])
            ->add('users', EntityType::class, [
                // looks for choices from this entity
                'class' => User::class,
            
                // uses the User.username property as the visible option string
                'choice_label' => function (User $user) {
                    return $user->getName() . ' ' . $user->getForename();
                },
            
                // used to render a select box, check boxes or radios
                'multiple' => true,
                'attr' => ['class' => 'ui search dropdown'],

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Notification::class,
        ]);
    }
}
