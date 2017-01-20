<?php

namespace Wit\SADBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdmissionSchedulerType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array('label'=>'Admission Title',    'attr'=>array('class'=>'gui-input', 'placeholder'=>'Admission Title.. e-g Fall Admission')))
            ->add('detail', 'textarea', array('label'=>'Admission Procedure',    'attr'=>array('class'=>'gui-textarea', 'placeholder'=>'Admission Procedure..')))
            /*
            ->add('startDate', 'datetime', array('label'=>'Starting Date', 'data' => new \DateTime('now'),    'attr'=>array('class'=>'gui-input')))
            ->add('endDate', 'datetime', array('label'=>'Ending Date', 'data' => new \DateTime('now'),   'attr'=>array('class'=>'gui-input')))
            */
            ->add('startDate', 'datetime', array('label'=>'Starting Date',    'attr'=>array('class'=>'gui-input')))
            ->add('endDate', 'datetime', array('label'=>'Ending Date',   'attr'=>array('class'=>'gui-input')))
            ->add('applicationsRefNo', 'text', array('label'=>'Application Reference No.',    'attr'=>array('class'=>'gui-input', 'placeholder'=>'Application Reference #.. e-g YEAR-FALL-00001')))
            ->add('isOpen', 'choice', array('label'=>'Scheduler Status', 'choices'  => array('1' => 'Open', '0' => 'Closed'), 'attr'=>array('class'=>'gui-input')))

            ->add('docAdPhotoFile', 'file', array('label'=>'Ad Photo', 'required'=>false, 'attr'=>array('class'=>'gui-file', 'onChange'=>"document.getElementById('uploadfile4').value = this.value;")))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wit\ModelBundle\Entity\AdmissionScheduler'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wit_modelbundle_admissionscheduler';
    }
}
