<?php

namespace Wit\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use FOS\ElasticaBundle\Configuration\Search;

/**
 * Application
 *
 * @ORM\Table(name="applications")
 * @ORM\Entity(repositoryClass="Wit\ModelBundle\Entity\ApplicationRepository")
 * @Vich\Uploadable
 */
class Application
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
     * @ORM\Column(name="firstname", type="string", length=100)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="fathers_name", type="string", length=100)
     */
    private $fathersName;

    /**
     * @var string
     *
     * @ORM\Column(name="grand_fathers_name", type="string", length=100)
     */
    private $grandFathersName;

    /**
     * @var string
     *
     * @ORM\Column(name="family_name", type="string", length=100)
     */
    private $familyName;

    /**
     * @ORM\ManyToOne(targetEntity="MaritalStatus")
     * @ORM\JoinColumn(name="marital_status_id", referencedColumnName="id")
     **/
    private $maritalStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="birth_date", type="string", length=100)
     */
    private $birthDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="national_id", type="integer")
     */
    private $nationalId;

    /**
     * @ORM\ManyToOne(targetEntity="Gender")
     * @ORM\JoinColumn(name="gender_id", referencedColumnName="id")
     **/
    private $gender;

    /**
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumn(name="current_living_place_id", referencedColumnName="id")
     **/
    private $currentLivingPlace;

    /**
     * @var string
     *
     * @ORM\Column(name="current_living_place_other", type="string", length=100, nullable=true)
     */
    private $currentLivingPlaceOther;

    /**
     * @var string
     *
     * @ORM\Column(name="house_no", type="string", length=32, nullable=true)
     */
    private $houseNo;

    /**
     * @var string
     *
     * @ORM\Column(name="street_name", type="string", length=255)
     */
    private $streetName;

    /**
     * @var string
     *
     * @ORM\Column(name="pobox", type="string", length=32, nullable=true)
     */
    private $pobox;

    /**
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     **/
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     **/
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=64, nullable=true)
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", length=64)
     */
    private $mobile;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=128)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="permanent_house_no", type="string", length=32, nullable=true)
     */
    private $permanentHouseNo;

    /**
     * @var string
     *
     * @ORM\Column(name="permanent_street_name", type="string", length=255)
     */
    private $permanentStreetName;

    /**
     * @var string
     *
     * @ORM\Column(name="permanent_pobox", type="string", length=32, nullable=true)
     */
    private $permanentPobox;

    /**
     * @var string
     *
     * @ORM\Column(name="permanent_postal_code", type="string", length=32, nullable=true)
     */
    private $permanentPostalCode;

    /**
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumn(name="permanent_city_id", referencedColumnName="id")
     **/
    private $permanentCity;

    /**
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumn(name="permanent_country_id", referencedColumnName="id")
     **/
    private $permanentCountry;

    /**
     * @var string
     *
     * @ORM\Column(name="emergency1_name", type="string", length=100)
     */
    private $emergency1Name;

    /**
     * @var string
     *
     * @ORM\Column(name="emergency1_mobile", type="string", length=64)
     */
    private $emergency1Mobile;

    /**
     * @var string
     *
     * @ORM\Column(name="emergency1_phone", type="string", length=64, nullable=true)
     */
    private $emergency1Phone;

    /**
     * @var string
     *
     * @ORM\Column(name="emergency2_name", type="string", length=100)
     */
    private $emergency2Name;

    /**
     * @var string
     *
     * @ORM\Column(name="emergency2_mobile", type="string", length=64)
     */
    private $emergency2Mobile;

    /**
     * @var string
     *
     * @ORM\Column(name="emergency2_phone", type="string", length=64, nullable=true)
     */
    private $emergency2Phone;

    /**
     * @ORM\ManyToOne(targetEntity="StudyLevel")
     * @ORM\JoinColumn(name="study_level_id", referencedColumnName="id")
     **/
    private $studyLevel;

    /**
     * @var string
     *
     * @ORM\Column(name="study_level_other", type="string", length=255, nullable=true)
     */
    private $studyLevelOther;

    /**
     * @ORM\ManyToOne(targetEntity="CompletionDate")
     * @ORM\JoinColumn(name="completion_date_id", referencedColumnName="id")
     **/
    private $completionDate;

    /**
     * @ORM\ManyToOne(targetEntity="City")
     * @ORM\JoinColumn(name="completion_city_id", referencedColumnName="id")
     **/
    private $completionCity;

    /**
     * @var string
     *
     * @ORM\Column(name="completion_city_other", type="string", length=128, nullable=true)
     */
    private $completionCityOther;

    /**
     * @ORM\ManyToOne(targetEntity="Grade")
     * @ORM\JoinColumn(name="grade_id", referencedColumnName="id")
     **/
    private $grade;

    /**
     * @ORM\ManyToOne(targetEntity="AccumulatedGrade")
     * @ORM\JoinColumn(name="accumulated_grade_id", referencedColumnName="id")
     **/
    private $accumulatedGrade;

    /**
     * @ORM\ManyToOne(targetEntity="QiyasGrade")
     * @ORM\JoinColumn(name="qiyas_grade_id", referencedColumnName="id")
     **/
    private $qiyasGrade;

    /**
     * @ORM\ManyToOne(targetEntity="EnglishGrade")
     * @ORM\JoinColumn(name="english_grade_id", referencedColumnName="id")
     **/
    private $englishGrade;

    /**
     * @ORM\ManyToOne(targetEntity="Major")
     * @ORM\JoinColumn(name="major_id", referencedColumnName="id")
     **/
    private $major;

    /**
     * @var string
     *
     * @ORM\Column(name="major_other", type="string", length=255, nullable=true)
     */
    private $majorOther;

    /**
     * @var string
     *
     * @ORM\Column(name="doc_national_id", type="string", length=255, nullable=true)
     */
    private $docNationalId;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="user_app_files", fileNameProperty="docNationalId")
     * 
     * @var File $docNationalIdFile
     */
    private $docNationalIdFile;

    /**
     * @var string
     *
     * @ORM\Column(name="doc_certificate", type="string", length=255, nullable=true)
     */
    private $docCertificate;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="user_app_files", fileNameProperty="docCertificate")
     * 
     * @var File $docCertificateFile
     */
    private $docCertificateFile;

    /**
     * @var string
     *
     * @ORM\Column(name="doc_qiyas_certificate", type="string", length=255, nullable=true)
     */
    private $docQiyasCertificate;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="user_app_files", fileNameProperty="docQiyasCertificate")
     * 
     * @var File $docQiyasCertificateFile
     */
    private $docQiyasCertificateFile;

    /**
     * @var string
     *
     * @ORM\Column(name="doc_your_photo", type="string", length=255, nullable=true)
     */
    private $docYourPhoto;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="user_app_files", fileNameProperty="docYourPhoto")
     * 
     * @var File $docYourPhotoFile
     */
    private $docYourPhotoFile;
    
    /**
     * @ORM\ManyToOne(targetEntity="HearAboutUs")
     * @ORM\JoinColumn(name="hear_about_us_id", referencedColumnName="id")
     **/
    private $hearAboutUs;

    /**
     * @var string
     *
     * @ORM\Column(name="hear_about_us_other", type="string", length=255, nullable=true)
     */
    private $hearAboutUsOther;

    /**
     * @var string
     *
     * @ORM\Column(name="application_reference_no", type="string", length=32, nullable=true)
     */
    private $applicationReferenceNo;

    /**
     * @var integer
     * 
     * 0 = Application is in pending
     * 1 = Application was reviewd
     * 2 = Application was accepted
     * 3 = Application was rejected
     *
     * @ORM\Column(name="application_status", type="integer", options={"default" = 0})
     */
    private $applicationStatus;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime", nullable=true)
     */
    private $dateCreated;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_updated", type="datetime")
     */
    private $dateUpdated;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="applications")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="SET NULL")
     **/
    private $user;
    
    /**
     * @ORM\ManyToOne(targetEntity="AdmissionScheduler", inversedBy="applications")
     * @ORM\JoinColumn(name="admission_scheduler_id", referencedColumnName="id", onDelete="CASCADE")
     **/
    private $admissionScheduler;

    /**
     * @var boolean
     * 
     * This field will allow user to edit application if 1 or if 0 user cannot edit application
     * 
     * @ORM\Column(name="isEditable", type="boolean", options={"default" = 0}, nullable=true)
     */
    private $isEditable;

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
     * Set firstname
     *
     * @param string $firstname
     * @return Application
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set fathersName
     *
     * @param string $fathersName
     * @return Application
     */
    public function setFathersName($fathersName)
    {
        $this->fathersName = $fathersName;

        return $this;
    }

    /**
     * Get fathersName
     *
     * @return string 
     */
    public function getFathersName()
    {
        return $this->fathersName;
    }

    /**
     * Set grandFathersName
     *
     * @param string $grandFathersName
     * @return Application
     */
    public function setGrandFathersName($grandFathersName)
    {
        $this->grandFathersName = $grandFathersName;

        return $this;
    }

    /**
     * Get grandFathersName
     *
     * @return string 
     */
    public function getGrandFathersName()
    {
        return $this->grandFathersName;
    }

    /**
     * Set familyName
     *
     * @param string $familyName
     * @return Application
     */
    public function setFamilyName($familyName)
    {
        $this->familyName = $familyName;

        return $this;
    }

    /**
     * Get familyName
     *
     * @return string 
     */
    public function getFamilyName()
    {
        return $this->familyName;
    }

    /**
     * Set birthDate
     *
     * @param string $birthDate
     * @return Application
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return string 
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set nationalId
     *
     * @param integer $nationalId
     * @return Application
     */
    public function setNationalId($nationalId)
    {
        $this->nationalId = $nationalId;

        return $this;
    }

    /**
     * Get nationalId
     *
     * @return integer 
     */
    public function getNationalId()
    {
        return $this->nationalId;
    }

    /**
     * Set currentLivingPlaceOther
     *
     * @param string $currentLivingPlaceOther
     * @return Application
     */
    public function setCurrentLivingPlaceOther($currentLivingPlaceOther)
    {
        $this->currentLivingPlaceOther = $currentLivingPlaceOther;

        return $this;
    }

    /**
     * Get currentLivingPlaceOther
     *
     * @return string 
     */
    public function getCurrentLivingPlaceOther()
    {
        return $this->currentLivingPlaceOther;
    }

    /**
     * Set houseNo
     *
     * @param string $houseNo
     * @return Application
     */
    public function setHouseNo($houseNo)
    {
        $this->houseNo = $houseNo;

        return $this;
    }

    /**
     * Get houseNo
     *
     * @return string 
     */
    public function getHouseNo()
    {
        return $this->houseNo;
    }

    /**
     * Set streetName
     *
     * @param string $streetName
     * @return Application
     */
    public function setStreetName($streetName)
    {
        $this->streetName = $streetName;

        return $this;
    }

    /**
     * Get streetName
     *
     * @return string 
     */
    public function getStreetName()
    {
        return $this->streetName;
    }

    /**
     * Set pobox
     *
     * @param string $pobox
     * @return Application
     */
    public function setPobox($pobox)
    {
        $this->pobox = $pobox;

        return $this;
    }

    /**
     * Get pobox
     *
     * @return string 
     */
    public function getPobox()
    {
        return $this->pobox;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     * @return Application
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string 
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     * @return Application
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get mobile
     *
     * @return string 
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Application
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set permanentHouseNo
     *
     * @param string $permanentHouseNo
     * @return Application
     */
    public function setPermanentHouseNo($permanentHouseNo)
    {
        $this->permanentHouseNo = $permanentHouseNo;

        return $this;
    }

    /**
     * Get permanentHouseNo
     *
     * @return string 
     */
    public function getPermanentHouseNo()
    {
        return $this->permanentHouseNo;
    }

    /**
     * Set permanentStreetName
     *
     * @param string $permanentStreetName
     * @return Application
     */
    public function setPermanentStreetName($permanentStreetName)
    {
        $this->permanentStreetName = $permanentStreetName;

        return $this;
    }

    /**
     * Get permanentStreetName
     *
     * @return string 
     */
    public function getPermanentStreetName()
    {
        return $this->permanentStreetName;
    }

    /**
     * Set permanentPobox
     *
     * @param string $permanentPobox
     * @return Application
     */
    public function setPermanentPobox($permanentPobox)
    {
        $this->permanentPobox = $permanentPobox;

        return $this;
    }

    /**
     * Get permanentPobox
     *
     * @return string 
     */
    public function getPermanentPobox()
    {
        return $this->permanentPobox;
    }

    /**
     * Set permanentPostalCode
     *
     * @param string $permanentPostalCode
     * @return Application
     */
    public function setPermanentPostalCode($permanentPostalCode)
    {
        $this->permanentPostalCode = $permanentPostalCode;

        return $this;
    }

    /**
     * Get permanentPostalCode
     *
     * @return string 
     */
    public function getPermanentPostalCode()
    {
        return $this->permanentPostalCode;
    }

    /**
     * Set emergency1Name
     *
     * @param string $emergency1Name
     * @return Application
     */
    public function setEmergency1Name($emergency1Name)
    {
        $this->emergency1Name = $emergency1Name;

        return $this;
    }

    /**
     * Get emergency1Name
     *
     * @return string 
     */
    public function getEmergency1Name()
    {
        return $this->emergency1Name;
    }

    /**
     * Set emergency1Mobile
     *
     * @param string $emergency1Mobile
     * @return Application
     */
    public function setEmergency1Mobile($emergency1Mobile)
    {
        $this->emergency1Mobile = $emergency1Mobile;

        return $this;
    }

    /**
     * Get emergency1Mobile
     *
     * @return string 
     */
    public function getEmergency1Mobile()
    {
        return $this->emergency1Mobile;
    }

    /**
     * Set emergency1Phone
     *
     * @param string $emergency1Phone
     * @return Application
     */
    public function setEmergency1Phone($emergency1Phone)
    {
        $this->emergency1Phone = $emergency1Phone;

        return $this;
    }

    /**
     * Get emergency1Phone
     *
     * @return string 
     */
    public function getEmergency1Phone()
    {
        return $this->emergency1Phone;
    }

    /**
     * Set emergency2Name
     *
     * @param string $emergency2Name
     * @return Application
     */
    public function setEmergency2Name($emergency2Name)
    {
        $this->emergency2Name = $emergency2Name;

        return $this;
    }

    /**
     * Get emergency2Name
     *
     * @return string 
     */
    public function getEmergency2Name()
    {
        return $this->emergency2Name;
    }

    /**
     * Set emergency2Mobile
     *
     * @param string $emergency2Mobile
     * @return Application
     */
    public function setEmergency2Mobile($emergency2Mobile)
    {
        $this->emergency2Mobile = $emergency2Mobile;

        return $this;
    }

    /**
     * Get emergency2Mobile
     *
     * @return string 
     */
    public function getEmergency2Mobile()
    {
        return $this->emergency2Mobile;
    }

    /**
     * Set emergency2Phone
     *
     * @param string $emergency2Phone
     * @return Application
     */
    public function setEmergency2Phone($emergency2Phone)
    {
        $this->emergency2Phone = $emergency2Phone;

        return $this;
    }

    /**
     * Get emergency2Phone
     *
     * @return string 
     */
    public function getEmergency2Phone()
    {
        return $this->emergency2Phone;
    }

    /**
     * Set completionCityOther
     *
     * @param string $completionCityOther
     * @return Application
     */
    public function setCompletionCityOther($completionCityOther)
    {
        $this->completionCityOther = $completionCityOther;

        return $this;
    }

    /**
     * Get completionCityOther
     *
     * @return string 
     */
    public function getCompletionCityOther()
    {
        return $this->completionCityOther;
    }

    /**
     * Set majorOther
     *
     * @param string $majorOther
     * @return Application
     */
    public function setMajorOther($majorOther)
    {
        $this->majorOther = $majorOther;

        return $this;
    }

    /**
     * Get majorOther
     *
     * @return string 
     */
    public function getMajorOther()
    {
        return $this->majorOther;
    }

    /**
     * Set docNationalId
     *
     * @param string $docNationalId
     * @return Application
     */
    public function setDocNationalId($docNationalId)
    {
        $this->docNationalId = $docNationalId;

        return $this;
    }

    /**
     * Get docNationalId
     *
     * @return string 
     */
    public function getDocNationalId()
    {
        return $this->docNationalId;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file
     */
    public function setDocNationalIdFile(File $file = null)
    {
        $this->docNationalIdFile = $file;

        if ($file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->dateUpdated = new \DateTime();
        }
    }

    /**
     * @return File
     */
    public function getDocNationalIdFile()
    {
        return $this->docNationalIdFile;
    }

    /**
     * Set docCertificate
     *
     * @param string $docCertificate
     * @return Application
     */
    public function setDocCertificate($docCertificate)
    {
        $this->docCertificate = $docCertificate;

        return $this;
    }

    /**
     * Get docCertificate
     *
     * @return string 
     */
    public function getDocCertificate()
    {
        return $this->docCertificate;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file
     */
    public function setDocCertificateFile(File $file = null)
    {
        $this->docCertificateFile = $file;

        if ($file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->dateUpdated = new \DateTime();
        }
    }

    /**
     * @return File
     */
    public function getDocCertificateFile()
    {
        return $this->docCertificateFile;
    }

    /**
     * Set docQiyasCertificate
     *
     * @param string $docQiyasCertificate
     * @return Application
     */
    public function setDocQiyasCertificate($docQiyasCertificate)
    {
        $this->docQiyasCertificate = $docQiyasCertificate;

        return $this;
    }

    /**
     * Get docQiyasCertificate
     *
     * @return string 
     */
    public function getDocQiyasCertificate()
    {
        return $this->docQiyasCertificate;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file
     */
    public function setDocQiyasCertificateFile(File $file = null)
    {
        $this->docQiyasCertificateFile = $file;

        if ($file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->dateUpdated = new \DateTime();
        }
    }

    /**
     * @return File
     */
    public function getDocQiyasCertificateFile()
    {
        return $this->docQiyasCertificateFile;
    }

    /**
     * Set docYourPhoto
     *
     * @param string $docYourPhoto
     * @return Application
     */
    public function setDocYourPhoto($docYourPhoto)
    {
        $this->docYourPhoto = $docYourPhoto;

        return $this;
    }

    /**
     * Get docYourPhoto
     *
     * @return string 
     */
    public function getDocYourPhoto()
    {
        return $this->docYourPhoto;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file
     */
    public function setDocYourPhotoFile(File $file = null)
    {
        $this->docYourPhotoFile = $file;

        if ($file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->dateUpdated = new \DateTime();
        }
    }

    /**
     * @return File
     */
    public function getDocYourPhotoFile()
    {
        return $this->docYourPhotoFile;
    }

    /**
     * Set country
     *
     * @param \Wit\ModelBundle\Entity\Country $country
     * @return Application
     */
    public function setCountry(\Wit\ModelBundle\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \Wit\ModelBundle\Entity\Country 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set permanentCountry
     *
     * @param \Wit\ModelBundle\Entity\Country $permanentCountry
     * @return Application
     */
    public function setPermanentCountry(\Wit\ModelBundle\Entity\Country $permanentCountry = null)
    {
        $this->permanentCountry = $permanentCountry;

        return $this;
    }

    /**
     * Get permanentCountry
     *
     * @return \Wit\ModelBundle\Entity\Country 
     */
    public function getPermanentCountry()
    {
        return $this->permanentCountry;
    }

    /**
     * Set maritalStatus
     *
     * @param \Wit\ModelBundle\Entity\MaritalStatus $maritalStatus
     * @return Application
     */
    public function setMaritalStatus(\Wit\ModelBundle\Entity\MaritalStatus $maritalStatus = null)
    {
        $this->maritalStatus = $maritalStatus;

        return $this;
    }

    /**
     * Get maritalStatus
     *
     * @return \Wit\ModelBundle\Entity\MaritalStatus 
     */
    public function getMaritalStatus()
    {
        return $this->maritalStatus;
    }

    /**
     * Set currentLivingPlace
     *
     * @param \Wit\ModelBundle\Entity\City $currentLivingPlace
     * @return Application
     */
    public function setCurrentLivingPlace(\Wit\ModelBundle\Entity\City $currentLivingPlace = null)
    {
        $this->currentLivingPlace = $currentLivingPlace;

        return $this;
    }

    /**
     * Get currentLivingPlace
     *
     * @return \Wit\ModelBundle\Entity\City 
     */
    public function getCurrentLivingPlace()
    {
        return $this->currentLivingPlace;
    }

    /**
     * Set city
     *
     * @param \Wit\ModelBundle\Entity\City $city
     * @return Application
     */
    public function setCity(\Wit\ModelBundle\Entity\City $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return \Wit\ModelBundle\Entity\City 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set permanentCity
     *
     * @param \Wit\ModelBundle\Entity\City $permanentCity
     * @return Application
     */
    public function setPermanentCity(\Wit\ModelBundle\Entity\City $permanentCity = null)
    {
        $this->permanentCity = $permanentCity;

        return $this;
    }

    /**
     * Get permanentCity
     *
     * @return \Wit\ModelBundle\Entity\City 
     */
    public function getPermanentCity()
    {
        return $this->permanentCity;
    }

    /**
     * Set completionCity
     *
     * @param \Wit\ModelBundle\Entity\City $completionCity
     * @return Application
     */
    public function setCompletionCity(\Wit\ModelBundle\Entity\City $completionCity = null)
    {
        $this->completionCity = $completionCity;

        return $this;
    }

    /**
     * Get completionCity
     *
     * @return \Wit\ModelBundle\Entity\City 
     */
    public function getCompletionCity()
    {
        return $this->completionCity;
    }

    /**
     * Set gender
     *
     * @param \Wit\ModelBundle\Entity\Gender $gender
     * @return Application
     */
    public function setGender(\Wit\ModelBundle\Entity\Gender $gender = null)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return \Wit\ModelBundle\Entity\Gender 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set studyLevel
     *
     * @param \Wit\ModelBundle\Entity\StudyLevel $studyLevel
     * @return Application
     */
    public function setStudyLevel(\Wit\ModelBundle\Entity\StudyLevel $studyLevel = null)
    {
        $this->studyLevel = $studyLevel;

        return $this;
    }

    /**
     * Get studyLevel
     *
     * @return \Wit\ModelBundle\Entity\StudyLevel 
     */
    public function getStudyLevel()
    {
        return $this->studyLevel;
    }

    /**
     * Set completionDate
     *
     * @param \Wit\ModelBundle\Entity\CompletionDate $completionDate
     * @return Application
     */
    public function setCompletionDate(\Wit\ModelBundle\Entity\CompletionDate $completionDate = null)
    {
        $this->completionDate = $completionDate;

        return $this;
    }

    /**
     * Get completionDate
     *
     * @return \Wit\ModelBundle\Entity\CompletionDate 
     */
    public function getCompletionDate()
    {
        return $this->completionDate;
    }

    /**
     * Set grade
     *
     * @param \Wit\ModelBundle\Entity\Grade $grade
     * @return Application
     */
    public function setGrade(\Wit\ModelBundle\Entity\Grade $grade = null)
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * Get grade
     *
     * @return \Wit\ModelBundle\Entity\Grade 
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * Set accumulatedGrade
     *
     * @param \Wit\ModelBundle\Entity\AccumulatedGrade $accumulatedGrade
     * @return Application
     */
    public function setAccumulatedGrade(\Wit\ModelBundle\Entity\AccumulatedGrade $accumulatedGrade = null)
    {
        $this->accumulatedGrade = $accumulatedGrade;

        return $this;
    }

    /**
     * Get accumulatedGrade
     *
     * @return \Wit\ModelBundle\Entity\AccumulatedGrade 
     */
    public function getAccumulatedGrade()
    {
        return $this->accumulatedGrade;
    }

    /**
     * Set qiyasGrade
     *
     * @param \Wit\ModelBundle\Entity\QiyasGrade $qiyasGrade
     * @return Application
     */
    public function setQiyasGrade(\Wit\ModelBundle\Entity\QiyasGrade $qiyasGrade = null)
    {
        $this->qiyasGrade = $qiyasGrade;

        return $this;
    }

    /**
     * Get qiyasGrade
     *
     * @return \Wit\ModelBundle\Entity\QiyasGrade 
     */
    public function getQiyasGrade()
    {
        return $this->qiyasGrade;
    }

    /**
     * Set englishGrade
     *
     * @param \Wit\ModelBundle\Entity\EnglishGrade $englishGrade
     * @return Application
     */
    public function setEnglishGrade(\Wit\ModelBundle\Entity\EnglishGrade $englishGrade = null)
    {
        $this->englishGrade = $englishGrade;

        return $this;
    }

    /**
     * Get englishGrade
     *
     * @return \Wit\ModelBundle\Entity\EnglishGrade 
     */
    public function getEnglishGrade()
    {
        return $this->englishGrade;
    }

    /**
     * Set major
     *
     * @param \Wit\ModelBundle\Entity\Major $major
     * @return Application
     */
    public function setMajor(\Wit\ModelBundle\Entity\Major $major = null)
    {
        $this->major = $major;

        return $this;
    }

    /**
     * Get major
     *
     * @return \Wit\ModelBundle\Entity\Major 
     */
    public function getMajor()
    {
        return $this->major;
    }

    /**
     * Set hearAboutUs
     *
     * @param \Wit\ModelBundle\Entity\HearAboutUs $hearAboutUs
     * @return Application
     */
    public function setHearAboutUs(\Wit\ModelBundle\Entity\HearAboutUs $hearAboutUs = null)
    {
        $this->hearAboutUs = $hearAboutUs;

        return $this;
    }

    /**
     * Get hearAboutUs
     *
     * @return \Wit\ModelBundle\Entity\HearAboutUs 
     */
    public function getHearAboutUs()
    {
        return $this->hearAboutUs;
    }

    /**
     * Set applicationReferenceNo
     *
     * @param string $applicationReferenceNo
     * @return Application
     */
    public function setApplicationReferenceNo($applicationReferenceNo)
    {
        $this->applicationReferenceNo = $applicationReferenceNo;

        return $this;
    }

    /**
     * Get applicationReferenceNo
     *
     * @return string 
     */
    public function getApplicationReferenceNo()
    {
        return $this->applicationReferenceNo;
    }

    /**
     * Set applicationStatus
     *
     * @param integer $applicationStatus
     * @return Application
     */
    public function setApplicationStatus($applicationStatus)
    {
        $this->applicationStatus = $applicationStatus;

        return $this;
    }

    /**
     * Get applicationStatus
     *
     * @return integer 
     */
    public function getApplicationStatus()
    {
        return $this->applicationStatus;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return Application
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
     * @return Application
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
     * Set admissionScheduler
     *
     * @param \Wit\ModelBundle\Entity\AdmissionScheduler $admissionScheduler
     * @return Application
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

    /**
     * Set isEditable
     *
     * @param boolean $isEditable
     * @return Application
     */
    public function setIsEditable($isEditable)
    {
        $this->isEditable = $isEditable;

        return $this;
    }

    /**
     * Get isEditable
     *
     * @return boolean 
     */
    public function getIsEditable()
    {
        return $this->isEditable;
    }

    /**
     * Set user
     *
     * @param \Wit\ModelBundle\Entity\User $user
     * @return Application
     */
    public function setUser(\Wit\ModelBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Wit\ModelBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set studyLevelOther
     *
     * @param string $studyLevelOther
     * @return Application
     */
    public function setStudyLevelOther($studyLevelOther)
    {
        $this->studyLevelOther = $studyLevelOther;

        return $this;
    }

    /**
     * Get studyLevelOther
     *
     * @return string 
     */
    public function getStudyLevelOther()
    {
        return $this->studyLevelOther;
    }

    /**
     * Set hearAboutUsOther
     *
     * @param string $hearAboutUsOther
     * @return Application
     */
    public function setHearAboutUsOther($hearAboutUsOther)
    {
        $this->hearAboutUsOther = $hearAboutUsOther;

        return $this;
    }

    /**
     * Get hearAboutUsOther
     *
     * @return string 
     */
    public function getHearAboutUsOther()
    {
        return $this->hearAboutUsOther;
    }
}
