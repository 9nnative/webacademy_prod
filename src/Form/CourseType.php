<?php

namespace App\Form;

use DateTime;
use App\Entity\Course;
use App\Entity\Section;
use App\Entity\Category;
use App\Form\ChapterType;
use App\Form\CourseFileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'block_prefix' => 'wrapped_text',
            ])
            ->add('description', TextareaType::class, [
                'attr' => ['class' => ''],
                'required' => false
            ])
            ->add('prerequis', TextareaType::class)
            ->add('objectifs_pedago', TextareaType::class)
            ->add('title', TextType::class, [
                'block_prefix' => 'wrapped_text',
            ])
            ->add('categories', EntityType::class, [
                // looks for choices from this entity
                'class' => Category::class,
            
                // uses the User.username property as the visible option string
                'choice_label' => 'name',
            
                // used to render a select box, check boxes or radios
                'multiple' => true,
                'attr' => ['class' => 'ui search dropdown'],

            ])
            ->add('targets', ChoiceType::class, [
                'choices'  => [
                    'Industriel' => 'Industriel',
                    'Collectivité' => 'Collectivité',
                    'Étudiant' => 'Étudiant',
                ],
                'multiple' => true,
                'attr' => ['class' => 'ui search dropdown'],
            ])
            ->add('brochure', FileType::class, [
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                'attr' => array('accept' => 'image/jpeg,image/png,image/gif,image/jpg'),
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'label' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Seules les images sont acceptées',
                    ])
                ],
            ])
            ->add('is_paying')
            ->add('duration')
            ->add('date_start_str')
            ->add('date_end_str')
            ->add('date_end_bool', CheckboxType::class,[
                'label' => 'Date de fin du cours',
                'required' => false,
                'attr' => ['class' => 'ui checkbox'],
                 
            ])
            ->add('sections', CollectionType::class, [
                'entry_type' => SectionType::class,
                'label' => false,
                'allow_add' => true,
            ])
            ->add('suivant', SubmitType::class, [
                'attr' => ['class' => 'ui right floated button dnone'],
                'label' => 'Suivant',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
            'allow_extra_fields' => true
        ]);
    }

    public function getBlockPrefix()
    {   
    return ''; // return an empty string here
    }

}
