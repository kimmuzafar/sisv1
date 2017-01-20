<?php

namespace Wit\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QuickNews
 *
 * @ORM\Table(name="quick_news")
 * @ORM\Entity
 */
class QuickNews
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="fromUserName", type="string", length=255)
     */
    private $fromUserName;

    /**
     * @var string
     *
     * it's called toUserRole, because there can be many users who will have same role..
     *
     * @ORM\Column(name="toUserRole", type="string", length=64)
     */
    private $toUserRole;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime")
     */
    private $dateCreated;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_read", type="boolean")
     */
    private $isRead;

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
     * @return QuickNews
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
     * Set description
     *
     * @param string $description
     * @return QuickNews
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
     * Set fromUserName
     *
     * @param string $fromUserName
     * @return QuickNews
     */
    public function setFromUserName($fromUserName)
    {
        $this->fromUserName = $fromUserName;

        return $this;
    }

    /**
     * Get fromUserName
     *
     * @return string 
     */
    public function getFromUserName()
    {
        return $this->fromUserName;
    }
    
    /**
     * Set toUserRole
     *
     * @param string $toUserRole
     * @return QuickNews
     */
    public function setToUserRole($toUserRole)
    {
        $this->toUserRole = $toUserRole;

        return $this;
    }

    /**
     * Get toUserRole
     *
     * @return string 
     */
    public function getToUserRole()
    {
        return $this->toUserRole;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return QuickNews
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
     * Set isRead
     *
     * @param boolean $isRead
     * @return QuickNews
     */
    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;

        return $this;
    }

    /**
     * Get isRead
     *
     * @return boolean 
     */
    public function getIsRead()
    {
        return $this->isRead;
    }
}
