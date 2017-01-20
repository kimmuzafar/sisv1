<?php

namespace Wit\SADBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GradingSystemType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('marksMin', 'integer', array('label'=>'Minimum Marks',    'attr'=>array('class'=>'gui-input', 'placeholder'=>'e-g 95')))
            ->add('marksMax', 'integer', array('label'=>'Maximum Marks',    'attr'=>array('class'=>'gui-input', 'placeholder'=>'e-g 100')))
            ->add('gradeLetter', 'text', array('label'=>'Grade Letter',    'attr'=>array('class'=>'gui-input', 'placeholder'=>'e-g A+')))
            ->add('earnedPoints', 'integer', array('label'=>'Earned Points',    'attr'=>array('class'=>'gui-input', 'placeholder'=>'e-g 5.00')))
            ->add('gradeDescription', 'text', array('label'=>'Grade Description',    'attr'=>array('class'=>'gui-input', 'placeholder'=>'e-g Excellent')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wit\ModelBundle\Entity\GradingSystem'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wit_modelbundle_grading_system';
    }
}
