<?php

namespace Wit\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * UserLogs
 * 
 * @ORM\Table(name="user_logs")
 * @ORM\Entity(repositoryClass="Wit\ModelBundle\Entity\UserLogsRepository")
 */
class UserLogs
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
     * This detail field will hold log details ie: what was done/happened..
     *
     * @ORM\Column(name="detail", type="text")
     */
    private $detail;

    /**
     * @var string
     * 
     * This field will only display name of log generator it could be 
     * i-e: admin, system, tod, sad, automatic
     * 
     * @ORM\Column(name="generator", type="string", length=128)
     */
    private $generator;

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
     * @var boolean
     * 
     * This field will check if this log/notification was removed by user
     * 0 = removed
     * 1 = active not been removed by user
     * 
     * @ORM\Column(name="is_active", type="boolean", options={"default" = 1})
     */
    private $isActive;

    /**
     * @var boolean
     * 
     * This field will check if this log/notification should display USER ROLE TOD
     * 
     * @ORM\Column(name="is_for_tod", type="boolean", options={"default" = 0}, nullable=true)
     */
    private $isForTod;

    /**
     * @var boolean
     * 
     * This field will check if this log/notification should display USER ROLE DH
     * 
     * @ORM\Column(name="is_for_dh", type="boolean", options={"default" = 0}, nullable=true)
     */
    private $isForDh;

    /**
     * @var boolean
     * 
     * This field will check if this log/notification should display USER ROLE SAD
     * 
     * @ORM\Column(name="is_for_sad", type="boolean", options={"default" = 0}, nullable=true)
     */
    private $isForSad;

    /**
     * @var boolean
     * 
     * This field will check if this log/notification should display USER ROLE TEACHER
     * 
     * @ORM\Column(name="is_for_teacher", type="boolean", options={"default" = 0}, nullable=true)
     */
    private $isForTeacher;

    /**
     * @var boolean
     * 
     * This field will check if this log/notification should display USER ROLE STUDENT
     * 
     * @ORM\Column(name="is_for_student", type="boolean", options={"default" = 0}, nullable=true)
     */
    private $isForStudent;

    /**
     * @var boolean
     * 
     * This field will check if this log/notification should display USER ROLE USER
     * So if any notification was marked as 1 in this field will display to all users
     * 
     * @ORM\Column(name="is_for_user", type="boolean", options={"default" = 0}, nullable=true)
     */
    private $isForUser;

    /* All notification will display to USER ROLE ADMIN so no need to create perticular flag for that */

    /**
     * @var integer
     * 
     * This field is specific to USER ROLE type users
     * Once they created an account/ submitted an application, notification should be shown to them individually
     * 
     * @ORM\Column(name="user_role_user_id", type="integer", options={"default" = 0}, nullable=true)
     */
    private $userRoleUserId;

    /**
     * @var integer
     * 
     * This field will have user id who generated this notification
     * 
     * @ORM\Column(name="from_user_id", type="integer", nullable=true)
     */
    private $fromUserId;

    /**
     * @var integer
     * 
     * This field will have user id to whom this notification should display
     * 
     * @ORM\Column(name="for_user_id", type="integer", nullable=true)
     */
    private $forUserId;

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
     * Set detail
     *
     * @param string $detail
     * @return UserLogs
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
     * Set generator
     *
     * @param string $generator
     * @return UserLogs
     */
    public function setGenerator($generator)
    {
        $this->generator = $generator;

        return $this;
    }

    /**
     * Get generator
     *
     * @return string 
     */
    public function getGenerator()
    {
        return $this->generator;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return UserLogs
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
     * @return User
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
     * Set isActive
     *
     * @param boolean $isActive
     * @return UserLogs
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set isForTod
     *
     * @param boolean $isForTod
     * @return UserLogs
     */
    public function setIsForTod($isForTod)
    {
        $this->isForTod = $isForTod;

        return $this;
    }

    /**
     * Get isForTod
     *
     * @return boolean 
     */
    public function getIsForTod()
    {
        return $this->isForTod;
    }

    /**
     * Set isForSad
     *
     * @param boolean $isForSad
     * @return UserLogs
     */
    public function setIsForSad($isForSad)
    {
        $this->isForSad = $isForSad;

        return $this;
    }

    /**
     * Get isForSad
     *
     * @return boolean 
     */
    public function getIsForSad()
    {
        return $this->isForSad;
    }

    /**
     * Set isForUser
     *
     * @param boolean $isForUser
     * @return UserLogs
     */
    public function setIsForUser($isForUser)
    {
        $this->isForUser = $isForUser;

        return $this;
    }

    /**
     * Get isForUser
     *
     * @return boolean 
     */
    public function getIsForUser()
    {
        return $this->isForUser;
    }

    /**
     * Set isForDh
     *
     * @param boolean $isForDh
     * @return UserLogs
     */
    public function setIsForDh($isForDh)
    {
        $this->isForDh = $isForDh;

        return $this;
    }

    /**
     * Get isForDh
     *
     * @return boolean 
     */
    public function getIsForDh()
    {
        return $this->isForDh;
    }

    /**
     * Set isForTeacher
     *
     * @param boolean $isForTeacher
     * @return UserLogs
     */
    public function setIsForTeacher($isForTeacher)
    {
        $this->isForTeacher = $isForTeacher;

        return $this;
    }

    /**
     * Get isForTeacher
     *
     * @return boolean 
     */
    public function getIsForTeacher()
    {
        return $this->isForTeacher;
    }

    /**
     * Set isForStudent
     *
     * @param boolean $isForStudent
     * @return UserLogs
     */
    public function setIsForStudent($isForStudent)
    {
        $this->isForStudent = $isForStudent;

        return $this;
    }

    /**
     * Get isForStudent
     *
     * @return boolean 
     */
    public function getIsForStudent()
    {
        return $this->isForStudent;
    }

    /**
     * Set userRoleUserId
     *
     * @param integer $userRoleUserId
     * @return UserLogs
     */
    public function setUserRoleUserId($userRoleUserId)
    {
        $this->userRoleUserId = $userRoleUserId;

        return $this;
    }

    /**
     * Get userRoleUserId
     *
     * @return integer 
     */
    public function getUserRoleUserId()
    {
        return $this->userRoleUserId;
    }

    /**
     * Set fromUserId
     *
     * @param integer $fromUserId
     * @return UserLogs
     */
    public function setFromUserId($fromUserId)
    {
        $this->fromUserId = $fromUserId;

        return $this;
    }

    /**
     * Get fromUserId
     *
     * @return integer 
     */
    public function getFromUserId()
    {
        return $this->fromUserId;
    }

    /**
     * Set forUserId
     *
     * @param integer $forUserId
     * @return UserLogs
     */
    public function setForUserId($forUserId)
    {
        $this->forUserId = $forUserId;

        return $this;
    }

    /**
     * Get forUserId
     *
     * @return integer 
     */
    public function getForUserId()
    {
        return $this->forUserId;
    }
}
