<?php

namespace PrincipalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Target
 *
 * @ORM\Table(name="target")
 * @ORM\Entity(repositoryClass="PrincipalBundle\Repository\TargetRepository")
 */
class Target
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
     * @ORM\Column(name="domainList", type="string", length=255)
     */
    private $domainList;

    /**
     * @var string
     *
     * @ORM\Column(name="urlList", type="string", length=255)
     */
    private $urlList;

    /**
     * @var string
     *
     * @ORM\Column(name="regularExpression", type="string", length=255)
     */
    private $regularExpression;

    /**
     * @var string
     *
     * @ORM\Column(name="redirect", type="string", length=255)
     */
    private $redirect;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="nameGroup", type="string", length=25)
     */
    private $nameGroup;

    /**
     * @var string
     *
     * @ORM\Column(name="redirectMode", type="string", length=25)
     */
    private $redirectMode;

    /**
     * @ORM\ManyToOne(targetEntity="Log", inversedBy="targetLog")
     * @ORM\JoinColumn(name="log_id", referencedColumnName="id")
     */
    private $log;


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
     * Set domainList
     *
     * @param string $domainList
     *
     * @return Target
     */
    public function setDomainList($domainList)
    {
        $this->domainList = $domainList;

        return $this;
    }

    /**
     * Get domainList
     *
     * @return string
     */
    public function getDomainList()
    {
        return $this->domainList;
    }

    /**
     * Set urlList
     *
     * @param string $urlList
     *
     * @return Target
     */
    public function setUrlList($urlList)
    {
        $this->urlList = $urlList;

        return $this;
    }

    /**
     * Get urlList
     *
     * @return string
     */
    public function getUrlList()
    {
        return $this->urlList;
    }

    /**
     * Set regularExpression
     *
     * @param string $regularExpression
     *
     * @return Target
     */
    public function setRegularExpression($regularExpression)
    {
        $this->regularExpression = $regularExpression;

        return $this;
    }

    /**
     * Get regularExpression
     *
     * @return string
     */
    public function getRegularExpression()
    {
        return $this->regularExpression;
    }

    /**
     * Set redirect
     *
     * @param string $redirect
     *
     * @return Target
     */
    public function setRedirect($redirect)
    {
        $this->redirect = $redirect;

        return $this;
    }

    /**
     * Get redirect
     *
     * @return string
     */
    public function getRedirect()
    {
        return $this->redirect;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Target
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
     * Set nameGroup
     *
     * @param string $nameGroup
     *
     * @return Users
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
     * Set redirectMode
     *
     * @param string $redirectMode
     *
     * @return Target
     */
    public function setRedirectMode($redirectMode)
    {
        $this->redirectMode = $redirectMode;

        return $this;
    }

    /**
     * Get redirectMode
     *
     * @return string
     */
    public function getRedirectMode()
    {
        return $this->redirectMode;
    }

    /**
     * Set log
     *
     * @param \PrincipalBundle\Entity\Log $log
     *
     * @return Target
     */
    public function setLog(\PrincipalBundle\Entity\Log $log = null)
    {
        $this->log = $log;

        return $this;
    }

    /**
     * Get log
     *
     * @return \PrincipalBundle\Entity\Log
     */
    public function getLog()
    {
        return $this->log;
    }
}
