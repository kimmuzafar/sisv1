<?php

namespace Wit\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table(name="messages")
 * @ORM\Entity(repositoryClass="Wit\ModelBundle\Entity\MessageRepository")
 */
class Message
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
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="fromUser", referencedColumnName="id")
     **/
    private $fromUser;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="toUser", referencedColumnName="id")
     **/
    private $toUser;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     */
    private $body;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_deleted_by_fromUser", type="boolean", options={"default" = 0})
     */
    private $isDeletedByFromUser;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_deleted_by_toUser", type="boolean", options={"default" = 0})
     */
    private $isDeletedByToUser;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_read", type="boolean", options={"default" = 0})
     */
    private $isRead;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime")
     */
    private $dateCreated;

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
     * Set fromUser
     *
     * @param \Wit\ModelBundle\Entity\User $fromUser
     * @return Message
     */
    public function setFromUser(\Wit\ModelBundle\Entity\User $fromUser = null)
    {
        $this->fromUser = $fromUser;

        return $this;
    }

    /**
     * Get fromUser
     *
     * @return \Wit\ModelBundle\Entity\User 
     */
    public function getFromUser()
    {
        return $this->fromUser;
    }

    /**
     * Set toUser
     *
     * @param \Wit\ModelBundle\Entity\User $toUser
     * @return Message
     */
    public function setToUser(\Wit\ModelBundle\Entity\User $toUser = null)
    {
        $this->toUser = $toUser;

        return $this;
    }

    /**
     * Get toUser
     *
     * @return \Wit\ModelBundle\Entity\User 
     */
    public function getToUser()
    {
        return $this->toUser;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return Message
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string 
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return Message
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set isDeletedByFromUser
     *
     * @param boolean $isDeletedByFromUser
     * @return Message
     */
    public function setIsDeletedByFromUser($isDeletedByFromUser)
    {
        $this->isDeletedByFromUser = $isDeletedByFromUser;

        return $this;
    }

    /**
     * Get isDeletedByFromUser
     *
     * @return boolean 
     */
    public function getIsDeletedByFromUser()
    {
        return $this->isDeletedByFromUser;
    }

    /**
     * Set isDeletedByToUser
     *
     * @param boolean $isDeletedByToUser
     * @return Message
     */
    public function setIsDeletedByToUser($isDeletedByToUser)
    {
        $this->isDeletedByToUser = $isDeletedByToUser;

        return $this;
    }

    /**
     * Get isDeletedByToUser
     *
     * @return boolean 
     */
    public function getIsDeletedByToUser()
    {
        return $this->isDeletedByToUser;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return Message
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
     * @return Message
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
