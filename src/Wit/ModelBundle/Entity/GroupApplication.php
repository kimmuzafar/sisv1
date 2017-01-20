<?php

namespace Wit\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GroupApplication
 * 
 * Table generated through this entity class will hold all application and can be filtered through it's associated group
 *
 * @ORM\Table(name="group_applications")
 * @ORM\Entity()
 */
class GroupApplication
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="ApplicationGroup", inversedBy="groupApplications")
     * @ORM\JoinColumn(name="applicationgroup_id", referencedColumnName="id", onDelete="CASCADE")
     **/
    private $applicationGroup;

    /**
     * @ORM\ManyToOne(targetEntity="Application")
     * @ORM\JoinColumn(name="application_id", referencedColumnName="id")
     **/
    private $application;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set applicationGroup
     *
     * @param \Wit\ModelBundle\Entity\ApplicationGroup $applicationGroup
     * @return GroupApplication
     */
    public function setApplicationGroup(\Wit\ModelBundle\Entity\ApplicationGroup $applicationGroup = null)
    {
        $this->applicationGroup = $applicationGroup;

        return $this;
    }

    /**
     * Get applicationGroup
     *
     * @return \Wit\ModelBundle\Entity\ApplicationGroup 
     */
    public function getApplicationGroup()
    {
        return $this->applicationGroup;
    }

    /**
     * Set application
     *
     * @param \Wit\ModelBundle\Entity\Application $application
     * @return GroupApplication
     */
    public function setApplication(\Wit\ModelBundle\Entity\Application $application = null)
    {
        $this->application = $application;

        return $this;
    }

    /**
     * Get application
     *
     * @return \Wit\ModelBundle\Entity\Application 
     */
    public function getApplication()
    {
        return $this->application;
    }
}
