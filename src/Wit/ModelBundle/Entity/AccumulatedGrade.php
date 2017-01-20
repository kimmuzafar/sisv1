<?php

namespace Wit\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AccumulatedGrade
 *
 * @ORM\Table(name="accumulated_grades")
 * @ORM\Entity
 */
class AccumulatedGrade
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
     * @ORM\Column(name="title", type="string", length=10)
     */
    private $title;

    /**
     * AccumulatedGrade Title
     *
     * @return string
     */
    public function __toString()
    {
        return strval($this->title);
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
     * @return AccumulatedGrade
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
}
