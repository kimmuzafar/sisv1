<?php

namespace Wit\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GroupUser
 *
 * @ORM\Table(name="group_users")
 * @ORM\Entity
 */
class GroupUser
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
     * @ORM\ManyToOne(targetEntity="UserGroup", inversedBy="groupUsers")
     * @ORM\JoinColumn(name="usergroup_id", referencedColumnName="id", onDelete="CASCADE")
     **/
    private $userGroup;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    private $user;

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
     * Set userGroup
     *
     * @param \Wit\ModelBundle\Entity\UserGroup $userGroup
     * @return GroupUser
     */
    public function setUserGroup(\Wit\ModelBundle\Entity\UserGroup $userGroup = null)
    {
        $this->userGroup = $userGroup;

        return $this;
    }

    /**
     * Get userGroup
     *
     * @return \Wit\ModelBundle\Entity\UserGroup 
     */
    public function getUserGroup()
    {
        return $this->userGroup;
    }

    /**
     * Set user
     *
     * @param \Wit\ModelBundle\Entity\User $user
     * @return GroupUser
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
}
