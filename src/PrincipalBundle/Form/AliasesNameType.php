<?php

namespace PrincipalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;;

class AliasesNameType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,array("label"=>"Name: ","required"=>"required","attr"=>array("class"=>"form-control")))
            ->add('description',TextType::class,array("label"=>"Description: ","required"=>"required","attr"=>array("class"=>"form-control")))
            ->add('status',ChoiceType::class, array("label"=>"Type ","required"=>"required","attr"=>array("class"=>"form-control"),
            'choices' => array(
                'Choose an option' => '1',
                'host' => 'host',
                'Network(s)' => 'network',
                'Port(s)' => 'port',
                'URL (Ips)' => 'url',
                'URL (Ports)' => 'url_ports',
                'URL Table (Ips)' => 'urltable',
                'URL Table (Ports)' => 'urltable_ports',)))
            ->add('exp', CollectionType::class, 
            [
                'label'=>'Hint',
                'entry_type' => AliasesDescriptionType::class,
                'entry_options' => 
                [
                    'label' => false
                ],
                'by_reference' => false,
                // this allows the creation of new forms and the prototype too
                'allow_add' => true,
                // self explanatory, this one allows the form to be removed
                'allow_delete' => true
            ])
            ->add('Save', SubmitType::class,
                [
                    'attr' =>
                    [
                        'class' => 'btn btn-success'
                    ]
                ])
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PrincipalBundle\Entity\AliasesName'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'principalbundle_aliasesname';
    }


}
