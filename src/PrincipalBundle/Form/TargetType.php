<?php

namespace PrincipalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TargetType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        

        $builder
            ->add('name',TextType::class,array("label"=>"Name: ","required"=>"required","attr"=>array("class"=>"form-control")))
            ->add('domainList',TextareaType::class,array("label"=>"Domain list: ","required"=>"required","attr"=>array("class"=>"form-control", 'rows' => '7')))
            ->add('urlList',TextareaType::class,array("label"=>"URL list: ","required"=>"required","attr"=>array("class"=>"form-control", 'rows' => '7')))
            ->add('regularExpression',TextareaType::class,array("label"=>"Regular expression: ","required"=>"required","attr"=>array("class"=>"form-control", 'rows' => '7')))
            ->add('redirect',TextType::class,array("label"=>"Redirect: ","required"=>"required","attr"=>array("class"=>"form-control")))
            ->add('description',TextType::class,array("label"=>"Description: ","required"=>"required","attr"=>array("class"=>"form-control")))

            ->add('redirectMode',ChoiceType::class, array("label"=>"Redirect mode: ","required"=>"required","attr"=>array("class"=>"form-control"),
                'choices' => array(
                'none' => 'rmod_none',
                'int error page (enter error message)' => 'rmod_int',
                'int blank page ' => 'rmod_int_bpg',
                'int blank image' => 'rmod_int_bim',
                'ext url err page (enter URL)' => 'rmod_ext_err',
                'ext url redirect (enter URL)' => 'rmod_ext_rdr',
                'ext url move  (enter URL)' => 'rmod_ext_mov',
                'ext url found (enter URL)' => 'rmod_ext_fnd',)))
            
            ->add('log', EntityType::class, array("attr"=>array("class"=>"form-control"),
                'class' => 'PrincipalBundle:Log',
                'choice_label' => 'description',
            ))
            ->add('Save',SubmitType::class,array("attr"=>array("class"=>"btn btn-primary btn-block")));
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PrincipalBundle\Entity\Target'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'principalbundle_target';
    }
}
