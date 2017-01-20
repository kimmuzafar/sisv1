<?php

namespace Wit\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * UserGroup
 *
 * @ORM\Table(name="user_groups")
 * @ORM\Entity
 */
class UserGroup
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
     * @ORM\OneToMany(targetEntity="GroupUser", mappedBy="userGroup")
     **/
    private $groupUsers;

    public function __construct() {
        $this->groupUsers = new ArrayCollection();
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
     * Set title
     *
     * @param string $title
     * @return UserGroup
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
     * Add groupUsers
     *
     * @param \Wit\ModelBundle\Entity\GroupUser $groupUsers
     * @return UserGroup
     */
    public function addGroupUser(\Wit\ModelBundle\Entity\GroupUser $groupUsers)
    {
        $this->groupUsers[] = $groupUsers;

        return $this;
    }

    /**
     * Remove groupUsers
     *
     * @param \Wit\ModelBundle\Entity\GroupUser $groupUsers
     */
    public function removeGroupUser(\Wit\ModelBundle\Entity\GroupUser $groupUsers)
    {
        $this->groupUsers->removeElement($groupUsers);
    }

    /**
     * Get groupUsers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGroupUsers()
    {
        return $this->groupUsers;
    }
}
