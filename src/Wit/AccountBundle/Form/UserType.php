<?php

namespace Wit\AccountBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname',  'text', array('label'=>'First Name',    'attr'=>array('class'=>'gui-input', 'placeholder'=>'First Name..')))
            ->add('lastname',   'text', array('label'=>'Last Name',     'attr'=>array('class'=>'gui-input', 'placeholder'=>'Last Name..')))
            ->add('email',      'text', array('label'=>'Email',         'attr'=>array('class'=>'gui-input', 'placeholder'=>'Email..')))
            ->add('nationalid', 'text', array('label'=>'National ID',   'attr'=>array('class'=>'gui-input', 'placeholder'=>'National ID..')))
            ->add('password',   'password', array('label'=>'Password',  'attr'=>array('class'=>'gui-input', 'placeholder'=>'Password..')))
            ->add('roles',      'entity',   array('label'=>'Role', 'class'=>'ModelBundle:Role', 'empty_value'=>'- Select Role..', 'property'=>'role', 'multiple'=>false, 'attr'=>array()))

            ->add('mobile', 'text', array('label'=>'Mobile', 'attr'=>array('class'=>'gui-input', 'placeholder'=>'Mobile')))
            ->add('gender', 'entity', array('label'=>'Gender', 'class'=>'ModelBundle:Gender', 'empty_value'=>'- Select Gender..', 'property'=>'title', 'multiple'=>false, 'attr'=>array()))
            ->add('country', 'entity', array('label'=>'Country', 'class'=>'ModelBundle:Country', 'empty_value'=>'- Select Country..', 'property'=>'shortname', 'multiple'=>false, 'attr'=>array()))
            ->add('docYourPhotoFile', 'file', array('label'=>'Your Photo', 'attr'=>array('class'=>'gui-file', 'onChange'=>"document.getElementById('uploadfile4').value = this.value;")))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wit\ModelBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wit_modelbundle_user';
    }
}
