<?php

namespace Wit\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * AdmissionScheduler
 *
 * @ORM\Table(name="admission_scheduler")
 * @ORM\Entity(repositoryClass="Wit\ModelBundle\Entity\AdmissionSchedulerRepository")
 * @Vich\Uploadable
 */
class AdmissionScheduler
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
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="detail", type="text")
     * @Assert\NotBlank()
     */
    private $detail;

    /**
     * @var \DateTime
     *
     * Starting date for users to start submit applications against this schedule
     *
     * @ORM\Column(name="startDate", type="datetime")
     * @Assert\NotBlank()
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * Ending date for this schedule, user won't be able to submit applications against this schedule after this date
     *
     * @ORM\Column(name="endDate", type="datetime")
     * @Assert\NotBlank()
     */
    private $endDate;
    
    /**
     * @var string
     *
     * This number will be initialy taken from SAD during schedule creation
     * further part of it will be updated on each application submission
     * example format: YEAR-FALL-00001
     * example format: YEAR-SPRING-00002
     *
     * Note: this field will also help identify total number of applicaitons submitted against this schedule
     *
     * @ORM\Column(name="application_ref_no", type="string", length=128, nullable=true)
     */
    private $applicationsRefNo;
    
    /**
     * @var string
     *
     * This is user name should be taken from logged in user session, get stay identify who created this schedule
     *
     * @ORM\Column(name="created_by_name", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $createdByName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime")
     */
    private $dateCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_updated", type="datetime")
     */
    private $dateUpdated;
    
    /**
     * @ORM\OneToMany(targetEntity="Application", mappedBy="admissionScheduler")
     **/
    private $applications;

    /**
     * @var boolean
     * 
     * This field will check if current record is active for people to apply for 
     * More than 1 cannot be active at a time..
     * 
     * @ORM\Column(name="isOpen", type="boolean", options={"default" = 0}, nullable=true)
     */
    private $isOpen;

    /**
     * @var string
     *
     * @ORM\Column(name="doc_ad_photo", type="string", length=255, nullable=true)
     */
    private $docAdPhoto;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="admission_scheduler_files", fileNameProperty="docAdPhoto")
     * 
     * @var File $docAdPhotoFile
     */
    private $docAdPhotoFile;
    
    /**
     * @ORM\OneToMany(targetEntity="ApplicationGroup", mappedBy="admissionScheduler")
     **/
    private $applicationGroups;

    public function __construct()
    {
        $this->dateUpdated = new \DateTime();
        
        $this->applications = new ArrayCollection();
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
     * @return AdmissionScheduler
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
     * Set detail
     *
     * @param string $detail
     * @return AdmissionScheduler
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;

        return $this;
    }

    /**
     * Get detail
     *
     * @return string 
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return AdmissionScheduler
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return AdmissionScheduler
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->endDate;
    }
    
    /**
     * Set applicationsRefNo
     *
     * @param string $applicationsRefNo
     * @return AdmissionScheduler
     */
    public function setApplicationsRefNo($applicationsRefNo)
    {
        $this->applicationsRefNo = $applicationsRefNo;

        return $this;
    }

    /**
     * Get applicationsRefNo
     *
     * @return string 
     */
    public function getApplicationsRefNo()
    {
        return $this->applicationsRefNo;
    }
    
    /**
     * Set createdByName
     *
     * @param string $createdByName
     * @return AdmissionScheduler
     */
    public function setCreatedByName($createdByName)
    {
        $this->createdByName = $createdByName;

        return $this;
    }

    /**
     * Get createdByName
     *
     * @return string 
     */
    public function getCreatedByName()
    {
        return $this->createdByName;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return AdmissionScheduler
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime 
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set dateUpdated
     *
     * @param \DateTime $dateUpdated
     * @return AdmissionScheduler
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;

        return $this;
    }

    /**
     * Get dateUpdated
     *
     * @return \DateTime 
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    /**
     * Add applications
     *
     * @param \Wit\ModelBundle\Entity\Application $applications
     * @return AdmissionScheduler
     */
    public function addApplication(\Wit\ModelBundle\Entity\Application $applications)
    {
        $this->applications[] = $applications;

        return $this;
    }

    /**
     * Remove applications
     *
     * @param \Wit\ModelBundle\Entity\Application $applications
     */
    public function removeApplication(\Wit\ModelBundle\Entity\Application $applications)
    {
        $this->applications->removeElement($applications);
    }

    /**
     * Get applications
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getApplications()
    {
        return $this->applications;
    }

    /**
     * Set isOpen
     *
     * @param boolean $isOpen
     * @return AdmissionScheduler
     */
    public function setIsOpen($isOpen)
    {
        $this->isOpen = $isOpen;

        return $this;
    }

    /**
     * Get isOpen
     *
     * @return boolean 
     */
    public function getIsOpen()
    {
        return $this->isOpen;
    }


    /**
     * Set docAdPhoto
     *
     * @param string $docAdPhoto
     * @return AdmissionScheduler
     */
    public function setDocAdPhoto($docAdPhoto)
    {
        $this->docAdPhoto = $docAdPhoto;

        return $this;
    }

    /**
     * Get docAdPhoto
     *
     * @return string 
     */
    public function getDocAdPhoto()
    {
        return $this->docAdPhoto;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file
     */
    public function setDocAdPhotoFile(File $file = null)
    {
        $this->docAdPhotoFile = $file;

        if ($file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->dateUpdated = new \DateTime();
        }
    }

    /**
     * @return File
     */
    public function getDocAdPhotoFile()
    {
        return $this->docAdPhotoFile;
    }


    /**
     * Add applicationGroups
     *
     * @param \Wit\ModelBundle\Entity\ApplicationGroup $applicationGroups
     * @return AdmissionScheduler
     */
    public function addApplicationGroup(\Wit\ModelBundle\Entity\ApplicationGroup $applicationGroups)
    {
        $this->applicationGroups[] = $applicationGroups;

        return $this;
    }

    /**
     * Remove applicationGroups
     *
     * @param \Wit\ModelBundle\Entity\ApplicationGroup $applicationGroups
     */
    public function removeApplicationGroup(\Wit\ModelBundle\Entity\ApplicationGroup $applicationGroups)
    {
        $this->applicationGroups->removeElement($applicationGroups);
    }

    /**
     * Get applicationGroups
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getApplicationGroups()
    {
        return $this->applicationGroups;
    }
}
