<?php

namespace PrincipalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class AclType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('disabled',ChoiceType::class, array("label"=>"Disabled: ","required"=>"required","attr"=>array("class"=>"form-control"),
            'choices' => array(
                'On' => 'on',
                'Off' => 'off',)))
            ->add('name',TextType::class,array("label"=>"Name: ","required"=>"required","attr"=>array("class"=>"form-control")))
            ->add('client',TextareaType::class,array("required"=>"required","attr"=>array("class"=>"form-control",'rows' => '7')))
            ->add('time',ChoiceType::class, array("label"=>"Time: ","required"=>"required","attr"=>array("class"=>"form-control"),
                'choices' => array(
                    'none (time not defined)' => 'none',)))
            ->add('targetRule',ChoiceType::class, array("label"=>"Target rules: ","required"=>"required","attr"=>array("class"=>"form-control"),
                'choices' => array(
                    'All [ All]' => 'All [ All]',)))
            ->add('allowIp',ChoiceType::class, array("label"=>"Do not allow IP-Addresses in URL: ","required"=>"required","attr"=>array("class"=>"form-control"),
                'choices' => array(
                    'On' => 'on',
                    'Off' => 'off',)))
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
            ->add('redirect',TextType::class,array("label"=>"Redirect: ","required"=>"required","attr"=>array("class"=>"form-control")))
            ->add('safeSearch',ChoiceType::class, array("label"=>"Use SafeSearch engine: ","required"=>"required","attr"=>array("class"=>"form-control"),
                'choices' => array(
                    'On' => 'on',
                    'Off' => 'off',)))
            ->add('rewrite',ChoiceType::class, array("label"=>"Rewrite: ","required"=>"required","attr"=>array("class"=>"form-control"),
                'choices' => array(
                    'none (rewrite not defined)' => 'none',
                    'Enter the rewrite condition name for this rule or leave it blank.' => 'safesearch',)))
            ->add('rewriteTime',ChoiceType::class, array("label"=>"Rewrite for off-time: ","required"=>"required","attr"=>array("class"=>"form-control"),
                'choices' => array(
                    'none (rewrite not defined)' => 'none',
                    'Enter the rewrite condition name for this rule or leave it blank.' => 'safesearch',)))
            ->add('description',TextType::class,array("label"=>"Description: ","required"=>"required","attr"=>array("class"=>"form-control")))
            ->add('log', EntityType::class, array("attr"=>array("class"=>"form-control"),
                'class' => 'PrincipalBundle:Log',
                'choice_label' => 'description',
            ))
            ->add('Save',SubmitType::class,array("attr"=>array("class"=>"btn btn-primary btn-block")))
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PrincipalBundle\Entity\Acl'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'principalbundle_acl';
    }


}
