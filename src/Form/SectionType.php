<?php

namespace App\Form;

use App\Entity\Links;
use App\Entity\Section;
use App\Entity\CourseFiles;
use App\Form\CourseFileType;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class SectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'attr' => ['class' => 'ui input',
                'autocomplete' => 'off'],
            ])
            ->add('content', TextareaType::class, [
                'attr' => ['class' => 'ui input tinymce',
                'autocomplete' => 'off'],
                'required' => false,
            ])
            // ->add('links', CollectionType::class, [
            //     // looks for choices from this entity
            //     'entry_type' => LinksType::class,
            //     'entry_options' => ['label' => false],
            //     'allow_add' => true,
            //     'required' => false,
            //     'label' => false
            // ])
            ->add('courseFiles', FileType::class, [
                'attr' => ['id' => 'uploadImage',
                'onchange' => 'PreviewImage();'],
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'ui primary button margintop submitButton'],
                'label' => 'Ajouter la section',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Section::class,
            'allow_extra_fields' => true
        ]);
    }
}
