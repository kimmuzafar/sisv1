<?php

namespace Wit\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GradingSystem
 *
 * @ORM\Table(name="grading_system")
 * @ORM\Entity
 */
class GradingSystem
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
     * @var integer
     *
     * @ORM\Column(name="marksMin", type="integer")
     */
    private $marksMin;

    /**
     * @var integer
     *
     * @ORM\Column(name="marksMax", type="integer")
     */
    private $marksMax;

    /**
     * @var string
     *
     * @ORM\Column(name="gradeLetter", type="string", length=16)
     */
    private $gradeLetter;

    /**
     * @var float
     *
     * @ORM\Column(name="earnedPoints", type="float")
     */
    private $earnedPoints;

    /**
     * @var string
     *
     * @ORM\Column(name="gradeDescription", type="string", length=128)
     */
    private $gradeDescription;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateUpdated", type="datetime")
     */
    private $dateUpdated;

    public function __construct()
    {
        $this->dateUpdated = new \DateTime();
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
     * Set marksMin
     *
     * @param integer $marksMin
     * @return GradingSystem
     */
    public function setMarksMin($marksMin)
    {
        $this->marksMin = $marksMin;

        return $this;
    }

    /**
     * Get marksMin
     *
     * @return integer 
     */
    public function getMarksMin()
    {
        return $this->marksMin;
    }

    /**
     * Set marksMax
     *
     * @param integer $marksMax
     * @return GradingSystem
     */
    public function setMarksMax($marksMax)
    {
        $this->marksMax = $marksMax;

        return $this;
    }

    /**
     * Get marksMax
     *
     * @return integer 
     */
    public function getMarksMax()
    {
        return $this->marksMax;
    }

    /**
     * Set gradeLetter
     *
     * @param string $gradeLetter
     * @return GradingSystem
     */
    public function setGradeLetter($gradeLetter)
    {
        $this->gradeLetter = $gradeLetter;

        return $this;
    }

    /**
     * Get gradeLetter
     *
     * @return string 
     */
    public function getGradeLetter()
    {
        return $this->gradeLetter;
    }

    /**
     * Set earnedPoints
     *
     * @param float $earnedPoints
     * @return GradingSystem
     */
    public function setEarnedPoints($earnedPoints)
    {
        $this->earnedPoints = $earnedPoints;

        return $this;
    }

    /**
     * Get earnedPoints
     *
     * @return float 
     */
    public function getEarnedPoints()
    {
        return $this->earnedPoints;
    }

    /**
     * Set gradeDescription
     *
     * @param string $gradeDescription
     * @return GradingSystem
     */
    public function setGradeDescription($gradeDescription)
    {
        $this->gradeDescription = $gradeDescription;

        return $this;
    }

    /**
     * Get gradeDescription
     *
     * @return string 
     */
    public function getGradeDescription()
    {
        return $this->gradeDescription;
    }

    /**
     * Set dateUpdated
     *
     * @param \DateTime $dateUpdated
     * @return GradingSystem
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
}
