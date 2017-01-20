<?php

namespace Wit\CourseManagementBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CourseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('coursequarter', 'entity', array('label'=>'Quarter', 'class'=>'ModelBundle:CourseQuarter', 'empty_value'=>'- Select Quarter..', 'property'=>'fullTitle', 'multiple'=>false, 'attr'=>array('class'=>'gui-input', 'placeholder'=>'Quarter..')))
            ->add('title', 'text', array('label'=>'Name',    'attr'=>array('class'=>'gui-input', 'placeholder'=>'Name..')))
            ->add('code', 'text', array('label'=>'Code',    'attr'=>array('class'=>'gui-input', 'placeholder'=>'Code..')))
            ->add('creditHours', 'text', array('label'=>'Credit Hours',    'attr'=>array('class'=>'gui-input', 'placeholder'=>'Credit Hours..')))
            ->add('description', 'textarea', array('label'=>'Description',    'attr'=>array('class'=>'gui-textarea', 'placeholder'=>'Description..')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wit\ModelBundle\Entity\Course'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wit_modelbundle_course';
    }
}
