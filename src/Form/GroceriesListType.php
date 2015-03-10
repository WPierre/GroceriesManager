<?php

namespace WPierre\GroceriesManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GroceriesListType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('items')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WPierre\GroceriesManagerBundle\Entity\GroceriesList'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wpierre_groceriesmanagerbundle_grocerieslist';
    }
}
