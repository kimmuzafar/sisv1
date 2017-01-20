<?php

namespace Wit\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Transcript
 *
 * @ORM\Table(name="transcripts")
 * @ORM\Entity
 */
class Transcript
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
     * Student Name in English
     *
     * @var string
     *
     * @ORM\Column(name="name_en", type="string", length=255, nullable=true)
     */
    private $nameEnglish;

    /**
     *
     * Student Name in Arabic
     *
     * @var string
     *
     * @ORM\Column(name="name_ar", type="string", length=255, nullable=true)
     */
    private $nameArabic;

    /**
     *
     * trainee id or wit id
     * this is must as records to be fetched using based on this in future
     *
     * @var string
     *
     * @ORM\Column(name="wit_id", type="string", length=50, nullable=true)
     */
    private $witId;

    /**
     *
     * Date of joining
     *
     * @var string
     *
     * @ORM\Column(name="joining_date", type="string", length=255, nullable=true)
     */
    private $joiningDate;




    /**
     *
     * quarter title
     *
     * @var string
     *
     * @ORM\Column(name="quarter", type="string", length=128, nullable=true)
     */
    private $quarter;

    /**
     *
     * Batch Title
     *
     * @var string
     *
     * @ORM\Column(name="batch", type="string", length=128, nullable=true)
     */
    private $batch;    

    /**
     *
     * Program Title
     *
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
     * total credit hours in whole quarter
     * total of available credit hours in each subject in whole quarter
     * 
     *
     * @var decimal
     *
     * @ORM\Column(name="total_credit_hours", type="decimal", scale=2, nullable=true)
     */
    private $totalCreditHours;

    /**
     *
     * total points in whole quarter
     * total of all earned subjectTotalPoints in whole quarter
     * 
     *
     * @var decimal
     *
     * @ORM\Column(name="total_points", type="decimal", scale=2, nullable=true)
     */
    private $totalPoints;





    /**
     *
     * GPA for current quarter
     * 
     * $totalPoints / $totalCreditHours = GPA for current quarter
     *
     * @var decimal
     *
     * @ORM\Column(name="gpa", type="decimal", scale=2, nullable=true)
     */
    private $gpa;





    /**
     *
     * AGPA based on all previous quarters performance, first quarter cannot have AGPA
     *
     * $totalPoints of all previous quarters / $totalCreditHours of all previous quarters = AGPA for current quarter
     *
     * @var decimal
     *
     * @ORM\Column(name="agpa", type="decimal", scale=2, nullable=true)
     */
    private $agpa;





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
     * @ORM\OneToMany(targetEntity="TranscriptDetail", mappedBy="transcript")
     */
    private $transcriptDetails;



    
    public function __construct()
    {
        $this->dateUpdated = new \DateTime();

        $this->transcriptDetails = new ArrayCollection();
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
     * Set nameEnglish
     *
     * @param string $nameEnglish
     * @return Transcript
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
     * @return Transcript
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
     * Set witId
     *
     * @param string $witId
     * @return Transcript
     */
    public function setWitId($witId)
    {
        $this->witId = $witId;

        return $this;
    }

    /**
     * Get witId
     *
     * @return string 
     */
    public function getWitId()
    {
        return $this->witId;
    }

    /**
     * Set joiningDate
     *
     * @param string $joiningDate
     * @return Transcript
     */
    public function setJoiningDate($joiningDate)
    {
        $this->joiningDate = $joiningDate;

        return $this;
    }

    /**
     * Get joiningDate
     *
     * @return string 
     */
    public function getJoiningDate()
    {
        return $this->joiningDate;
    }

    /**
     * Set quarter
     *
     * @param string $quarter
     * @return Transcript
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
     * Set batch
     *
     * @param string $batch
     * @return Transcript
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
     * Set program
     *
     * @param string $program
     * @return Transcript
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
     * Set totalCreditHours
     *
     * @param string $totalCreditHours
     * @return Transcript
     */
    public function setTotalCreditHours($totalCreditHours)
    {
        $this->totalCreditHours = $totalCreditHours;

        return $this;
    }

    /**
     * Get totalCreditHours
     *
     * @return string 
     */
    public function getTotalCreditHours()
    {
        return $this->totalCreditHours;
    }

    /**
     * Set totalPoints
     *
     * @param string $totalPoints
     * @return Transcript
     */
    public function setTotalPoints($totalPoints)
    {
        $this->totalPoints = $totalPoints;

        return $this;
    }

    /**
     * Get totalPoints
     *
     * @return string 
     */
    public function getTotalPoints()
    {
        return $this->totalPoints;
    }

    /**
     * Set gpa
     *
     * @param string $gpa
     * @return Transcript
     */
    public function setGpa($gpa)
    {
        $this->gpa = $gpa;

        return $this;
    }

    /**
     * Get gpa
     *
     * @return string 
     */
    public function getGpa()
    {
        return $this->gpa;
    }

    /**
     * Set agpa
     *
     * @param string $agpa
     * @return Transcript
     */
    public function setAgpa($agpa)
    {
        $this->agpa = $agpa;

        return $this;
    }

    /**
     * Get agpa
     *
     * @return string 
     */
    public function getAgpa()
    {
        return $this->agpa;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return Transcript
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
     * @return Transcript
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
     * Add transcriptDetails
     *
     * @param \Wit\ModelBundle\Entity\TranscriptDetail $transcriptDetails
     * @return Transcript
     */
    public function addTranscriptDetail(\Wit\ModelBundle\Entity\TranscriptDetail $transcriptDetails)
    {
        $this->transcriptDetails[] = $transcriptDetails;

        return $this;
    }

    /**
     * Remove transcriptDetails
     *
     * @param \Wit\ModelBundle\Entity\TranscriptDetail $transcriptDetails
     */
    public function removeTranscriptDetail(\Wit\ModelBundle\Entity\TranscriptDetail $transcriptDetails)
    {
        $this->transcriptDetails->removeElement($transcriptDetails);
    }

    /**
     * Get transcriptDetails
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTranscriptDetails()
    {
        return $this->transcriptDetails;
    }
    

    /**
     * Set batchCode
     *
     * @param string $batchCode
     * @return Transcript
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
     * @return Transcript
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
     * @return Transcript
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
