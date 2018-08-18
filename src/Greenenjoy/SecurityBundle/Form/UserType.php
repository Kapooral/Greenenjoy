<?php 

namespace Greenenjoy\SecurityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', TextType::class, array(
                    'required' => false,
                    'label' => 'Nom d\'affichage'))
                ->add('instagram', TextType::class, array(
                    'required' => false,
                    'label' => 'Compte Instagram'))
                ->add('email', RepeatedType::class, array(
                    'type' => EmailType::class,
                    'required' => false,
                    'invalid_message' => 'L\'adresse e-mail doit être identique.',
                    'first_options' => array(
                        'label' => 'Nouvelle adresse e-mail'),
                    'second_options' => array(
                        'label' => 'Confirmez la nouvelle adressse e-mail',
                        'attr' => array('autocomplete' => 'off'))))
                ->add('password', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'required' => false,
                    'mapped' => false,
                    'invalid_message' => 'Le mot de passe doit être identique.',
                    'first_options' => array(
                        'label' => 'Nouveau mot de passe'),
                    'second_options' => array(
                        'label' => 'Confirmez le nouveau mot de passe')))
                ->add('current_password', PasswordType::class, array(
                    'label' => 'Mot de passe actuel',
                    'mapped' => false));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Greenenjoy\SecurityBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'greenenjoy_securitybundle_user';
    }
}
