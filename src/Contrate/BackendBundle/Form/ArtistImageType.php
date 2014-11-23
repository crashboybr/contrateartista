<?php

namespace Contrate\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArtistImageType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('pic', 'file', array(
                'attr' => array('class' => 'img-pics'),
                'label' => false,
                'data_class' => null
                ));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Contrate\BackendBundle\Entity\ArtistImage'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'contrate_frontendbundle_artist_image';
    }
}
