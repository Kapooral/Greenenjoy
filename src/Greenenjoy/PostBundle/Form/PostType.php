<?php

namespace Greenenjoy\PostBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Greenenjoy\PostBundle\Form\ImageType;

class PostType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, array(
                    'label' => 'Titre'))
                ->add('subtitle', TextType::class, array(
                    'label' => 'Citation',
                    'required' => false))
                ->add('content', TextareaType::class, array(
                    'label' => 'Contenu'))
                ->add('image', ImageType::class)
                ->add('categorie', EntityType::class, array(
                    'class' => 'GreenenjoyPostBundle:Categories',
                    'choice_label' => 'name'));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Greenenjoy\PostBundle\Entity\Post'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'post';
    }


}
