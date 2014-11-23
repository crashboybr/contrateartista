<?php

namespace Contrate\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArtistType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('category', 'entity', array(
                'class'       => 'ContrateBackendBundle:Category',
                'expanded'    => false,
                'multiple'    => false,
                'property' => 'name',
                'empty_value' => 'Escolha uma Categoria',
                ))
            ->add('artist_images', 'collection', array('type' => new ArtistImageType(), 'required' => false))
            ->add('agency')
            ->add('website')
            ->add('phone')
            ->add('description')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Contrate\BackendBundle\Entity\Artist'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'contrate_backendbundle_artist';
    }
}
