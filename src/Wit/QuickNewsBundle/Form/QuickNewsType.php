<?php

namespace Wit\QuickNewsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class QuickNewsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array('label'=>'Title',    'attr'=>array('class'=>'gui-input', 'placeholder'=>'Title..')))
            ->add('description', 'textarea', array('label'=>'Description',    'attr'=>array('class'=>'gui-textarea', 'placeholder'=>'Description..')))
            ->add('toUserRole', 'entity', array('label'=>'To', 'class'=>'ModelBundle:Role', 'empty_value'=>'- To..', 'property'=>'role', 'multiple'=>false, 'attr'=>array('class'=>'gui-input', 'placeholder'=>'To..')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wit\ModelBundle\Entity\QuickNews'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wit_modelbundle_quicknews';
    }
}
