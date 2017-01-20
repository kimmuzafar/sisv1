<?php

namespace Wit\TODBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GradingcriteriaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('groupWork', 'integer', array('label'=>'Group Work',    'attr'=>array('class'=>'gui-input', 'placeholder'=>'Group Work..')))
            ->add('assignment', 'integer', array('label'=>'Assignment',    'attr'=>array('class'=>'gui-input', 'placeholder'=>'Assignment..')))
            ->add('quiz', 'integer', array('label'=>'Quizzes',    'attr'=>array('class'=>'gui-input', 'placeholder'=>'Quizzes..')))
            ->add('attendance', 'integer', array('label'=>'Attendance',    'attr'=>array('class'=>'gui-input', 'placeholder'=>'Attendance..')))
            ->add('finalExam', 'integer', array('label'=>'Final Exam',    'attr'=>array('class'=>'gui-input', 'placeholder'=>'Final Exam..')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wit\ModelBundle\Entity\GradingCriteria'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wit_modelbundle_gradingcriteria';
    }
}
