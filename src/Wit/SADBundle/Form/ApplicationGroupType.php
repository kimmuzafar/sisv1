<?php

namespace Wit\SADBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ApplicationGroupType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('admissionScheduler', 'entity', array('label'=>'Admission Scheduler', 'class'=>'ModelBundle:AdmissionScheduler', 'empty_value'=>'- Select Admission Scheduler..', 'property'=>'title', 'multiple'=>false, 'attr'=>array()))
            ->add('title', 'text', array('label'=>'Name', 'attr'=>array('class'=>'gui-input', 'placeholder'=>'Name..')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wit\ModelBundle\Entity\ApplicationGroup'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wit_modelbundle_applicationgroup';
    }
}
