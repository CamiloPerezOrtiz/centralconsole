<?php

namespace PrincipalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class AdministratorType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,array("label"=>"Name: ","required"=>"required","attr"=>array("class"=>"form-control")))
            ->add('email', EmailType::class,array("label"=>"Email: ","required"=>"required","attr"=>array("class"=>"form-control")))
            ->add('password', PasswordType::class,array("label"=>"Password: ","required"=>"required","attr"=>array("class"=>"form-control")))
            ->add('Save', SubmitType::class,array("attr"=>array("class"=>"btn btn-primary btn-block")))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PrincipalBundle\Entity\Administrator'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'principalbundle_administrator';
    }


}
