<?php

namespace Wit\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ApplicationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', 'text',                  array('label'=>'First Name',                    'attr'=>array('class'=>'gui-input', 'placeholder'=>'First Name')))
            ->add('fathersName', 'text',                array('label'=>'Fathers Name',                  'attr'=>array('class'=>'gui-input', 'placeholder'=>'Fathers Name')))
            ->add('grandFathersName', 'text',           array('label'=>'Grand Fathers Name',            'attr'=>array('class'=>'gui-input', 'placeholder'=>'Grand Fathers Name')))
            ->add('familyName', 'text',                 array('label'=>'Family Name',                   'attr'=>array('class'=>'gui-input', 'placeholder'=>'Family Name')))
            ->add('maritalStatus', 'entity',            array('label'=>'Marital Status', 'class'=>'ModelBundle:MaritalStatus', 'empty_value'=>'- Select Marital Status..', 'property'=>'title', 'multiple'=>false, 'attr'=>array()))
            ->add('birthDate', 'text',                  array('label'=>'Birth Date',                    'attr'=>array('class'=>'gui-input', 'placeholder'=>'Birth Date: mm/dd/yyyy')))
            ->add('nationalId', 'text',                 array('label'=>'National Id',                   'attr'=>array('class'=>'gui-input', 'placeholder'=>'National Id')))
            ->add('gender', 'entity',                   array('label'=>'Gender', 'class'=>'ModelBundle:Gender', 'empty_value'=>'- Select Gender..', 'property'=>'title', 'multiple'=>false, 'attr'=>array()))
            
            ->add('currentLivingPlace', 'entity',       array('label'=>'Current Living Place', 'class'=>'ModelBundle:City', 'empty_value'=>'- Select Current Living Place..', 'property'=>'title', 'multiple'=>false, 'attr'=>array()))
            ->add('currentLivingPlaceOther', 'text',    array('label'=>'Current Living Place Others',   'required'=>false,   'attr'=>array('class'=>'gui-input', 'placeholder'=>'Current Living Place Others')))
            ->add('houseNo', 'text',                    array('label'=>'House No.',                     'required'=>false, 'attr'=>array('class'=>'gui-input', 'placeholder'=>'House No.')))
            ->add('streetName', 'text',                 array('label'=>'Street Name',                   'attr'=>array('class'=>'gui-input', 'placeholder'=>'Street Name')))
            ->add('pobox', 'text',                      array('label'=>'P.O.Box',                       'required'=>false, 'attr'=>array('class'=>'gui-input', 'placeholder'=>'P.O.Box')))
            ->add('city', 'entity',                     array('label'=>'City', 'class'=>'ModelBundle:City', 'empty_value'=>'- Select City..', 'property'=>'title', 'multiple'=>false, 'attr'=>array()))
            ->add('country', 'entity',                  array('label'=>'Country', 'class'=>'ModelBundle:Country', 'empty_value'=>'- Select Country..', 'property'=>'shortname', 'multiple'=>false, 'attr'=>array()))
            
            ->add('telephone', 'text',                  array('label'=>'Telephone',                     'required'=>false, 'attr'=>array('class'=>'gui-input', 'placeholder'=>'Telephone')))
            ->add('mobile', 'text',                     array('label'=>'Mobile',                        'attr'=>array('class'=>'gui-input mobile', 'placeholder'=>'Mobile: 966596698827')))
            ->add('email', 'text',                      array('label'=>'Email',                         'attr'=>array('class'=>'gui-input', 'placeholder'=>'Email')))
            
            ->add('permanentHouseNo', 'text',           array('label'=>'House No.',                     'required'=>false, 'attr'=>array('class'=>'gui-input', 'placeholder'=>'House No.')))
            ->add('permanentStreetName', 'text',        array('label'=>'Street Name',                   'attr'=>array('class'=>'gui-input', 'placeholder'=>'Street Name')))
            ->add('permanentPobox', 'text',             array('label'=>'P.O.Box',                       'required'=>false, 'attr'=>array('class'=>'gui-input', 'placeholder'=>'P.O.Box')))
            ->add('permanentPostalCode', 'text',        array('label'=>'Postal Code',                   'required'=>false, 'attr'=>array('class'=>'gui-input', 'placeholder'=>'Postal Code')))
            ->add('permanentCity', 'entity',            array('label'=>'City', 'class'=>'ModelBundle:City', 'empty_value'=>'- Select City..', 'property'=>'title', 'multiple'=>false, 'attr'=>array()))
            ->add('permanentCountry', 'entity',         array('label'=>'Country', 'class'=>'ModelBundle:Country', 'empty_value'=>'- Select Country..', 'property'=>'shortname', 'multiple'=>false, 'attr'=>array()))
            
            ->add('emergency1Name', 'text',             array('label'=>'Emergency ',                    'attr'=>array('class'=>'gui-input', 'placeholder'=>'Name')))
            ->add('emergency1Mobile', 'text',           array('label'=>'Mobile',                        'attr'=>array('class'=>'gui-input mobile', 'placeholder'=>'Mobile: 966596698827')))
            ->add('emergency1Phone', 'text',            array('label'=>'Phone',                         'required'=>false, 'attr'=>array('class'=>'gui-input', 'placeholder'=>'Phone')))
            
            ->add('emergency2Name', 'text',             array('label'=>'Name',                          'attr'=>array('class'=>'gui-input', 'placeholder'=>'Name')))
            ->add('emergency2Mobile', 'text',           array('label'=>'Mobile',                        'attr'=>array('class'=>'gui-input mobile', 'placeholder'=>'Mobile: 966596698827')))
            ->add('emergency2Phone', 'text',            array('label'=>'Phone',                         'required'=>false, 'attr'=>array('class'=>'gui-input', 'placeholder'=>'Phone')))
            
            ->add('studyLevel', 'entity',               array('label'=>'Study Level', 'class'=>'ModelBundle:StudyLevel', 'empty_value'=>'- Select Study Level..', 'property'=>'title', 'multiple'=>false, 'attr'=>array()))
            ->add('studyLevelOther', 'text',            array('label'=>'Study Level Others',            'required'=>false,                  'attr'=>array('class'=>'gui-input', 'placeholder'=>'Study Level Others')))
            ->add('completionDate', 'entity',           array('label'=>'Completion Date', 'class'=>'ModelBundle:CompletionDate', 'empty_value'=>'- Select Completion Date..', 'property'=>'title', 'multiple'=>false, 'attr'=>array()))
            ->add('completionCity', 'entity',           array('label'=>'Completion City', 'class'=>'ModelBundle:City', 'property'=>'title', 'multiple'=>false, 'attr'=>array()))
            ->add('completionCityOther', 'text',        array('label'=>'Completion City Others',        'required'=>false,        'attr'=>array('class'=>'gui-input', 'placeholder'=>'Completion City Others')))
            ->add('grade', 'entity',                    array('label'=>'Grade', 'class'=>'ModelBundle:Grade', 'empty_value'=>'- Select Grade..', 'property'=>'title', 'multiple'=>false, 'attr'=>array()))
            ->add('accumulatedGrade', 'entity',         array('label'=>'Accummulated Grade', 'class'=>'ModelBundle:AccumulatedGrade', 'empty_value'=>'- Select Accumulated Grade..', 'property'=>'title', 'multiple'=>false, 'attr'=>array()))
            ->add('qiyasGrade', 'entity',               array('label'=>'Qiyas Grade', 'class'=>'ModelBundle:QiyasGrade', 'empty_value'=>'- Select Qiyas Grade..', 'property'=>'title', 'multiple'=>false, 'attr'=>array()))
            ->add('englishGrade', 'entity',             array('label'=>'English Grade', 'class'=>'ModelBundle:EnglishGrade', 'empty_value'=>'- Select English Grade..', 'property'=>'title', 'multiple'=>false, 'attr'=>array()))
            ->add('major', 'entity',                    array('label'=>'Major', 'class'=>'ModelBundle:Major', 'empty_value'=>'- Select Majors..', 'property'=>'title', 'multiple'=>false, 'attr'=>array()))
            ->add('majorOther', 'text',                 array('label'=>'Major Others',                  'required'=>false,                  'attr'=>array('class'=>'gui-input', 'placeholder'=>'Major Others')))
            
            ->add('docNationalIdFile', 'file',          array('label'=>'National Id', 'required' => false,                  'attr'=>array('class'=>'gui-file', 'onChange'=>"document.getElementById('uploadfile1').value = this.value;")))
            ->add('docCertificateFile', 'file',         array('label'=>'Certificate', 'required' => false,                  'attr'=>array('class'=>'gui-file', 'onChange'=>"document.getElementById('uploadfile2').value = this.value;")))
            ->add('docQiyasCertificateFile', 'file',    array('label'=>'Qiyas Certificate', 'required' => false,            'attr'=>array('class'=>'gui-file', 'onChange'=>"document.getElementById('uploadfile3').value = this.value;")))
            ->add('docYourPhotoFile', 'file',           array('label'=>'Your Photo', 'required' => false,                   'attr'=>array('class'=>'gui-file', 'onChange'=>"document.getElementById('uploadfile4').value = this.value;")))

            ->add('hearAboutUs', 'entity',              array('label'=>'Where did you hear about us?', 'class'=>'ModelBundle:HearAboutUs', 'empty_value'=>'- Where did you hear about us?..', 'property'=>'title', 'multiple'=>false, 'attr'=>array()))

            ->add('hearAboutUsOther', 'text',            array('label'=>'Hear about us other',          'required'=>false,                  'attr'=>array('class'=>'gui-input', 'placeholder'=>'Hear about us other')))
            
            ->add('admissionScheduler', 'entity',       array('label'=>'Admission Schedule', 'class'=>'ModelBundle:AdmissionScheduler', 
                'query_builder'=>function(\Doctrine\ORM\EntityRepository $er){
                    return $er->createQueryBuilder("ascheduler")
                    ->where("ascheduler.isOpen=1")
                    ->orderBy("ascheduler.id", "ASC");
                    /*->setMaxResults(1);*/
                }, 
                'property'=>'title', 'multiple'=>false, 'attr'=>array())
            )
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
