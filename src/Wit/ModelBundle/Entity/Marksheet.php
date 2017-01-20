<?php

namespace Wit\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Marksheet
 *
 * @ORM\Table(name="marksheets")
 * @ORM\Entity
 */
class Marksheet
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
     * @ORM\Column(name="courseTitle", type="string", length=255, nullable=true)
     */
    private $courseTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="courseCode", type="string", length=16, nullable=true)
     */
    private $courseCode;

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
     * @var string
     *
     * @ORM\Column(name="program", type="string", length=255, nullable=true)
     */
    private $program;

    /**
     *
     * To help identify which program this user belongs to if he want to refer back to program anytime
     * To be used in transcript as well..
     * 
     * @var string
     *
     * @ORM\Column(name="programCode", type="string", length=16, nullable=true)
     */
    private $programCode;

    /**
     * @var string
     *
     * @ORM\Column(name="batch", type="string", length=128, nullable=true)
     */
    private $batch;

    /**
     *
     * To help identify which batch this user belongs to if he want to refer back to batch anytime
     * To be used in transcript as well..
     * 
     * @var string
     *
     * @ORM\Column(name="batchCode", type="string", length=16, nullable=true)
     */
    private $batchCode;

    /**
     * @var string
     *
     * @ORM\Column(name="quarter", type="string", length=128, nullable=true)
     */
    private $quarter;

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
     *
     * Quarter id for referencing back to it
     *
     * @var integer
     *
     * @ORM\Column(name="quarter_id", type="integer", nullable=true)
     */
    private $quarterId;

    /**
     * @var string
     *
     * @ORM\Column(name="sid", type="string", length=32, nullable=true)
     */
    private $sid;

    /**
     * @var string
     *
     * @ORM\Column(name="nameEnglish", type="string", length=255, nullable=true)
     */
    private $nameEnglish;

    /**
     * @var string
     *
     * @ORM\Column(name="nameArabic", type="string", length=255, nullable=true)
     */
    private $nameArabic;

    /**
     * @var string
     *
     * @ORM\Column(name="traineeid", type="string", length=50, nullable=true)
     */
    private $traineeid;

    /**
     * @var decimal
     *
     * @ORM\Column(name="groupWork", type="decimal", scale=2)
     */
    private $groupWork;
    
    /**
     * @var decimal
     *
     * @ORM\Column(name="assignment", type="decimal", scale=2)
     */
    private $assignment;

    /**
     * @var decimal
     *
     * @ORM\Column(name="quiz", type="decimal", scale=2)
     */
    private $quiz;

    /**
     * @var decimal
     *
     * @ORM\Column(name="attendance", type="decimal", scale=2)
     */
    private $attendance;

    /**
     * @var decimal
     *
     * @ORM\Column(name="finalExam", type="decimal", scale=2)
     */
    private $finalExam;

    /**
     * @var decimal
     *
     * @ORM\Column(name="totalMarks", type="decimal", scale=2, nullable=true)
     */
    private $totalMarks;

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
     * @var string
     *
     * Teacher remarks
     *
     * @ORM\Column(name="remarks", type="string", length=255, nullable=true)
     */
    private $remarks;

    /**
     * @var string
     *
     * @ORM\Column(name="teacherName", type="string", length=255, nullable=true)
     */
    private $teacherName;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="isCheckedByDH", type="boolean", nullable=true)
     */
    private $isCheckedByDH;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isCheckedByTOM", type="boolean", nullable=true)
     */
    private $isCheckedByTOM;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isCheckedBySAD", type="boolean", nullable=true)
     */
    private $isCheckedBySAD;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isCheckedByHR", type="boolean", nullable=true)
     */
    private $isCheckedByHR;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isReleasedForStudent", type="boolean", nullable=true)
     */
    private $isReleasedForStudent;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreated", type="datetime", nullable=true)
     */
    private $dateCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateUpdated", type="datetime")
     */
    private $dateUpdated;

    /**
     * @var integer
     * 
     * student id from users table will be recorded here so can get back to him
     *
     * @ORM\Column(name="student_id", type="integer", nullable=true)
     */
    private $studentId;

    /**
     * @var integer
     * 
     * teacher id from users table will be recorded here so can get back to him
     *
     * @ORM\Column(name="teacher_id", type="integer", nullable=true)
     */
    private $teacherId;

    /**
     * @var integer
     * 
     * course id from course table will be recorded here so can get back to him
     *
     * @ORM\Column(name="course_id", type="integer", nullable=true)
     */
    private $courseId;

    /**
     * 
     * This will help in determining if retake for current marksheet is enabled by TOM or not
     * Retake can not be enabled for a retake again, it can only be enabled against original marksheet / performance
     * Once retake is submitted by teacher, it should be set back to 0
     * 
     * 1 = if retake is enabled by TOM
     * 0 = no retake to be submitted
     * 
     * @var boolean
     *
     * @ORM\Column(name="is_retake_enabled", type="boolean", nullable=true)
     */
    private $isRetakeEnabled = false;

    /**
     * This will help determine is this record actually a retake and which re-take is this 1, 2 or 3 etc
     * 
     * @var integer
     *
     * @ORM\Column(name="retake_number", type="integer", nullable=true)
     */
    private $retakeNumber = 0;





    
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
     * Set courseTitle
     *
     * @param string $courseTitle
     * @return Marksheet
     */
    public function setCourseTitle($courseTitle)
    {
        $this->courseTitle = $courseTitle;

        return $this;
    }

    /**
     * Get courseTitle
     *
     * @return string 
     */
    public function getCourseTitle()
    {
        return $this->courseTitle;
    }

    /**
     * Set courseCode
     *
     * @param string $courseCode
     * @return Marksheet
     */
    public function setCourseCode($courseCode)
    {
        $this->courseCode = $courseCode;

        return $this;
    }

    /**
     * Get courseCode
     *
     * @return string 
     */
    public function getCourseCode()
    {
        return $this->courseCode;
    }

    /**
     * Set creditHours
     *
     * @param string $creditHours
     * @return Marksheet
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
     * Set program
     *
     * @param string $program
     * @return Marksheet
     */
    public function setProgram($program)
    {
        $this->program = $program;

        return $this;
    }

    /**
     * Get program
     *
     * @return string 
     */
    public function getProgram()
    {
        return $this->program;
    }

    /**
     * Set batch
     *
     * @param string $batch
     * @return Marksheet
     */
    public function setBatch($batch)
    {
        $this->batch = $batch;

        return $this;
    }

    /**
     * Get batch
     *
     * @return string 
     */
    public function getBatch()
    {
        return $this->batch;
    }

    /**
     * Set quarter
     *
     * @param string $quarter
     * @return Marksheet
     */
    public function setQuarter($quarter)
    {
        $this->quarter = $quarter;

        return $this;
    }

    /**
     * Get quarter
     *
     * @return string 
     */
    public function getQuarter()
    {
        return $this->quarter;
    }

    /**
     * Set sid
     *
     * @param string $sid
     * @return Marksheet
     */
    public function setSid($sid)
    {
        $this->sid = $sid;

        return $this;
    }

    /**
     * Get sid
     *
     * @return string 
     */
    public function getSid()
    {
        return $this->sid;
    }

    /**
     * Set nameEnglish
     *
     * @param string $nameEnglish
     * @return Marksheet
     */
    public function setNameEnglish($nameEnglish)
    {
        $this->nameEnglish = $nameEnglish;

        return $this;
    }

    /**
     * Get nameEnglish
     *
     * @return string 
     */
    public function getNameEnglish()
    {
        return $this->nameEnglish;
    }

    /**
     * Set nameArabic
     *
     * @param string $nameArabic
     * @return Marksheet
     */
    public function setNameArabic($nameArabic)
    {
        $this->nameArabic = $nameArabic;

        return $this;
    }

    /**
     * Get nameArabic
     *
     * @return string 
     */
    public function getNameArabic()
    {
        return $this->nameArabic;
    }

    /**
     * Set traineeid
     *
     * @param string $traineeid
     * @return Marksheet
     */
    public function setTraineeid($traineeid)
    {
        $this->traineeid = $traineeid;

        return $this;
    }

    /**
     * Get traineeid
     *
     * @return string 
     */
    public function getTraineeid()
    {
        return $this->traineeid;
    }

    /**
     * Set groupWork
     *
     * @param string $groupWork
     * @return Marksheet
     */
    public function setGroupWork($groupWork)
    {
        $this->groupWork = $groupWork;

        return $this;
    }

    /**
     * Get groupWork
     *
     * @return string 
     */
    public function getGroupWork()
    {
        return $this->groupWork;
    }

    /**
     * Set assignment
     *
     * @param string $assignment
     * @return Marksheet
     */
    public function setAssignment($assignment)
    {
        $this->assignment = $assignment;

        return $this;
    }

    /**
     * Get assignment
     *
     * @return string 
     */
    public function getAssignment()
    {
        return $this->assignment;
    }

    /**
     * Set quiz
     *
     * @param string $quiz
     * @return Marksheet
     */
    public function setQuiz($quiz)
    {
        $this->quiz = $quiz;

        return $this;
    }

    /**
     * Get quiz
     *
     * @return string 
     */
    public function getQuiz()
    {
        return $this->quiz;
    }

    /**
     * Set attendance
     *
     * @param string $attendance
     * @return Marksheet
     */
    public function setAttendance($attendance)
    {
        $this->attendance = $attendance;

        return $this;
    }

    /**
     * Get attendance
     *
     * @return string 
     */
    public function getAttendance()
    {
        return $this->attendance;
    }

    /**
     * Set finalExam
     *
     * @param string $finalExam
     * @return Marksheet
     */
    public function setFinalExam($finalExam)
    {
        $this->finalExam = $finalExam;

        return $this;
    }

    /**
     * Get finalExam
     *
     * @return string 
     */
    public function getFinalExam()
    {
        return $this->finalExam;
    }

    /**
     * Set totalMarks
     *
     * @param string $totalMarks
     * @return Marksheet
     */
    public function setTotalMarks($totalMarks)
    {
        $this->totalMarks = $totalMarks;

        return $this;
    }

    /**
     * Get totalMarks
     *
     * @return string 
     */
    public function getTotalMarks()
    {
        return $this->totalMarks;
    }

    /**
     * Set grade
     *
     * @param string $grade
     * @return Marksheet
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
     * @return Marksheet
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
     * @return Marksheet
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
     * Set remarks
     *
     * @param string $remarks
     * @return Marksheet
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
     * Set teacherName
     *
     * @param string $teacherName
     * @return Marksheet
     */
    public function setTeacherName($teacherName)
    {
        $this->teacherName = $teacherName;

        return $this;
    }

    /**
     * Get teacherName
     *
     * @return string 
     */
    public function getTeacherName()
    {
        return $this->teacherName;
    }

    /**
     * Set isCheckedByDH
     *
     * @param boolean $isCheckedByDH
     * @return Marksheet
     */
    public function setIsCheckedByDH($isCheckedByDH)
    {
        $this->isCheckedByDH = $isCheckedByDH;

        return $this;
    }

    /**
     * Get isCheckedByDH
     *
     * @return boolean 
     */
    public function getIsCheckedByDH()
    {
        return $this->isCheckedByDH;
    }

    /**
     * Set isCheckedByTOM
     *
     * @param boolean $isCheckedByTOM
     * @return Marksheet
     */
    public function setIsCheckedByTOM($isCheckedByTOM)
    {
        $this->isCheckedByTOM = $isCheckedByTOM;

        return $this;
    }

    /**
     * Get isCheckedByTOM
     *
     * @return boolean 
     */
    public function getIsCheckedByTOM()
    {
        return $this->isCheckedByTOM;
    }

    /**
     * Set isCheckedBySAD
     *
     * @param boolean $isCheckedBySAD
     * @return Marksheet
     */
    public function setIsCheckedBySAD($isCheckedBySAD)
    {
        $this->isCheckedBySAD = $isCheckedBySAD;

        return $this;
    }

    /**
     * Get isCheckedBySAD
     *
     * @return boolean 
     */
    public function getIsCheckedBySAD()
    {
        return $this->isCheckedBySAD;
    }

    /**
     * Set isCheckedByHR
     *
     * @param boolean $isCheckedByHR
     * @return Marksheet
     */
    public function setIsCheckedByHR($isCheckedByHR)
    {
        $this->isCheckedByHR = $isCheckedByHR;

        return $this;
    }

    /**
     * Get isCheckedByHR
     *
     * @return boolean 
     */
    public function getIsCheckedByHR()
    {
        return $this->isCheckedByHR;
    }

    /**
     * Set isReleasedForStudent
     *
     * @param boolean $isReleasedForStudent
     * @return Marksheet
     */
    public function setIsReleasedForStudent($isReleasedForStudent)
    {
        $this->isReleasedForStudent = $isReleasedForStudent;

        return $this;
    }

    /**
     * Get isReleasedForStudent
     *
     * @return boolean 
     */
    public function getIsReleasedForStudent()
    {
        return $this->isReleasedForStudent;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return Marksheet
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
     * @return Marksheet
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
     * Set studentId
     *
     * @param integer $studentId
     * @return Marksheet
     */
    public function setStudentId($studentId)
    {
        $this->studentId = $studentId;

        return $this;
    }

    /**
     * Get studentId
     *
     * @return integer 
     */
    public function getStudentId()
    {
        return $this->studentId;
    }

    /**
     * Set teacherId
     *
     * @param integer $teacherId
     * @return Marksheet
     */
    public function setTeacherId($teacherId)
    {
        $this->teacherId = $teacherId;

        return $this;
    }

    /**
     * Get teacherId
     *
     * @return integer 
     */
    public function getTeacherId()
    {
        return $this->teacherId;
    }

    /**
     * Set courseId
     *
     * @param integer $courseId
     * @return Marksheet
     */
    public function setCourseId($courseId)
    {
        $this->courseId = $courseId;

        return $this;
    }

    /**
     * Get courseId
     *
     * @return integer 
     */
    public function getCourseId()
    {
        return $this->courseId;
    }

    /**
     * Set isRetakeEnabled
     *
     * @param boolean $isRetakeEnabled
     * @return Marksheet
     */
    public function setIsRetakeEnabled($isRetakeEnabled)
    {
        $this->isRetakeEnabled = $isRetakeEnabled;

        return $this;
    }

    /**
     * Get isRetakeEnabled
     *
     * @return boolean 
     */
    public function getIsRetakeEnabled()
    {
        return $this->isRetakeEnabled;
    }

    /**
     * Set retakeNumber
     *
     * @param integer $retakeNumber
     * @return Marksheet
     */
    public function setRetakeNumber($retakeNumber)
    {
        $this->retakeNumber = $retakeNumber;

        return $this;
    }

    /**
     * Get retakeNumber
     *
     * @return integer 
     */
    public function getRetakeNumber()
    {
        return $this->retakeNumber;
    }

    /**
     * Set quarterId
     *
     * @param integer $quarterId
     * @return Marksheet
     */
    public function setQuarterId($quarterId)
    {
        $this->quarterId = $quarterId;

        return $this;
    }

    /**
     * Get quarterId
     *
     * @return integer 
     */
    public function getQuarterId()
    {
        return $this->quarterId;
    }

    /**
     * Set batchCode
     *
     * @param string $batchCode
     * @return Marksheet
     */
    public function setBatchCode($batchCode)
    {
        $this->batchCode = $batchCode;

        return $this;
    }

    /**
     * Get batchCode
     *
     * @return string 
     */
    public function getBatchCode()
    {
        return $this->batchCode;
    }

    /**
     * Set quarterNumber
     *
     * @param integer $quarterNumber
     * @return Marksheet
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
     * Set programCode
     *
     * @param string $programCode
     * @return Marksheet
     */
    public function setProgramCode($programCode)
    {
        $this->programCode = $programCode;

        return $this;
    }

    /**
     * Get programCode
     *
     * @return string 
     */
    public function getProgramCode()
    {
        return $this->programCode;
    }
}
