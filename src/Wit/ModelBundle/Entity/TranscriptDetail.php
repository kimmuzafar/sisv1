<?php

namespace Wit\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TranscriptDetail
 *
 * @ORM\Table(name="transcript_details")
 * @ORM\Entity
 */
class TranscriptDetail
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
     *
     * Course Title
     *
     * @var string
     *
     * @ORM\Column(name="course", type="string", length=255, nullable=true)
     */
    private $course;

    /**
     *
     * Course Code
     *
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=16, nullable=true)
     */
    private $code;

    /**
     *
     * Course credit hours
     *
     * @var decimal
     *
     * @ORM\Column(name="credit_hours", type="decimal", scale=2, nullable=true)
     */
    private $creditHours;

    


    /**
     *
     * TOBE CHECKED: Total Marks in first / initial exam for this course
     *
     * @var decimal
     *
     * @ORM\Column(name="marks", type="decimal", scale=2, nullable=true)
     */
    private $marks;

    /**
     *
     * Grade in first / initial exam for this course
     *
     * @var string
     *
     * @ORM\Column(name="grade", type="string", length=16, nullable=true)
     */
    private $grade;

    /**
     *
     * earned points based on grade to be fetched from grading system, respective to each grade letter A, B,C or fail
     *
     * @var decimal
     *
     * @ORM\Column(name="points", type="decimal", scale=2, nullable=true)
     */
    private $points;





    /**
     *
     * total earned points per subject or course
     * CreditHours * Points = TotalPoints per subject
     * 
     *
     * @var decimal
     *
     * @ORM\Column(name="subject_total_points", type="decimal", scale=2, nullable=true)
     */
    private $subjectTotalPoints;





    /**
     * 
     * to help determine if student has retakes
     * 
     * @var boolean
     *
     * @ORM\Column(name="has_retakes", type="boolean", nullable=true)
     */
    private $hasRetakes = false;






    /**
     *
     * Pass / Fail / something else decided eventually
     *
     * @var string
     *
     * @ORM\Column(name="remarks", type="string", length=255, nullable=true)
     */
    private $remarks;





    /**
     * @ORM\ManyToOne(targetEntity="Transcript", inversedBy="transcriptDetails")
     * @ORM\JoinColumn(name="transcript_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $transcript;
    

    

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
     * Set course
     *
     * @param string $course
     * @return TranscriptDetail
     */
    public function setCourse($course)
    {
        $this->course = $course;

        return $this;
    }

    /**
     * Get course
     *
     * @return string 
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return TranscriptDetail
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
     * Set creditHours
     *
     * @param string $creditHours
     * @return TranscriptDetail
     */
    public function setCreditHours($creditHours)
    {
        $this->creditHours = $creditHours;

        return $this;
    }

    /**
     * Get creditHours
     *
     * @return string 
     */
    public function getCreditHours()
    {
        return $this->creditHours;
    }

    /**
     * Set marks
     *
     * @param string $marks
     * @return TranscriptDetail
     */
    public function setMarks($marks)
    {
        $this->marks = $marks;

        return $this;
    }

    /**
     * Get marks
     *
     * @return string 
     */
    public function getMarks()
    {
        return $this->marks;
    }

    /**
     * Set grade
     *
     * @param string $grade
     * @return TranscriptDetail
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * Get grade
     *
     * @return string 
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * Set points
     *
     * @param string $points
     * @return TranscriptDetail
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return string 
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set subjectTotalPoints
     *
     * @param string $subjectTotalPoints
     * @return TranscriptDetail
     */
    public function setSubjectTotalPoints($subjectTotalPoints)
    {
        $this->subjectTotalPoints = $subjectTotalPoints;

        return $this;
    }

    /**
     * Get subjectTotalPoints
     *
     * @return string 
     */
    public function getSubjectTotalPoints()
    {
        return $this->subjectTotalPoints;
    }

    /**
     * Set hasRetakes
     *
     * @param boolean $hasRetakes
     * @return TranscriptDetail
     */
    public function setHasRetakes($hasRetakes)
    {
        $this->hasRetakes = $hasRetakes;

        return $this;
    }

    /**
     * Get hasRetakes
     *
     * @return boolean 
     */
    public function getHasRetakes()
    {
        return $this->hasRetakes;
    }

    /**
     * Set remarks
     *
     * @param string $remarks
     * @return TranscriptDetail
     */
    public function setRemarks($remarks)
    {
        $this->remarks = $remarks;

        return $this;
    }

    /**
     * Get remarks
     *
     * @return string 
     */
    public function getRemarks()
    {
        return $this->remarks;
    }

    /**
     * Set transcript
     *
     * @param \Wit\ModelBundle\Entity\Transcript $transcript
     * @return TranscriptDetail
     */
    public function setTranscript(\Wit\ModelBundle\Entity\Transcript $transcript = null)
    {
        $this->transcript = $transcript;

        return $this;
    }

    /**
     * Get transcript
     *
     * @return \Wit\ModelBundle\Entity\Transcript 
     */
    public function getTranscript()
    {
        return $this->transcript;
    }
}
