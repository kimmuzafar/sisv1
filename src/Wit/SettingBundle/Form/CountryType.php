<?php

namespace Wit\SettingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CountryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', 'text', array('label'=>'Code',    'attr'=>array('class'=>'gui-input', 'placeholder'=>'Code..')))
            ->add('shortname', 'text', array('label'=>'Short Name',    'attr'=>array('class'=>'gui-input', 'placeholder'=>'Short Name..')))
            ->add('longname', 'text', array('label'=>'Long Name',    'attr'=>array('class'=>'gui-input', 'placeholder'=>'Long Name..')))
            ->add('callingcode', 'text', array('label'=>'Calling Code',    'attr'=>array('class'=>'gui-input', 'placeholder'=>'Calling Code..')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wit\ModelBundle\Entity\Country'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wit_modelbundle_country';
    }
}