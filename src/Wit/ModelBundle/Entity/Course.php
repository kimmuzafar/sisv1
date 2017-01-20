<?php

namespace Wit\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Course
 *
 * @ORM\Table(name="courses")
 * @ORM\Entity
 */
class Course
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
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=128, nullable=true)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", nullable=true)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="credit_hours", type="integer")
     */
    private $creditHours;

    /**
     * @ORM\ManyToOne(targetEntity="CourseQuarter")
     * @ORM\JoinColumn(name="coursequarter_id", referencedColumnName="id")
     **/
    private $coursequarter;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreated", type="datetime")
     */
    private $dateCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateUpdated", type="datetime")
     */
    private $dateUpdated;

    /**
     * @var boolean
     * 
     * This field will be updated from course management page
     * 0 = teachers cannot see option to submit grading tom should enable it so teachers can submit grading
     * 1 = teachers will be able to submit grading
     *
     * @ORM\Column(name="isAllowedGrading", type="boolean", nullable=true)
     */
    private $isAllowedGrading=false;

    /**
     * @var boolean
     * 
     * This field will be updated from grading controller from teacher bundle
     * to make sure that teacher has submitted grading for all students in this course
     * so grading form in bulk grading will not display to teachers again.
     *
     * @ORM\Column(name="isGradingSubmitted", type="boolean", nullable=true)
     */
    private $isGradingSubmitted=false;

    /**
     * @var integer
     * 
     * This field will be updated from performance sheet for subject page
     * DH, TOM and SAD will be able to update this field
     * 1 = submitted by DH, 2 = submitted by TOM, 3 = submitted by SAD to students (so they can see their performance sheets)
     *
     * @ORM\Column(name="performancesheetApproval", type="integer", nullable=true)
     */
    private $performancesheetApproval;

    public function __construct()
    {
        $this->dateUpdated = new \DateTime();
        //$this->isAllowedGrading = 0; //TODO: to be check if make it 0 from here
    }

    /**
     * Course Title
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
     * @return Course
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
     * Set code
     *
     * @param string $code
     * @return Course
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Course
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set creditHours
     *
     * @param integer $creditHours
     * @return Course
     */
    public function setCreditHours($creditHours)
    {
        $this->creditHours = $creditHours;

        return $this;
    }

    /**
     * Get creditHours
     *
     * @return integer 
     */
    public function getCreditHours()
    {
        return $this->creditHours;
    }

    /**
     * Set coursequarter
     *
     * @param \Wit\ModelBundle\Entity\CourseQuarter $coursequarter
     * @return Course
     */
    public function setCoursequarter(\Wit\ModelBundle\Entity\CourseQuarter $coursequarter = null)
    {
        $this->coursequarter = $coursequarter;

        return $this;
    }

    /**
     * Get coursequarter
     *
     * @return \Wit\ModelBundle\Entity\CourseQuarter 
     */
    public function getCoursequarter()
    {
        return $this->coursequarter;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return Course
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
     * @return Course
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
     * Set isGradingSubmitted
     *
     * @param boolean $isGradingSubmitted
     * @return Course
     */
    public function setIsGradingSubmitted($isGradingSubmitted)
    {
        $this->isGradingSubmitted = $isGradingSubmitted;

        return $this;
    }

    /**
     * Get isGradingSubmitted
     *
     * @return boolean 
     */
    public function getIsGradingSubmitted()
    {
        return $this->isGradingSubmitted;
    }

    /**
     * Set performancesheetApproval
     *
     * @param integer $performancesheetApproval
     * @return Course
     */
    public function setPerformancesheetApproval($performancesheetApproval)
    {
        $this->performancesheetApproval = $performancesheetApproval;

        return $this;
    }

    /**
     * Get performancesheetApproval
     *
     * @return integer 
     */
    public function getPerformancesheetApproval()
    {
        return $this->performancesheetApproval;
    }

    public function getTestTitle()
    {
        return "2000000";
    }

    /**
     * Set isAllowedGrading
     *
     * @param boolean $isAllowedGrading
     * @return Course
     */
    public function setIsAllowedGrading($isAllowedGrading)
    {
        $this->isAllowedGrading = $isAllowedGrading;

        return $this;
    }

    /**
     * Get isAllowedGrading
     *
     * @return boolean 
     */
    public function getIsAllowedGrading()
    {
        return $this->isAllowedGrading;
    }
}
