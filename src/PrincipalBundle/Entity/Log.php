<?php

namespace PrincipalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Log
 *
 * @ORM\Table(name="log")
 * @ORM\Entity(repositoryClass="PrincipalBundle\Repository\LogRepository")
 */
class Log
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=3)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="Target", mappedBy="log", cascade={"persist"})
     */
    private $targetLog;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Log
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
     * Constructor
     */
    public function __construct()
    {
        $this->targetLog = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add targetLog
     *
     * @param \PrincipalBundle\Entity\Target $targetLog
     *
     * @return Log
     */
    public function addTargetLog(\PrincipalBundle\Entity\Target $targetLog)
    {
        $this->targetLog[] = $targetLog;

        return $this;
    }

    /**
     * Remove targetLog
     *
     * @param \PrincipalBundle\Entity\Target $targetLog
     */
    public function removeTargetLog(\PrincipalBundle\Entity\Target $targetLog)
    {
        $this->targetLog->removeElement($targetLog);
    }

    /**
     * Get targetLog
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTargetLog()
    {
        return $this->targetLog;
    }
}
