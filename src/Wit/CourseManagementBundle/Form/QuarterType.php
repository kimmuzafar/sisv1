<?php

namespace Wit\CourseManagementBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class QuarterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('coursebatch', 'entity', array('label'=>'Batch', 'class'=>'ModelBundle:CourseBatch', 'empty_value'=>'- Select Batch..', 'property'=>'title', 'multiple'=>false, 'attr'=>array('class'=>'gui-input', 'placeholder'=>'Batch..')))
            ->add('title', 'text', array('label'=>'Name',    'attr'=>array('class'=>'gui-input', 'placeholder'=>'Name..')))
            ->add('quarterNumber', 'text', array('label'=>'Quarter Number',    'attr'=>array('class'=>'gui-input', 'placeholder'=>'Quarter number e-g 1..')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wit\ModelBundle\Entity\CourseQuarter'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wit_modelbundle_coursequarter';
    }
}
