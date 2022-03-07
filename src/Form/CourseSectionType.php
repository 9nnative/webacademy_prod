<?php

namespace App\Form;

use App\Entity\Course;
use App\Form\SectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CourseSectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sections', CollectionType::class, [
                'entry_type' => SectionEditType::class,
                'label' => false,
                'allow_add' => true,
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'ui primary button margintop marginbot'],
                'label' => 'Mettre à jour les sections',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}
