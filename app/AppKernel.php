<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

// setting the default time zone
date_default_timezone_set('UTC');

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Wit\SADBundle\SADBundle(),
            new Wit\ModelBundle\ModelBundle(),
            new Wit\UserBundle\UserBundle(),
            new Wit\AuthBundle\AuthBundle(),
            new Vich\UploaderBundle\VichUploaderBundle(),
            new Wit\AdminBundle\AdminBundle(),
            new Wit\TODBundle\TODBundle(),
            new FOS\ElasticaBundle\FOSElasticaBundle(),
            new Wit\SettingBundle\SettingBundle(),
            new Wit\AccountBundle\AccountBundle(),
            new Wit\TeacherBundle\TeacherBundle(),
            new Wit\StudentBundle\StudentBundle(),
            new Wit\DHBundle\DHBundle(),
            new Wit\CourseManagementBundle\CourseManagementBundle(),
            new Wit\UserEnrolmentBundle\UserEnrolmentBundle(),
            new Wit\PerformanceBundle\PerformanceBundle(),
            new Wit\QuickNewsBundle\QuickNewsBundle(),
            new Wit\TranscriptBundle\TranscriptBundle(),
            new Wit\OnboardingBundle\OnboardingBundle(),
            new Knp\Bundle\SnappyBundle\KnpSnappyBundle(),
            new Wit\DefaultBundle\DefaultBundle(),
            new Wit\SendEmailBundle\SendEmailBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
    
    public function init()
    {
        date_default_timezone_set('Asia/Riyadh');
        parent::init();
    }
}
