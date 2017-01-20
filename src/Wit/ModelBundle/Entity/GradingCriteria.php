<?php

namespace Wit\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GradingCriteria
 *
 * @ORM\Table(name="grading_criteria")
 * @ORM\Entity
 */
class GradingCriteria
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
     * @ORM\Column(name="groupWork", type="integer")
     */
    private $groupWork;

    /**
     * @var integer
     *
     * @ORM\Column(name="assignment", type="integer")
     */
    private $assignment;

    /**
     * @var integer
     *
     * @ORM\Column(name="quiz", type="integer")
     */
    private $quiz;

    /**
     * @var integer
     *
     * @ORM\Column(name="attendance", type="integer")
     */
    private $attendance;

    /**
     * @var integer
     *
     * @ORM\Column(name="finalExam", type="integer")
     */
    private $finalExam;


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
     * Set groupWork
     *
     * @param integer $groupWork
     * @return GradingCriteria
     */
    public function setGroupWork($groupWork)
    {
        $this->groupWork = $groupWork;

        return $this;
    }

    /**
     * Get groupWork
     *
     * @return integer 
     */
    public function getGroupWork()
    {
        return $this->groupWork;
    }

    /**
     * Set assignment
     *
     * @param integer $assignment
     * @return GradingCriteria
     */
    public function setAssignment($assignment)
    {
        $this->assignment = $assignment;

        return $this;
    }

    /**
     * Get assignment
     *
     * @return integer 
     */
    public function getAssignment()
    {
        return $this->assignment;
    }

    /**
     * Set quiz
     *
     * @param integer $quiz
     * @return GradingCriteria
     */
    public function setQuiz($quiz)
    {
        $this->quiz = $quiz;

        return $this;
    }

    /**
     * Get quiz
     *
     * @return integer 
     */
    public function getQuiz()
    {
        return $this->quiz;
    }

    /**
     * Set attendance
     *
     * @param integer $attendance
     * @return GradingCriteria
     */
    public function setAttendance($attendance)
    {
        $this->attendance = $attendance;

        return $this;
    }

    /**
     * Get attendance
     *
     * @return integer 
     */
    public function getAttendance()
    {
        return $this->attendance;
    }

    /**
     * Set finalExam
     *
     * @param integer $finalExam
     * @return GradingCriteria
     */
    public function setFinalExam($finalExam)
    {
        $this->finalExam = $finalExam;

        return $this;
    }

    /**
     * Get finalExam
     *
     * @return integer 
     */
    public function getFinalExam()
    {
        return $this->finalExam;
    }
}
