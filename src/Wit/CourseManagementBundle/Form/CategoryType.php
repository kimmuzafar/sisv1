<?php

namespace Wit\CourseManagementBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array('label'=>'Name',    'attr'=>array('class'=>'gui-input', 'placeholder'=>'Name..')))
            ->add('code', 'text', array('label'=>'Category Code',    'attr'=>array('class'=>'gui-input', 'placeholder'=>'Category Code..')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wit\ModelBundle\Entity\CourseCategory'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wit_modelbundle_coursecategory';
    }
}
