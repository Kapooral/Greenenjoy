<?php 

namespace Greenenjoy\SecurityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Greenenjoy\PostBundle\Form\ImageType;

class InfosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('profilePicture', ImageType::class, array(
                    'label' => 'Photo de profil',
                    'required' => false))
                ->add('email', RepeatedType::class, array(
                    'type' => EmailType::class,
                    'required' => false,
                    'invalid_message' => 'L\'adresse e-mail doit être identique.',
                    'first_options' => array(
                        'label' => 'Nouvelle adresse e-mail'),
                    'second_options' => array(
                        'label' => 'Confirmez la nouvelle adressse e-mail',
                        'attr' => array('autocomplete' => 'off'))))
                ->add('username', TextType::class, array(
                    'required' => false,
                    'label' => 'Nom d\'affichage'))
                ->add('instagram', TextType::class, array(
                    'required' => false,
                    'label' => 'Compte Instagram'))
                ->add('coverBiography', ImageType::class, array(
                    'label' => 'Image de biographie',
                    'required' => false))
                ->add('biography', TextareaType::class, array(
                    'required' => false,
                    'label' => 'Biographie'))
                ->add('password', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'required' => false,
                    'invalid_message' => 'Le mot de passe doit être identique.',
                    'first_options' => array(
                        'label' => 'Nouveau mot de passe'),
                    'second_options' => array(
                        'label' => 'Confirmez le nouveau mot de passe')))
                ->add('current_password', PasswordType::class, array(
                    'label' => 'Mot de passe actuel',
                    'attr' => array('autocomplete' => 'off')));
    }

    public function getBlockPrefix()
    {
        return 'greenenjoy_securitybundle_user';
    }
}
