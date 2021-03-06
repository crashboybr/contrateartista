<?php

namespace Contrate\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
                                
        $events =   array(
            "Corporativo"       => "Corporativo",
            "Show"              => "Show",
            "Presença VIP"      => "Presença VIP",
            "Desfile"           => "Desfile",
            "Festa Debutante"   => "Festa Debutante",
            "Casamento"         => "Casamento",
            "Formatura"         => "Formatura",
            "Mestre Cerimônia"  => "Mestre Cerimônia",
            "Palestras"         => "Palestras",
            "Campanha"          => "Campanha",
            "Outros"            => "Outros");
        $builder
            ->add('name')
            ->add('email')
            ->add('phone')
            ->add('event', 'choice', array(
                    'multiple' => false,
                    'expanded' => false,
                    'choices' => $events
                ))
            ->add('eventAt', 'text')
            ->add('city')
            ->add('state')
            ->add('description')
            ->add('artistId', 'hidden')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Contrate\BackendBundle\Entity\Contact'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'contrate_backendbundle_contact';
    }
}
