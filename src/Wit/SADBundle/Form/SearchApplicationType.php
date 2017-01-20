<?php

namespace Wit\SADBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SearchApplicationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('maritalStatus', 'entity',            array('label'=>'Marital Status', 'class'=>'ModelBundle:MaritalStatus', 'empty_value'=>'- Select Marital Status..', 'property'=>'title', 'multiple'=>false, 'attr'=>array()))
            ->add('gender', 'entity',                   array('label'=>'Gender', 'class'=>'ModelBundle:Gender', 'empty_value'=>'- Select Gender..', 'property'=>'title', 'multiple'=>false, 'attr'=>array()))
            
            ->add('city', 'entity',                     array('label'=>'City', 'class'=>'ModelBundle:City', 'empty_value'=>'- Select City..', 'property'=>'title', 'multiple'=>false, 'attr'=>array()))
            ->add('country', 'entity',                  array('label'=>'Country', 'class'=>'ModelBundle:Country', 'empty_value'=>'- Select Country..', 'property'=>'shortname', 'multiple'=>false, 'attr'=>array()))
            
            ->add('permanentCity', 'entity',            array('label'=>'City', 'class'=>'ModelBundle:City', 'empty_value'=>'- Select City..', 'property'=>'title', 'multiple'=>false, 'attr'=>array()))
            ->add('permanentCountry', 'entity',         array('label'=>'Country', 'class'=>'ModelBundle:Country', 'empty_value'=>'- Select Country..', 'property'=>'shortname', 'multiple'=>false, 'attr'=>array()))
            
            ->add('studyLevel', 'entity',               array('label'=>'Study Level', 'class'=>'ModelBundle:StudyLevel', 'empty_value'=>'- Select Study Level..', 'property'=>'title', 'multiple'=>false, 'attr'=>array()))
            ->add('completionDate', 'entity',           array('label'=>'Completion Date', 'class'=>'ModelBundle:CompletionDate', 'empty_value'=>'- Select Completion Date..', 'property'=>'title', 'multiple'=>false, 'attr'=>array()))
            ->add('completionCity', 'entity',           array('label'=>'Completion City', 'class'=>'ModelBundle:City','empty_value'=>'- Select Completion City..', 'property'=>'title', 'multiple'=>false, 'attr'=>array()))
            ->add('grade', 'entity',                    array('label'=>'Grade', 'class'=>'ModelBundle:Grade', 'empty_value'=>'- Select Grade..', 'property'=>'title', 'multiple'=>false, 'attr'=>array()))
            ->add('accumulatedGrade', 'entity',         array('label'=>'Accummulated Grade', 'class'=>'ModelBundle:AccumulatedGrade', 'empty_value'=>'- Select Accumulated Grade..', 'property'=>'title', 'multiple'=>false, 'attr'=>array()))
            ->add('qiyasGrade', 'entity',               array('label'=>'Qiyas Grade', 'class'=>'ModelBundle:QiyasGrade', 'empty_value'=>'- Select Qiyas Grade..', 'property'=>'title', 'multiple'=>false, 'attr'=>array()))
            ->add('englishGrade', 'entity',             array('label'=>'English Grade', 'class'=>'ModelBundle:EnglishGrade', 'empty_value'=>'- Select English Grade..', 'property'=>'title', 'multiple'=>false, 'attr'=>array()))
            ->add('major', 'entity',                    array('label'=>'Major', 'class'=>'ModelBundle:Major', 'empty_value'=>'- Select Majors..', 'property'=>'title', 'multiple'=>false, 'attr'=>array()))

            ->add('hearAboutUs', 'entity',              array('label'=>'Where did you hear about us?', 'class'=>'ModelBundle:HearAboutUs', 'empty_value'=>'- Where did you hear about us?..', 'property'=>'title', 'multiple'=>false, 'attr'=>array()))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wit\ModelBundle\Entity\Application'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wit_modelbundle_application';
    }
}
