<?php

namespace PrincipalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Acl
 *
 * @ORM\Table(name="acl")
 * @ORM\Entity(repositoryClass="PrincipalBundle\Repository\AclRepository")
 */
class Acl
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
     * @ORM\Column(name="disabled", type="string", length=15)
     */
    private $disabled;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=40)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="client", type="text")
     */
    private $client;

    /**
     * @var string
     *
     * @ORM\Column(name="time", type="text")
     */
    private $time;

    /**
     * @var string
     *
     * @ORM\Column(name="targetRule", type="text")
     */
    private $targetRule;

    /**
     * @var string
     *
     * @ORM\Column(name="allowIp", type="string", length=3)
     */
    private $allowIp;

    /**
     * @var string
     *
     * @ORM\Column(name="redirectMode", type="string", length=40)
     */
    private $redirectMode;

    /**
     * @var string
     *
     * @ORM\Column(name="redirect", type="string", length=30)
     */
    private $redirect;

    /**
     * @var string
     *
     * @ORM\Column(name="safeSearch", type="string", length=3)
     */
    private $safeSearch;

    /**
     * @var string
     *
     * @ORM\Column(name="rewrite", type="string", length=40)
     */
    private $rewrite;

    /**
     * @var string
     *
     * @ORM\Column(name="rewriteTime", type="string", length=40)
     */
    private $rewriteTime;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=40)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="nameGroup", type="string", length=25)
     */
    private $nameGroup;

    /**
     * @ORM\ManyToOne(targetEntity="Log", inversedBy="aclLog")
     * @ORM\JoinColumn(name="acl_id", referencedColumnName="id")
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
     * Set disabled
     *
     * @param string $disabled
     *
     * @return Acl
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;

        return $this;
    }

    /**
     * Get disabled
     *
     * @return string
     */
    public function getDisabled()
    {
        return $this->disabled;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Acl
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
     * Set client
     *
     * @param string $client
     *
     * @return Acl
     */
    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return string
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set time
     *
     * @param string $time
     *
     * @return Acl
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return string
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set targetRule
     *
     * @param string $targetRule
     *
     * @return Acl
     */
    public function setTargetRule($targetRule)
    {
        $this->targetRule = $targetRule;

        return $this;
    }

    /**
     * Get targetRule
     *
     * @return string
     */
    public function getTargetRule()
    {
        return $this->targetRule;
    }

    /**
     * Set allowIp
     *
     * @param string $allowIp
     *
     * @return Acl
     */
    public function setAllowIp($allowIp)
    {
        $this->allowIp = $allowIp;

        return $this;
    }

    /**
     * Get allowIp
     *
     * @return string
     */
    public function getAllowIp()
    {
        return $this->allowIp;
    }

    /**
     * Set redirectMode
     *
     * @param string $redirectMode
     *
     * @return Acl
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
     * Set redirect
     *
     * @param string $redirect
     *
     * @return Acl
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
     * Set safeSearch
     *
     * @param string $safeSearch
     *
     * @return Acl
     */
    public function setSafeSearch($safeSearch)
    {
        $this->safeSearch = $safeSearch;

        return $this;
    }

    /**
     * Get safeSearch
     *
     * @return string
     */
    public function getSafeSearch()
    {
        return $this->safeSearch;
    }

    /**
     * Set rewrite
     *
     * @param string $rewrite
     *
     * @return Acl
     */
    public function setRewrite($rewrite)
    {
        $this->rewrite = $rewrite;

        return $this;
    }

    /**
     * Get rewrite
     *
     * @return string
     */
    public function getRewrite()
    {
        return $this->rewrite;
    }

    /**
     * Set rewriteTime
     *
     * @param string $rewriteTime
     *
     * @return Acl
     */
    public function setRewriteTime($rewriteTime)
    {
        $this->rewriteTime = $rewriteTime;

        return $this;
    }

    /**
     * Get rewriteTime
     *
     * @return string
     */
    public function getRewriteTime()
    {
        return $this->rewriteTime;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Acl
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
     * Set log
     *
     * @param \PrincipalBundle\Entity\Log $log
     *
     * @return Acl
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
