<?php

namespace Wit\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * ApplicationGroup
 *
 * @ORM\Table(name="application_groups")
 * @ORM\Entity()
 */
class ApplicationGroup
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=128)
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity="GroupApplication", mappedBy="applicationGroup")
     **/
    private $groupApplications;

    /**
     * @ORM\ManyToOne(targetEntity="AdmissionScheduler", inversedBy="applicationGroups")
     * @ORM\JoinColumn(name="admission_scheduler_id", referencedColumnName="id", onDelete="CASCADE")
     **/
    private $admissionScheduler;
    
    public function __construct() {
        $this->groupApplications = new ArrayCollection();
    }

    /**
     * Application Group Title
     *
     * @return string
     */
    public function __toString()
    {
        return strval($this->title);
    }

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
     * Set title
     *
     * @param string $title
     * @return ApplicationGroup
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Add groupApplications
     *
     * @param \Wit\ModelBundle\Entity\GroupApplication $groupApplications
     * @return ApplicationGroup
     */
    public function addGroupApplication(\Wit\ModelBundle\Entity\GroupApplication $groupApplications)
    {
        $this->groupApplications[] = $groupApplications;

        return $this;
    }

    /**
     * Remove groupApplications
     *
     * @param \Wit\ModelBundle\Entity\GroupApplication $groupApplications
     */
    public function removeGroupApplication(\Wit\ModelBundle\Entity\GroupApplication $groupApplications)
    {
        $this->groupApplications->removeElement($groupApplications);
    }

    /**
     * Get groupApplications
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGroupApplications()
    {
        return $this->groupApplications;
    }

    /**
     * Set admissionScheduler
     *
     * @param \Wit\ModelBundle\Entity\AdmissionScheduler $admissionScheduler
     * @return ApplicationGroup
     */
    public function setAdmissionScheduler(\Wit\ModelBundle\Entity\AdmissionScheduler $admissionScheduler = null)
    {
        $this->admissionScheduler = $admissionScheduler;

        return $this;
    }

    /**
     * Get admissionScheduler
     *
     * @return \Wit\ModelBundle\Entity\AdmissionScheduler 
     */
    public function getAdmissionScheduler()
    {
        return $this->admissionScheduler;
    }
}
