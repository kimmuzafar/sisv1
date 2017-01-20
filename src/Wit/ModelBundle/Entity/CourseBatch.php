<?php

namespace Wit\ModelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Course Batch
 *
 * @ORM\Table(name="course_batches")
 * @ORM\Entity
 */
class CourseBatch
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
     * @ORM\Column(name="title", type="string", length=128)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=32, nullable=true)
     */
    private $code;

    /**
     * @ORM\ManyToOne(targetEntity="CourseCategory")
     * @ORM\JoinColumn(name="coursecategory_id", referencedColumnName="id")
     **/
    private $coursecategory;

    /**
     * CourseBatch Title
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
     * @return CourseBatch
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
     * Set coursecategory
     *
     * @param \Wit\ModelBundle\Entity\CourseCategory $coursecategory
     * @return CourseBatch
     */
    public function setCoursecategory(\Wit\ModelBundle\Entity\CourseCategory $coursecategory = null)
    {
        $this->coursecategory = $coursecategory;

        return $this;
    }

    /**
     * Get coursecategory
     *
     * @return \Wit\ModelBundle\Entity\CourseCategory 
     */
    public function getCoursecategory()
    {
        return $this->coursecategory;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return CourseBatch
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }
}
