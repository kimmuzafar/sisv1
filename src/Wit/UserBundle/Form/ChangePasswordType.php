<?php

namespace Wit\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('oldPassword', 'password', array(
            'label'=>'Old Password', 'attr'=>array('class'=>'gui-input', 'placeholder'=>'Old Password')));
        $builder->add('newPassword', 'repeated', array(
            'type' => 'password',
            'invalid_message' => 'The password fields must match.',
            'required' => true,
            'constraints' => new NotBlank(),
            'first_options'  => array('label' => 'New Password', 'attr'=>array('class'=>'gui-input', 'placeholder'=>'New Password'),),
            'second_options' => array('label' => 'Repeat New Password', 'attr'=>array('class'=>'gui-input', 'placeholder'=>'Repeat New Password'),),
        ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wit\UserBundle\Form\Model\ChangePassword'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'change_passwd';
    }
}