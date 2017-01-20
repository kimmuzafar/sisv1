<?php

namespace Wit\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use Wit\ModelBundle\Entity\User;

/**
 * User
 * 
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Wit\ModelBundle\Entity\UserRepository")
 * @Vich\Uploadable
 */
class User implements AdvancedUserInterface, \Serializable
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
     * @ORM\Column(name="firstname", type="string", length=50)
     * @Assert\NotBlank()
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=50)
     * @Assert\NotBlank()
     */
    private $lastname;


    /**
     * @var string
     *
     * @ORM\Column(name="fullname_english", type="string", length=50, nullable=true)
     */
    private $fullname_english;

    /**
     * @var string
     *
     * @ORM\Column(name="fullname_arabic", type="string", length=50, nullable=true)
     */
    private $fullname_arabic;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=50, unique=true)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=32)
     */
    private $salt;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=40)
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, unique=true)
     * @Assert\NotBlank()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="nationalid", type="string", length=50, nullable=true)
     */
    private $nationalid;

    /**
     * @var string
     *
     * @ORM\Column(name="traineeid", type="string", length=50, nullable=true)
     */
    private $traineeid;


    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", length=32, nullable=true)
     */
    private $mobile;

    /**
     * @ORM\ManyToOne(targetEntity="Gender")
     * @ORM\JoinColumn(name="gender_id", referencedColumnName="id")
     **/
    private $gender;

    /**
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     **/
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="doc_your_photo", type="string", length=255, nullable=true)
     */
    private $docYourPhoto;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="user_profile_files", fileNameProperty="docYourPhoto")
     * 
     * @var File $docYourPhotoFile
     */
    private $docYourPhotoFile;




    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
     *
     */
    private $roles;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_joined", type="datetime", nullable=true)
     */
    private $dateJoined;

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
     * @ORM\OneToMany(targetEntity="Application", mappedBy="user")
     **/
    private $applications;
    
    public function __construct()
    {
        $this->isActive = true;
        $this->salt = md5(uniqid(null, true));

        $this->roles = new ArrayCollection();

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
     * Set firstname
     *
     * @param string $firstname
     * @return User
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
     * Set lastname
     *
     * @param string $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
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
     * Set nationalid
     *
     * @param string $nationalid
     * @return User
     */
    public function setNationalid($nationalid)
    {
        $this->nationalid = $nationalid;

        return $this;
    }

    /**
     * Get nationalid
     *
     * @return string 
     */
    public function getNationalid()
    {
        return $this->nationalid;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return User
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
     * set roles
     *
     * @param \Wit\ModelBundle\Entity\Role $roles
     * @return User
     */
    public function setRoles(\Wit\ModelBundle\Entity\Role $roles)
    {
        $this->roles[] = $roles;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return $this->roles->toArray();
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return User
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
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            ) = unserialize($serialized);
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }




    /**
     * Add roles
     *
     * @param \Wit\ModelBundle\Entity\Role $roles
     * @return User
     */
    public function addRole(\Wit\ModelBundle\Entity\Role $roles)
    {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \Wit\ModelBundle\Entity\Role $roles
     */
    public function removeRole(\Wit\ModelBundle\Entity\Role $roles)
    {
        $this->roles->removeElement($roles);
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     * @return User
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
     * Set gender
     *
     * @param \Wit\ModelBundle\Entity\Gender $gender
     * @return User
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
     * Set country
     *
     * @param \Wit\ModelBundle\Entity\Country $country
     * @return User
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
     * Set docYourPhoto
     *
     * @param string $docYourPhoto
     * @return User
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
     * Add applications
     *
     * @param \Wit\ModelBundle\Entity\Application $applications
     * @return User
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
     * Set fullname_english
     *
     * @param string $fullnameEnglish
     * @return User
     */
    public function setFullnameEnglish($fullnameEnglish)
    {
        $this->fullname_english = $fullnameEnglish;

        return $this;
    }

    /**
     * Get fullname_english
     *
     * @return string 
     */
    public function getFullnameEnglish()
    {
        return $this->fullname_english;
    }

    /**
     * Set fullname_arabic
     *
     * @param string $fullnameArabic
     * @return User
     */
    public function setFullnameArabic($fullnameArabic)
    {
        $this->fullname_arabic = $fullnameArabic;

        return $this;
    }

    /**
     * Get fullname_arabic
     *
     * @return string 
     */
    public function getFullnameArabic()
    {
        return $this->fullname_arabic;
    }

    /**
     * Set traineeid
     *
     * @param string $traineeid
     * @return User
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
     * Set dateJoined
     *
     * @param \DateTime $dateJoined
     * @return User
     */
    public function setDateJoined($dateJoined)
    {
        $this->dateJoined = $dateJoined;

        return $this;
    }

    /**
     * Get dateJoined
     *
     * @return \DateTime 
     */
    public function getDateJoined()
    {
        return $this->dateJoined;
    }
}
