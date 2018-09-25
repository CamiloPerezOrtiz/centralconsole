<?php

namespace PrincipalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AliasesName
 *
 * @ORM\Table(name="aliases_name")
 * @ORM\Entity(repositoryClass="PrincipalBundle\Repository\AliasesNameRepository")
 */
class AliasesName
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="nameGroup", type="string", length=25)
     */
    private $nameGroup;

    /**
     * @ORM\OneToMany(targetEntity="AliasesDescription", mappedBy="user", cascade={"persist", "remove"})
     */
    private $exp;


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
     * Set name
     *
     * @param string $name
     *
     * @return AliasesName
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return AliasesName
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
     * Set status
     *
     * @param string $status
     *
     * @return AliasesName
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set nameGroup
     *
     * @param string $nameGroup
     *
     * @return AliasesName
     */
    public function setNameGroup($nameGroup)
    {
        $this->nameGroup = $nameGroup;

        return $this;
    }

    /**
     * Get nameGroup
     *
     * @return string
     */
    public function getNameGroup()
    {
        return $this->nameGroup;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->exp = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add exp
     *
     * @param \PrincipalBundle\Entity\AliasesDescription $exp
     *
     * @return AliasesName
     */
    public function addExp(\PrincipalBundle\Entity\AliasesDescription $exp)
    {
        $this->exp[] = $exp;
        $exp->setUser($this);
        return $this;
    }

    /**
     * Remove exp
     *
     * @param \PrincipalBundle\Entity\AliasesDescription $exp
     */
    public function removeExp(\PrincipalBundle\Entity\AliasesDescription $exp)
    {
        $this->exp->removeElement($exp);
    }

    /**
     * Get exp
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExp()
    {
        return $this->exp;
    }
}
