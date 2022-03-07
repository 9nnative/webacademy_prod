<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\UserGroup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseInGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('courses', EntityType::class, [
                // looks for choices from this entity
                'class' => Course::class,
            
                // uses the User.username property as the visible option string
                'choice_label' => 'title',
                'multiple' => true,
                'attr' => ['class' => 'ui search dropdown'],
                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserGroup::class,
        ]);
    }
}
