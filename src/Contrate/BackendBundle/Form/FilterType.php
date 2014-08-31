<?php

namespace Contrate\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityRepository;

class FilterType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
   

        $builder
            ->add('q', 'text')
            ->add('category' , 'entity' , array(
                      'class'    => 'ContrateBackendBundle:Category',
                      'property' => 'name' ,
                      'expanded' => true ,
                      'multiple' => true , ))
            
            ->add('submit', 'submit', array('label' => 'Buscar'))
            
        ;




    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'contrate_filter';
    }
}
