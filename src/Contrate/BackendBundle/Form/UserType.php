<?php

namespace Contrate\BackendBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class UserType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $choices = array(
            'Empresário' => 'Empresário', 
            'Empresa' => 'Empresa', 
            'cerimonialista' => 'Cerimonialista',
            'Produtor' => 'Produtor',
            'Produtor' => 'Prefeitura',
            'Agência' => 'Agência',
            'Outros' => 'Outros'
            );
       // add your custom field
        $builder->add('name');
        $builder->add('type', 'choice', 
            array('choices' => $choices));
        //$builder->add('logo');
        $builder->add('site');
        $builder->add('phone');
        $builder->add('agency');
        //$builder->add('logo', 'file');
        //$builder->add('language');

        //...............
        //Add all your properties here with $builder->add('property name')
    }

    public function getName()
    {
        return 'contrate_user_registration';
    }
}