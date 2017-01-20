<?php

namespace Wit\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Course Quarters
 *
 * @ORM\Table(name="course_quarters")
 * @ORM\Entity
 */
class CourseQuarter
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
     *
     * Increamental quarter number in each batch for example: batch1 has 10 quarters so each quarter should have quarter number to identify it's order
     *
     * @var integer
     *
     * @ORM\Column(name="quarter_number", type="integer", nullable=true)
     */
    private $quarterNumber;

    /**
     * @ORM\ManyToOne(targetEntity="CourseBatch")
     * @ORM\JoinColumn(name="coursebatch_id", referencedColumnName="id")
     **/
    private $coursebatch;

    /**
     * @var boolean
     * 
     * To mark any quarter as current quarter
     * Each different 'course batch' should have atleast one 'Current Quarter'
     * 
     * 1 = Current Quarter
     * 0 = it's not current Quarter
     *
     * TOM will be able to set current quarter from his portal
     *
     * @ORM\Column(name="is_current_quarter", type="boolean", nullable=true)
     */
    private $isCurrentQuarter = false;

    /**
     * @var boolean
     * 
     * This field will only display if quarter grading was enabled or not
     * 0 = grading submission will be disabled for all courses in this quarter
     * 1 = grading submission will be enabled for all courses in this quarter
     *
     * @ORM\Column(name="isAllowedGrading", type="boolean", nullable=true)
     */
    private $isAllowedGrading=false;

    /**
     * @var boolean
     * 
     * If this field is marked as 1 it means transcripts are generated for this quarter
     *
     * @ORM\Column(name="areTranscriptsGenerated", type="boolean", nullable=true)
     */
    private $areTranscriptsGenerated=false;

    /**
     * @var boolean
     * 
     * If this field is marked as 1 it means students are able to view their transcripts if they are / were enrolled in this quarter
     *
     * @ORM\Column(name="areTranscriptsReleased", type="boolean", nullable=true)
     */
    private $areTranscriptsReleased=false;

    /**
     * CourseQuarter Title
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
     * @return CourseQuarter
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
     * Set coursebatch
     *
     * @param \Wit\ModelBundle\Entity\CourseBatch $coursebatch
     * @return CourseQuarter
     */
    public function setCoursebatch(\Wit\ModelBundle\Entity\CourseBatch $coursebatch = null)
    {
        $this->coursebatch = $coursebatch;

        return $this;
    }

    /**
     * Get coursebatch
     *
     * @return \Wit\ModelBundle\Entity\CourseBatch 
     */
    public function getCoursebatch()
    {
        return $this->coursebatch;
    }

    /**
     * Get Full Title, not a filed in database, it's computed property
     *
     * @return string 
     */
    public function getFullTitle()
    {
        return $this->getCoursebatch()->getCoursecategory()." >> ".$this->coursebatch->getTitle()." >> ".$this->getTitle();
    }

    /**
     * Set isCurrentQuarter
     *
     * @param boolean $isCurrentQuarter
     * @return CourseQuarter
     */
    public function setIsCurrentQuarter($isCurrentQuarter)
    {
        $this->isCurrentQuarter = $isCurrentQuarter;

        return $this;
    }

    /**
     * Get isCurrentQuarter
     *
     * @return boolean 
     */
    public function getIsCurrentQuarter()
    {
        return $this->isCurrentQuarter;
    }

    /**
     * Set isAllowedGrading
     *
     * @param boolean $isAllowedGrading
     * @return CourseQuarter
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

    /**
     * Set quarterNumber
     *
     * @param integer $quarterNumber
     * @return CourseQuarter
     */
    public function setQuarterNumber($quarterNumber)
    {
        $this->quarterNumber = $quarterNumber;

        return $this;
    }

    /**
     * Get quarterNumber
     *
     * @return integer 
     */
    public function getQuarterNumber()
    {
        return $this->quarterNumber;
    }

    /**
     * Set areTranscriptsGenerated
     *
     * @param boolean $areTranscriptsGenerated
     * @return CourseQuarter
     */
    public function setAreTranscriptsGenerated($areTranscriptsGenerated)
    {
        $this->areTranscriptsGenerated = $areTranscriptsGenerated;

        return $this;
    }

    /**
     * Get areTranscriptsGenerated
     *
     * @return boolean 
     */
    public function getAreTranscriptsGenerated()
    {
        return $this->areTranscriptsGenerated;
    }

    /**
     * Set areTranscriptsReleased
     *
     * @param boolean $areTranscriptsReleased
     * @return CourseQuarter
     */
    public function setAreTranscriptsReleased($areTranscriptsReleased)
    {
        $this->areTranscriptsReleased = $areTranscriptsReleased;

        return $this;
    }

    /**
     * Get areTranscriptsReleased
     *
     * @return boolean 
     */
    public function getAreTranscriptsReleased()
    {
        return $this->areTranscriptsReleased;
    }
}
